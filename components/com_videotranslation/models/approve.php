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

class VideoTranslationModelApprove extends JModelForm
{

    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_videotranslation.approve', 'approve', array('control' => 'jform', 'load_data' => $loadData));
        return $form;
    }

    protected function loadFormData()
    {

    }

    public function getwasApproved() {


        $order_id = JRequest::getInt('order_id');

        $order =& JTable::getInstance('order', 'VideoTranslationTable');
        $order->load($order_id);

        $event =& JTable::getInstance('event', 'VideoTranslationTable');
        $event->load($order->event_id);


        $item['order'] = $order;
        $item['event'] = $event;

        return $item;
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
            $order->date = date('j F',$event->dtstart-$difference);
            $order->start = date('H:i',$event->dtstart-$difference);
            $order->end = date('H:i',$event->dtend-$difference);


            return $order;
        }
        else {
            return array();
        }
    }


    function mailGeneration($recipient,$subject,$body) {

        $mailer =& JFactory::getMailer();
        $config =& JFactory::getConfig();

        //sender
        $sender = array(
            $config->get( 'config.mailfrom' ),
            $config->get( 'config.fromname' )
        );

        $mailer->setSender($sender);
        $mailer->addRecipient($recipient);

        $mailer->isHTML(true);
        $mailer->setSubject($subject);

        $mailer->setBody($body);

        $send =& $mailer->Send();
    }

    function sentNotificationEmailTranslator($order,$event) {

        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $model = JModelLegacy::getInstance('PartnersDetails', 'VideoTranslationModel', array('ignore_request' => true));

        //$difference = $model->differenceTime($order->user_timezone);

        $difference = 0;

        $translator =& JFactory::getUser($order->translator_id);
        $translator_email = $translator->email;

        $recipient = array( $translator_email );

        $subject = JText::sprintf(

            'COM_VIDEOTRANSLATION_TRANSLATOR_SUBJECT_THEME2',
            maxsite_the_russian_time(date('j F H:i',$event->dtstart+$difference)).' - '. date('H:i',$event->dtend+$difference)

        );

        //$link = $event->location."&order_id=".$order->id."&usertype=3&Itemid=155&lang=ru";
        $link = $event->location;

        $body = JText::sprintf(

            'COM_VIDEOTRANSLATION_TRANSLATOR_BODY',
            $order->first_name.' '.$order->last_name,
            $order->envitee_name,
            maxsite_the_russian_time(date('j F',$event->dtstart+$difference)),
            date('H:i',$event->dtstart+$difference),
            date('H:i',$event->dtend+$difference),
            $link

        );

        $body .= "<br /> ".JText::_("COM_VIDEOTRANSLATION_SIGNATURE");


        $this->mailGeneration($recipient,$subject,$body);
    }

    function sentInvitationEmail($order, $event, $approved) {

        require_once(JPATH_SITE.'/components/com_videotranslation/lib/russian-date.php');

        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $model = JModelLegacy::getInstance('PartnersDetails', 'VideoTranslationModel', array('ignore_request' => true));


        $language_tag = $model->getSystemLanguage($order->your_language);
        $tag = $model->LoadSpecificLanguageFile($language_tag);


        $difference = $model->differenceTime($order->user_timezone);

        //recipient
        $user =& JFactory::getUser($order->user_id);
        //$customer_email = $user->email;
        $customer_email = $order->email;

        $recipient = array( $customer_email );

        $subject = JText::sprintf('COM_VIDEOTRANSLATION_SUBJECT_CUSTOMER_THEME2',$order->envitee_name);

        if($approved) {

            //$link = $event->location."&order_id=".$order->id."&usertype=1&Itemid=155&lang=".$tag;
            $link = $event->location;


            $link = '
                        <a style="
                            width:145px;
                            height: 41px;
                            display: block;
                            background-color: #ed6f6d;
                            color: #ffffff;
                            cursor: pointer;
                            text-align:center;
                            display:table-cell;
                            vertical-align:middle;
                            border-radius: 4px;
                            text-decoration:none;
                            "
                            href="'.$link.'"

                            >'.JText::_("COM_VIDEOTRASLATION_SESSION_LINK").'</a>

                ';


            $body = '<table width="100%" cellpadding="10" >
                    <tr>
                        <td bgcolor="#252d35">
                            <img src="'.JURI::base().'images/logo.png" alt="logo"/>
                        </td>
                    </tr>
                ';

            $body .= '<tr><td>'.JText::sprintf(

                'COM_VIDEOTRANSLATION_BODY_CUSTOMER2'
                ,$order->envitee_name,
                maxsite_the_russian_time(date('j F',$event->dtstart-$difference),$tag),
                date('H:i',$event->dtstart-$difference),
                date('H:i',$event->dtend-$difference),
                $order->user_timezone,
                $link
            ).'</td></tr>';


         }
        else {
            $request = JRequest::getVar('jform');


            switch($request['reason_refuse']) {
                case 1:
                    $reason_text = JText::_('COM_VIDEOTRANSLATION_REASON_REFUSE1');
                break;
                case 2:
                    $reason_text = JText::_('COM_VIDEOTRANSLATION_REASON_REFUSE2');
                break;
                case 3:
                    $reason_text = $request['the_other_reason_text'];
                break;
            }


            $body = '<tr><td>'.JText::sprintf('COM_VIDEOTRANSLATION_THE_MEETING_WASNT_APPROVED',$order->envitee_name,  maxsite_the_russian_time(date('j F H:i',$event->dtstart-$difference),$tag).' - '. date('H:i',$event->dtend-$difference), $reason_text).'</td></tr>';
        }

        $body .= "<tr><td><br /> ".JText::_("COM_VIDEOTRANSLATION_SIGNATURE").'</td></tr></table>';

        $this->mailGeneration($recipient,$subject,$body);
    }

    function sentNotificationEmail($order, $event) {

        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $model = JModelLegacy::getInstance('PartnersDetails', 'VideoTranslationModel', array('ignore_request' => true));

        $language_tag = $model->getSystemLanguage($order->your_partner_language);
        $tag = $model->LoadSpecificLanguageFile($language_tag);


        $difference = $model->differenceTime($order->envitee_timezone);

        $subject = JText::sprintf('COM_VIDEOTRANSLATION_SUBJECT_PARTNER_THEME2',$order->first_name.' '.$order->last_name);

        //recipient
        $recipient = array( $order->envitee_email );

        //$link = $event->location."&order_id=".$order->id."&usertype=2&Itemid=155&lang=".$tag;
        $link = $event->location;

        $link = '
                        <a style="
                            width:145px;
                            height: 41px;
                            display: block;
                            background-color: #ed6f6d;
                            color: #ffffff;
                            cursor: pointer;
                            text-align:center;
                            display:table-cell;
                            vertical-align:middle;
                            border-radius: 4px;
                            text-decoration:none;
                            "
                            href="'.$link.'"

                            >'.JText::_("COM_VIDEOTRASLATION_SESSION_LINK").'</a>

                ';


        $body = '<table width="100%" cellpadding="10" >
                    <tr>
                        <td bgcolor="#252d35">
                            <img src="'.JURI::base().'images/logo.png" alt="logo"/>
                        </td>
                    </tr>
                ';

        $body .= '<tr><td>'.JText::sprintf(

            'COM_VIDEOTRANSLATION_BODY_PARTNER2',
            $order->first_name.' '.$order->last_name,
            maxsite_the_russian_time(date('j F',$event->dtstart-$difference),$tag),
            date('H:i',$event->dtstart-$difference),
            date('H:i',$event->dtend-$difference),
            $order->envitee_timezone,
            $link

        ).'</td></tr>';

        $body .= "<tr><td><br /> ".JText::_("COM_VIDEOTRANSLATION_SIGNATURE").'</td></tr></table>';

        $this->mailGeneration($recipient,$subject,$body);
    }


    public function getApprove() {

        require_once(JPATH_SITE.'/components/com_videotranslation/lib/russian-date.php');

        $mainframe =& JFactory::getApplication();

        $approve = JRequest::getVar('approve');

        $order_id = JRequest::getInt('order_id');

        if($order_id) {
            $order =& JTable::getInstance('order', 'VideoTranslationTable');
            $order->load($order_id);

            $vevent =& JTable::getInstance('vevent', 'VideoTranslationTable');
            $vevent->load($order->event_id);

            $event =& JTable::getInstance('event', 'VideoTranslationTable');
            $event->load($order->event_id);
        }

        if($approve['approve'] && $order_id) {

            $vevent->state = $approve['approve'];

            $order->approved = 1;

            $order->store();

            if(!$vevent->store()){
                JError::raiseError(500, $vevent->getError() );
            }
            else {

                //email to translator
                $this->sentNotificationEmailTranslator($order,$event);

                //email to customer
                $this->sentInvitationEmail($order,$event,true);

                //email to envitee
                $this->sentNotificationEmail($order,$event);

                $msg=JText::_('COM_VIDEOTRANSLATION_ORDER_WAS_APPROVED') . ' '. JText::_('COM_VIDEOTRANSLATION_THANK_YOU');

//                $user =& JFactory::getUser($order->user_id);
//                $customer_email = $user->email;
                $emails_addresses = $order->envitee_email.", ".$order->email;
                $msg .= JText::sprintf('COM_VIDEOTRANSLATION_EMAILS_WAS_SENT_ON_ADDRESSES',$emails_addresses);

                JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
                $model = JModelLegacy::getInstance('PartnersDetails', 'VideoTranslationModel', array('ignore_request' => true));

                $msg .= JText::sprintf('COM_VIDEOTRANSLATION_LINK_ON_CONFERENCE',$model->getLink(2,$event,$order));

                $mainframe->Redirect('index.php?option=com_videotranslation&view=eventsaved&Itemid=493',$msg);
                return false;
            }
        }

        elseif(!$approve['approve'] && $order_id) {

            $order->approved = -1;
            $order->store();

//            //email to customer
//            $this->sentInvitationEmail($order,$event,false);
//
//            $amount = $order->amount;
//            $user_id = $order->user_id;
//
//            $db = JFactory::getDBO();
//            $query = $db->getQuery(true);
//            $query = "UPDATE #__vt_credits SET `amount`=`amount`+".$amount." WHERE user_id = ".$user_id;
//
//            $db->setQuery($query);
//            $db->query();

            $msg=JText::sprintf('COM_VIDEOTRANSLATION_ORDER_WAS_CANCELLED',$order->first_name.' '.$order->last_name);
            $mainframe->Redirect('index.php?option=com_videotranslation&view=reasonsrefuse&order_id='.$order->id.'&Itemid=493&ses_id='.$order->ses_id,$msg);
            return false;
        }
    }



}
