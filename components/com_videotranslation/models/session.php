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

class VideoTranslationModelSession extends JModelForm
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

    public function getIfItsTime() {
        $order_id = JRequest::getInt('order_id');
        $usertype = JRequest::getInt('usertype');


        $order =& JTable::getInstance('order', 'VideoTranslationTable');
        $order->load($order_id);

        $event =& JTable::getInstance('event', 'VideoTranslationTable');
        $event->load($order->event_id);

        switch($usertype) {
            case 1:
                $timezone = $order->user_timezone;
            break;
            case 2:
                $timezone = $order->envitee_timezone;
            break;
            case 3:
                $translator = & JFactory::getUser($order->translator_id);
                $timezone = $translator->getParam('timezone');
            break;
        }

        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $frontpage = JModelLegacy::getInstance('frontpage', 'VideoTranslationModel', array('ignore_request' => true));

        $translator = & JFactory::getUser($order->translator_id);
        $translator_timezone = $translator->getParam('timezone');

        $translator_timezone_multiplier = $frontpage->getTimeZone($translator_timezone);
        $timezoneMultiplier = $frontpage->getTimeZone($timezone);

        $difference = ($timezoneMultiplier - $translator_timezone_multiplier)*60*60;

        $nowtime = time()+$timezoneMultiplier*60*60 - $difference;

        if($nowtime >= $event->dtstart && $nowtime <= $event->dtend) {
            return 1;
        }
        elseif($nowtime >= $event->dtstart) {
            return -1;
        }
        else if($nowtime <= $event->dtend) {
            return -2;
        }
        else {
            return false;
        }
    }


    public function getBrowser() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes separately and for good reason.
        if (preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif (preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif (preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif (preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif (preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif (preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // Finally get the correct version number.
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // See how many we have.
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // Check if we have a number.
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }

    public function getUserinfo() {

        $ses_id = JRequest::getVar('ses_id');
        $order_id = JRequest::getInt('order_id');
        $usertype = JRequest::getInt('usertype');



        $order =& JTable::getInstance('order', 'VideoTranslationTable');
        $order->load($order_id);

        $item = new stdClass();


        if($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
            $_SERVER['REMOTE_ADDR'] = '178.124.105.98';
        }


        //$item->location = geoip_record_by_name($_SERVER['REMOTE_ADDR']);

        if(!isset($item->location)) {
            $item->location = 'Moscow';
        }

        switch($usertype) {
            case 1:
                $item->name = $order->first_name.' '.$order->last_name;
            break;
            case 2:
                $item->name = $order->envitee_name;
            break;
            case 3:
                $user =& JFactory::getUser($order->translator_id);
                $item->name = $user->name.' '.JText::_('COM_VIDEOTRANSLATION_INTERPRETER');
            break;
        }

        return $item;
    }

    function getRemainingTime () {
        $order_id = JRequest::getInt('order_id');

        $order =& JTable::getInstance('order', 'VideoTranslationTable');
        $order->load($order_id);

        $event =& JTable::getInstance('event', 'VideoTranslationTable');
        $event->load($order->event_id);

        $item = new stdClass();
        $item->startTime = $event->dtstart;
        $item->endTime = $event->dtend;

        $item->nowTime = (time() + 3*60*60);

        return $item;

    }

    function getTimeBeforeSession () {
        $order_id = JRequest::getInt('order_id');

        $order =& JTable::getInstance('order', 'VideoTranslationTable');
        $order->load($order_id);

        $event =& JTable::getInstance('event', 'VideoTranslationTable');
        $event->load($order->event_id);

        $item = new stdClass();
        $item->startTime = $event->dtstart;
        $item->endTime = $event->dtend;

        $item->nowTime = (time() + 3*60*60);

        return $item;

    }


}
