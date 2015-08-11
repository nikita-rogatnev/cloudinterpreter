<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modelitem');

class VideoTranslationModelPartnersDetails extends JModelForm
{

    public function getForm($data = array(), $loadData = true)
    {

        // Get the form.
        $form = $this->loadForm('com_videotranslation.partnersdetails', 'partnersdetails', array('control' => 'jform', 'load_data' => $loadData));
        return $form;
    }

    protected function loadFormData()
    {
        $app  = JFactory::getApplication();
        $data = $app->getUserState('com_videotranslation.edit.partnersdetails.data', array());

        if (empty($data))
        {
            $session = JFactory::getSession();
            $cart = $session->get('cart');

            if(isset($cart['partnersdetails']) && count($cart['partnersdetails'])) {
                return $cart['partnersdetails'];
            }
            else {
                return array();
            }
        }
        else {
            return $data;
        }
    }


    public function makePayment() {

        $user =& JFactory::getUser();

        $session = JFactory::getSession();
        $cart = $session->get('cart');

        $new_amount = $cart['balance']-$cart['userdetails']['amount'];

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query = "UPDATE #__vt_credits SET `amount`=".$new_amount." WHERE user_id = ".$user->id;

        $db->setQuery($query);
        $db->query();

        $cart['userdetails']['payed'] = 1;

    }

    public function save() {

        $mainframe =& JFactory::getApplication();

        $data  = JRequest::get('post');

        $session = JFactory::getSession();
        $cart = $session->get('cart');

        $cart['partnersdetails'] = $data['jform'];

        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $cart_model = JModelLegacy::getInstance('Cart', 'VideoTranslationModel', array('ignore_request' => true));

        $items = $cart_model->getItems();

        $cart['balance'] = $cart_model->getBalance();

        $session->set('cart', $cart);

        if(!isset($cart['userdetails']['payed'])) {
            $this->makePayment();
        }

        /* include jvents library here */
//        define("JEV_COM_COMPONENT","com_jevents");
//        define("JEV_ADMINPATH",JPATH_ADMINISTRATOR."/components/".JEV_COM_COMPONENT."/");
//        define("JEV_ADMINLIBS",JEV_ADMINPATH."libraries/");
        include_once(JPATH_ADMINISTRATOR . "/components/com_jevents/jevents.defines.php");

        //JLoader::register('SaveIcalEvent',JEV_ADMINLIBS."saveIcalEvent.php");
        //JLoader::register('JEVConfig', JPATH_ADMINISTRATOR . "/components/com_jevents/libraries/config.php");

        $cfg = & JEVConfig::getInstance();

        $this->dataModel = new JEventsDataModel("JEventsAdminDBModel");
        $this->queryModel = new JEventsDBModel($this->dataModel);

        require_once(JPATH_SITE.'/components/com_videotranslation/lib/russian-date.php');

        for($i=0;$i<count($items);$i++) {

            $array = $this->generateArray($items[$i],$cart,$session);

            $rrule = SaveIcalEvent::generateRRule($array);

            if ($event = SaveIcalEvent::save($array, $this->queryModel, $rrule))
            {

                $row = new jIcalEventDB($event);
                //if (JEVHelper::canPublishEvent($row))
                //{
                    //$msg = JText::_("Event_Saved", true);

                    if($event->ev_id) {
                        $translator_id = $this->applyTranslatorOnTheEvent($event->ev_id,$items[$i]);

                        $cart['translator_id'] = $translator_id;
                        $session->set('cart', $cart);

                        $time = ' '.maxsite_the_russian_time(date('j F H:i',$items[$i]->start)).' - '. date('H:i',$items[$i]->end);
                        unset($cart['items'][$items[$i]->start]);

                        for($j=0;$j<count($items[$i]->time);$j++) {
                            unset($cart['items'][$items[$i]->time[$j]]);
                        }

                        $order = $this->addOrder($event,$session,$translator_id);

                        $event =& JTable::getInstance('event', 'VideoTranslationTable');
                        $event->load($order->event_id);


                        //email for envitee
                        $this->sentInvitationEmail($order,$event);
                        //email for customer
                        $this->sentNotificationEmail($order,$event);
                    }
                    else {
                        $msg = JText::_('COM_VIDEOTRANSLATION_WASNT_ADDED_IN_THE_SYSTEM_CORRECT');
                        $mainframe->Redirect('index.php?option=com_videotranslation&view=eventsaved&Itemid=493',$msg);
                        return false;
                    }
               //}
                //else
                //{
                    //$msg = JText::_("EVENT_SAVED_UNDER_REVIEW", true);
                //}
            }
            else
            {
                //$msg = JText::_("Event Not Saved", true);
                $row = null;
            }
        }

        $msg = JText::sprintf('COM_VIDEOTRANSLATION_WAS_ADDED_IN_THE_SYSTEM',$time);

        $user =& JFactory::getUser($order->user_id);
        $customer_email = $user->email;
        $emails_addresses = $order->envitee_email.", ".$customer_email;
        $msg .= JText::sprintf('COM_VIDEOTRANSLATION_EMAILS_WAS_SENT_ON_ADDRESSES',$emails_addresses);

        $msg .= JText::sprintf('COM_VIDEOTRANSLATION_LINK_ON_CONFERENCE',$this->getLink(1,$event,$order));


//        echo "<pre>";
//        print_r($cart); die;


        $session->set('cart', $cart);

        $mainframe->Redirect('index.php?option=com_videotranslation&view=eventsaved&Itemid=493',$msg);
        return false;
    }

    function getLink($usertype,$event,$order) {

        return $event->location."&order_id=".$order->id."&usertype=".$usertype."&Itemid=155&lang=ru";

    }

    function addOrder($event,$session,$translator_id) {

        $user =& JFactory::getUser();
        $cart = $session->get('cart');

        $row =& JTable::getInstance('order', 'VideoTranslationTable');

        $data = array();
        $data['ses_id'] = $session->getId();
        $data['event_id'] = $event->ev_id;
        $data['user_id'] = $user->id;
        $data['user_timezone'] = $cart['my_timezone'];

        $data['first_name'] = $cart['userdetails']['first_name'];
        $data['last_name'] = $cart['userdetails']['last_name'];

        $data['envitee_email'] = $cart['partnersdetails']['partner_email'];
        $data['envitee_name'] = $cart['partnersdetails']['partner_name'];
        $data['envitee_phone'] = $cart['partnersdetails']['partner_phone'];
        $data['envitee_timezone'] = $cart['my_partner_timezone'];
        $data['amount'] = $cart['userdetails']['amount'];
        $data['time'] = date("Y-m-d H:i:s");
        $data['translator_id'] = $translator_id;
        $data['partner_invitation1'] = $cart['partnersdetails']['partner_invitation1'];
        $data['partner_invitation2'] = $cart['partnersdetails']['partner_invitation2'];
        $data['partner_personal_note'] = $cart['partnersdetails']['partner_personal_note'];

        if(!$row->bind($data))
        {
            JError::raiseError(500, $row->getError() );
        }
        if(!$row->store()){
            JError::raiseError(500, $row->getError() );
        }

        return $row;
    }

    function applyTranslatorOnTheEvent($id,$item) {

            if($id) {
                $translator_id = $this->getTranslator($item);
                if($translator_id) {
                    $db = JFactory::getDBO();
                    $query = $db->getQuery(true);
                    $query = "UPDATE #__jevents_vevent SET `created_by`=".$translator_id." WHERE ev_id = ".$id;

                    $db->setQuery($query);
                    $db->query();
                    return $translator_id;
                }
                else {
                    return false;
                }
            }
            return false;
    }

    function getTranslator($item) {

        $session = JFactory::getSession();
        $cart = $session->get('cart');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query = "SELECT a.user_id, count(c.ev_id) AS count_events FROM #__vt_translatorpair as a, #__vt_user_subject as b "

        ."  LEFT JOIN #__jevents_vevent AS c ON b.user_id = c.created_by"
        ."  WHERE a.pair_id = ".$cart['userdetails']['pair_id']." AND b.user_id = a.user_id AND b.subj_id = ".$cart['userdetails']['subj_id']
        ."  GROUP BY b.user_id ORDER BY count_events "

        ;

        $db->setQuery($query);
        $rows = $db->loadObjectList();

        for($i=0;$i<count($rows);$i++) {
            $query = "SELECT a.evdet_id FROM #__jevents_vevdetail as a, #__jevents_vevent as b WHERE a.evdet_id = b.ev_id AND a.dtstart < ".$item->start." AND a.dtend > ".$item->end."  AND b.created_by = ".$rows[$i]->user_id;
            $db->setQuery($query);
            $row = $db->loadObject();

            if(!count($row)) {
                return $rows[$i]->user_id;
            }
        }

//        echo "<pre>";
//        print_r($rows);
//        print_r($item); die;
//
//
//        return $row->user_id;

    }

//    function sentInvitationEmail($cart,$item,$session,$order) {
//
//        $difference = $this->differenceTime($cart['my_partner_timezone']);
//
//        $recipient = array( $cart['partnersdetails']['partner_email'] );
//
//        $subject = 'Invitation on meeting from '.$cart['userdetails']['first_name'].' '.$cart['userdetails']['last_name'];
//
//        $link = JRoute::_(JURI::base().'index.php?option=com_videotranslation&view=approve&order_id='.$order->id.'&ses_id='.$session->getId());
//
//        $body   = 'Hello. We would like to inform you about <b>'.$cart['userdetails']['first_name'].' '.$cart['userdetails']['last_name'].'</b> invites you on meetings <b>'.date('j F H:i',$item->start-$difference).' - '. date('H:i',$item->end-$difference).'</b> '.$cart['my_partner_timezone'].' time. <br />
//                    Please follow the <a href="'.$link.'">link</a> to approve this meeting.';
//
//        echo $body; die;
//
//        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
//        $model = JModelLegacy::getInstance('approve', 'VideoTranslationModel', array('ignore_request' => true));
//
//        $model->mailGeneration($recipient,$subject,$body);
//    }

    function sentInvitationEmail($order, $event) {

        $difference = $this->differenceTime($order->envitee_timezone);

        //recipient
        $recipient = array( $order->envitee_email );

        $subject = JText::sprintf( 'COM_VIDEOTRANSLATION_SUBJECT_PARTNER_THEME1', $order->first_name.' '.$order->last_name );

        $link = JRoute::_(JURI::base().'index.php?option=com_videotranslation&view=approve&order_id='.$order->id.'&ses_id='.$order->ses_id.'&Itemid=155&lang=ru');

        $body = JTEXT::sprintf(

            'COM_VIDEOTRANSLATION_BODY_PARTNER1',

            $order->first_name.' '.$order->last_name,
            maxsite_the_russian_time(date('j F',$event->dtstart-$difference)),
            date('H:i',$event->dtstart-$difference),
            date('H:i',$event->dtend-$difference),
            $order->envitee_timezone,
            $link

        );

        $body .= "<br /><br /> ".$order->partner_invitation1;

        $body .= "<br /><br /> ".$order->partner_invitation2;

        $body .= "<br /><br /> ".$order->partner_personal_note;

        $body .= "<br /> ".JText::_("COM_VIDEOTRANSLATION_SIGNATURE");


        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $model = JModelLegacy::getInstance('approve', 'VideoTranslationModel', array('ignore_request' => true));

        $model->mailGeneration($recipient,$subject,$body);
    }

    function sentNotificationEmail($order, $event) {

        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $model = JModelLegacy::getInstance('PartnersDetails', 'VideoTranslationModel', array('ignore_request' => true));

        $difference = $model->differenceTime($order->user_timezone);

        //recipient
        $user =& JFactory::getUser($order->user_id);
        $customer_email = $user->email;

        $recipient = array( $customer_email );

        $subject = JText::sprintf('COM_VIDEOTRANSLATION_SUBJECT_CUSTOMER_THEME1', $order->envitee_name);

        $link = $event->location."&order_id=".$order->id."&usertype=1&Itemid=155&lang=ru";

        $body = JText::sprintf(

            'COM_VIDEOTRANSLATION_BODY_CUSTOMER',
            $order->envitee_name,
            maxsite_the_russian_time(date('j F',$event->dtstart-$difference)),
            date('H:i',$event->dtstart-$difference),
            date('H:i',$event->dtend-$difference),
            $order->user_timezone
            //$link

        );

        $body .= "<br /> ".JText::_("COM_VIDEOTRANSLATION_SIGNATURE");

        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $model = JModelLegacy::getInstance('approve', 'VideoTranslationModel', array('ignore_request' => true));

        $model->mailGeneration($recipient,$subject,$body);
    }


//    function sentNotificationEmail($cart,$item,$session) {
//
//        $difference = 0;
//
//        //recipient
//        $recipient = array( $cart['userdetails']['email'] );
//
//        $subject = 'Invitation on meeting '.$cart['partnersdetails']['partner_name'];
//
//        $body   = 'Hello. We would like to inform you about we sent invitation <b>'.$cart['partnersdetails']['partner_name'].'</b> on meetings <b>'.date('j F H:i',$item->start-$difference).' - '. date('H:i',$item->end-$difference).'</b> '.$cart['my_timezone'].' time. <br />
//                    You can follow the <a href="http://78.140.138.8/demo?'.$session->getId().'">link</a> in this time if user approves your invitation.';
//
//        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
//        $model = JModelLegacy::getInstance('approve', 'VideoTranslationModel', array('ignore_request' => true));
//
//        $model->mailGeneration($recipient,$subject,$body);
//    }

    function differenceTime($timezone) {

        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $model = JModelLegacy::getInstance('FrontPage', 'VideoTranslationModel', array('ignore_request' => true));

        $user_timezone = $model->getTimeZone($timezone);

        $session = JFactory::getSession();
        $cart = $session->get('cart');

        if(isset($cart['translator_id']) && $cart['translator_id']) {
            $translator = JFactory::getUser($cart['translator_id']);
            $timezone_translator = $translator->getParam('timezone');
        }
        else {
            $timezone_translator = 'Europe/Minsk';
        }

        $translator_timezone = $model->getTimeZone($timezone_translator);


        $difference = ($translator_timezone - $user_timezone)*60*60;

        return $difference;

    }

    function generateArray($item,$cart,$session) {

        $difference = $this->differenceTime($cart['my_timezone']);

        $array['jevtype'] = 'icaldb';
        $array['rp_id'] = -1;
        $array['year'] = date('Y',$item->start);
        $array['month'] = date('n',$item->start);
        $array['day'] = date('j',$item->start);
        $array['state'] = 0;
        $array['evid'] = 0;
        $array['valid_dates'] = 1;
        $array['title'] = 'translation';
        $array['priority'] = 0;
        $array['jev_creatorid'] = -1;
        $array['ics_id'] = 1;
        $array['catid'] = 8;
        $array['access'] = 1;

        $array['jevcontent'] = '';
        $array['location'] = JRoute::_(JURI::base().'index.php?option=com_videotranslation&view=session&ses_id='.$session->getId());

        $array['contact_info'] = $cart['userdetails']['email'];
        $array['extra_info'] = $cart['userdetails']['first_name'].' '.$cart['userdetails']['last_name'].' invites '.$cart['partnersdetails']['partner_name'].'. Contact phone is '.$cart['userdetails']['phone'] ;
        $array['view12Hour'] = 0;
        $array['publish_up'] = date('Y-n-d',$item->start);
        $array['publish_up2'] = date('Y-m-j',$item->start);
        $array['start_time'] = date('H:i',$item->start+$difference);
        $array['start_12h'] = date('h:i',$item->start+$difference);
        $array['start_ampm'] = 'none';
        $array['publish_down'] = date('Y-n-d',$item->end);
        $array['publish_down2'] = date('Y-m-j',$item->end);
        $array['end_time'] = date('H:i',$item->end+$difference);
        $array['end_12h'] = date('h:i',$item->end+$difference);
        $array['end_ampm'] = 'none';
        $array['multiday'] = 1;
        $array['freq'] = 'none';
        $array['rinterval'] = 1;
        $array['countuntil'] = 'count';
        $array['count'] = 1;
        $array['until'] = date('Y-n-d',$item->end);
        $array['until2'] = date('Y-m-j',$item->end);
        $array['byyearday'] = date('z',$item->start);
        $array['bymonth'] = date('n',$item->start);
        $array['byweekno'] = date('w',$item->start);
        $array['bymonthday'] = date('j',$item->start);
        $array['weekdays'] = array(0=>0);
        $array['boxchecked'] = 0;
        $array['updaterepeats'] = 0;
//        $array['jevcontent'] = '';
//        $array['jevcontent'] = '';
//        $array['jevcontent'] = '';
//        $array['jevcontent'] = '';

        return $array;
    }

}
