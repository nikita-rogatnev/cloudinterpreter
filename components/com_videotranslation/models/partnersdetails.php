<?php

/**
 * @package     Joomla.Tutorials
 * @subpackage  Component
 * @copyright   Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license     License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
require_once "details.php";
jimport('joomla.application.component.modelitem');

class VideoTranslationModelPartnersDetails extends VideoTranslationModelDetails
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
//            $session = JFactory::getSession();
//            $cart = $session->get('cart');

            if(isset($cart['partnersdetails']) && count($cart['partnersdetails'])) {
                //return $cart['partnersdetails'];
            }
            else {
                return array();
            }
        }
        else {
            return $data;
        }
    }

    public function save() {

        $mainframe =& JFactory::getApplication();

        $data  = JRequest::get('post');

        $session = JFactory::getSession();
        $cart = $session->get('cart');

        $cart['partnersdetails'] = $data['jform'];

        return $this->saveCart($cart, $session, $mainframe);

        
    }
    

    function getLink($usertype,$event,$order) {

        //return $event->location."&order_id=".$order->id."&usertype=".$usertype."&Itemid=155&lang=ru";
        return $event->location;

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

    function differenceTimeInviterInvitee($inviterTimezone, $inviteeTimezone) {

        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $model = JModelLegacy::getInstance('FrontPage', 'VideoTranslationModel', array('ignore_request' => true));

        return ($model->getTimeZone($inviterTimezone) - $model->getTimeZone($inviteeTimezone))*60*60;
    }

    function getInvitationTextArea() {

        require_once(JPATH_SITE.'/components/com_videotranslation/lib/russian-date.php');

        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $cart_model = JModelLegacy::getInstance('Cart', 'VideoTranslationModel', array('ignore_request' => true));

        $items = $cart_model->getItems();


        //echo date('Y m d H:i:s',$items[0]->start); die;

//        echo "<pre>";
//        print_r($items); die;

        $this->items = $items;

        $session = JFactory::getSession();
        $cart = $session->get('cart');

        $this->difference = $this->differenceTimeInviterInvitee($cart['my_partner_timezone'], $cart['my_timezone']);

//      $this->difference = 0;
 //     echo $this->difference; die;
//echo $cart['my_partner_timezone']; die;


        $languageTable =& JTable::getInstance('language', 'VideoTranslationTable');
        $languageTable->load($cart['your_partner_language']);

        $lang = JFactory::getLanguage();
        $lang->load('com_videotranslation' , JPATH_SITE, $languageTable->system_language);

        $tag = substr($languageTable->system_language,0,2);

        $link = '[LINK]';


//        $time = ' ';
//        for($i=0;$i<count($items);$i++) {
//
//            $time
//
//        }

//        echo "<pre>";
//        print_r($items); die;

        $text =  JText::sprintf( 'COM_VIDEOTRANSLATION_BODY_PARTNER1',
            maxsite_the_russian_time(date('j F',($items[0]->start+$this->difference)),$tag),
            date('H:i',($items[0]->start+$this->difference)),
            date('H:i',($items[0]->end+$this->difference)),
            $cart['my_partner_timezone'],
            $link
        );

        $text = str_replace("<br />","\r\n",$text);

        $lang->load('com_videotranslation' , JPATH_SITE, $lang->getTag(), true);

        return $text;
    }

}
 