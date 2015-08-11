<?php

class VideoTranslationModelReasonsRefuse extends JModelForm {


    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_videotranslation.reasonsrefuse', 'reasonsrefuse', array('control' => 'jform', 'load_data' => $loadData));
        return $form;
    }

    protected function loadFormData()
    {

    }

    public function getItem() {

        $id = JRequest::getInt('order_id');
        $ses_id = JRequest::getVar('ses_id');

        $order =& JTable::getInstance('order', 'VideoTranslationTable');
        $order->load($id);

        if($order->event_id && ($order->ses_id == $ses_id)) {

            $event =& JTable::getInstance('event', 'VideoTranslationTable');
            $event->load($order->event_id);

            JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
            $model = JModelLegacy::getInstance('Partnersdetails', 'VideoTranslationModel', array('ignore_request' => true));

            $difference = $model->differenceTime($order->envitee_timezone);

            //date('j F H:i',$item->start-$difference)
            $order->start = date('j F H:i',$event->dtstart-$difference);
            $order->end = date('H:i',$event->dtend-$difference);


            return $order;
        }
        else {
            return array();
        }
    }

    public function getRefuse() {

        $mainframe =& JFactory::getApplication();

        $order_id = JRequest::getInt('order_id');

        $order =& JTable::getInstance('order', 'VideoTranslationTable');
        $order->load($order_id);

        $vevent =& JTable::getInstance('vevent', 'VideoTranslationTable');
        $vevent->load($order->event_id);

        $event =& JTable::getInstance('event', 'VideoTranslationTable');
        $event->load($order->event_id);

        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $model = JModelLegacy::getInstance('approve', 'VideoTranslationModel', array('ignore_request' => true));

        //email to customer
        $model->sentInvitationEmail($order,$event,false);

        $amount = $order->amount;
        $user_id = $order->user_id;

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query = "UPDATE #__vt_credits SET `amount`=`amount`+".$amount." WHERE user_id = ".$user_id;

        $db->setQuery($query);
        $db->query();

        $this->deleteEvent($order->event_id);

        $msg=JText::_('COM_VIDEOTRANSLATION_ORDER_WAS_DELETED');
        $mainframe->Redirect('index.php?option=com_videotranslation&view=eventsaved&Itemid=493',$msg);
        return false;

    }


    public function deleteEvent($id) {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $veventidstring = $id;

        // TODO the ruccurences should take care of all of these??
        // This would fail if all recurrances have been 'adjusted'
        $query = "SELECT DISTINCT (eventdetail_id) FROM #__jevents_repetition WHERE eventid IN ($veventidstring)";
        $db->setQuery($query);
        $detailids = $db->loadColumn();
        $detailidstring = implode(",", $detailids);

        $query = "DELETE FROM #__jevents_rrule WHERE eventid IN ($veventidstring)";
        $db->setQuery($query);
        $db->query();

        $query = "DELETE FROM #__jevents_repetition WHERE eventid IN ($veventidstring)";
        $db->setQuery($query);
        $db->query();

        $query = "DELETE FROM #__jevents_exception WHERE eventid IN ($veventidstring)";
        $db->setQuery($query);
        $db->query();

        $query = "DELETE FROM #__jevents_catmap WHERE evid IN ($veventidstring)";
        $db->setQuery($query);
        $db->query();

        if (strlen($detailidstring) > 0)
        {
            $query = "DELETE FROM #__jevents_vevdetail WHERE evdet_id IN ($detailidstring)";
            $db->setQuery($query);
            $db->query();
        }

        $query = "DELETE FROM #__jevents_vevent WHERE ev_id IN ($veventidstring)";
        $db->setQuery($query);
        $db->query();

    }


}