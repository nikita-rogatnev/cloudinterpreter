<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class VideoTranslationViewSession extends JViewLegacy
{
	function display($tpl = null) 
	{

        $document =& JFactory::getDocument();

        $document->setTitle(JText::_('COM_VIDEOTRANSLATION_MEETING_ROOM'));

        $this->is_time = $this->get('IfItsTime');


        if($this->is_time > 0) {

            $document->addScript('/media/jui/js/jquery.min.js');
            $document->addScript('/media/jui/js/jquery-noconflict.js');

            $document->addScript(JURI::base().'components/com_videotranslation/js/jquery-ui-1.10.3.custom.min.js');

            $document->addScript(JURI::base().'components/com_videotranslation/js/jquery.countdown.min.js');

            $document->addScript('http://cloudinterpreter.com:8888/socket.io/socket.io.js');
            $document->addScript(JURI::base().'components/com_videotranslation/js/helper.js');
            $document->addScript(JURI::base().'modules/mod_videotranslation/js/simplewebrtc.bundle.js');

            $document->addScript(JURI::base().'components/com_videotranslation/js/custom.js');

            $document->addStyleSheet(JURI::base().'components/com_videotranslation/css/style.css');
            $document->addStyleSheet(JURI::base().'components/com_videotranslation/css/general.css');
            $document->addStyleSheet(JURI::base().'components/com_videotranslation/css/jquery.countdown.css');
        }
        else {
            $document->addScript('/media/jui/js/jquery.min.js');
            $document->addScript('/media/jui/js/jquery-noconflict.js');
            $document->addScript(JURI::base().'components/com_videotranslation/js/jquery.countdown.min.js');
            $document->addScript(JURI::base().'components/com_videotranslation/js/session.js');

        }

        $document->addStyleSheet(JURI::base().'components/com_videotranslation/css/session.css');

        $ses_id = JRequest::getVar('ses_id');
        $order_id = JRequest::getInt('order_id');
        $usertype = JRequest::getInt('usertype');

        $this->room = $ses_id.$order_id;

        $this->userinfo = $this->get('Userinfo');

        $browser = $this->get("Browser");

        switch($browser['name']) {
            case 'Google Chrome':

            break;
            default:
                //JFactory::getApplication()->enqueueMessage(JText::_('COM_VIDEOTRANSLATION_BROWSER_DOESNT_SUPPORT'), 'error');
            break;
        }

        if ($this->is_time >0) {
            // Display the view
            parent::display($tpl);
        }
        else {
            $tpl = 'nottime';

            $order =& JTable::getInstance('order', 'VideoTranslationTable');
            $order->load($order_id);

            $event =& JTable::getInstance('event', 'VideoTranslationTable');
            $event->load($order->event_id);

            JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
            $partnersdetailsModel = JModelLegacy::getInstance('PartnersDetails', 'VideoTranslationModel', array('ignore_request' => true));

            //$difference = $this->differenceTime($cart['my_timezone']);

            $usertype = JRequest::getInt('usertype');

            switch($usertype) {
                case 1:
                    $timezone = $order->user_timezone;
                break;
                case 2:
                    $timezone = $order->envitee_timezone;
                break;
                case 3:
                    $translator = JFactory::getUser($order->translator_id);
                    $timezone = $translator->getParam('timezone');
                break;
            }

            $difference = $partnersdetailsModel->differenceTime($timezone);

            switch($this->is_time) {
                case -1:

                    JFactory::getApplication()->enqueueMessage(
                        JText::sprintf('COM_VIDEOTRANSLATION_THE_TIME_PASSED',
                            date('H:i F,d',$event->dtstart - $difference),
                            $timezone
                        )
                        , 'Notice');
                    break;
                case -2:
                    JFactory::getApplication()->enqueueMessage(
                        JText::sprintf('COM_VIDEOTRANSLATION_NOTTIME_TO_START',
                            date('H:i',$event->dtstart - $difference),
                            $timezone,
                            $_SERVER['REQUEST_URI']
                        )
                        , 'Notice');
                    break;
            }

            parent::display($tpl);
        }


	}

    function countPriceDependsOnSubject() {

        return json_encode($this->get('PriceDependsOnSubject'));

    }

    function getTimeBeforeSession() {
        return json_encode($this->get('TimeBeforeSession'));
    }

    function getRemainingTime () {
        return json_encode($this->get('remainingTime'));
    }


}
