<?php

/**
 * @package     Joomla.Tutorials
 * @subpackage  Component
 * @copyright   Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license     License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modelitem');

class VideoTranslationModelCart extends JModelForm {
    protected $item;

    public function getItems() {

        $session = JFactory::getSession();

        $cart = $session->get('cart');

        if(isset($cart['items']) && count($cart['items'])) {

            natsort($cart['items']);
            return $this->mergeTime($cart);
        }
        else {
            return array();
        }
    }
    public function getBalance() {

        $user =& JFactory::getUser();

        if ($user->guest) {
            return 0;
        } else {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query = "SELECT amount FROM #__vt_credits where user_id =".$user->id;

            $db->setQuery($query);
            $row = $db->loadResult();

            if(!$row) $row = 0;
            return $row;
        }

    }

    public function getAmount() {

        $session = JFactory::getSession();
        $cart = $session->get('cart');

        $selectedSubject = JRequest::getInt('selectedSubject');

        if(count($cart)) {

            $pair_id = 0;
            if(isset($cart['your_language']) && $cart['your_language']  && isset($cart['your_partner_language']) && $cart['your_partner_language']) {
                $pair_id = $this->getPairId($cart['your_language'],$cart['your_partner_language']);
            }

            if(!$selectedSubject) {
                $selectedSubject = $this->getDefaultSubjectId();
            }

            $subj_rate = $this->getSubjectrate($pair_id,$selectedSubject);

            if(isset($cart['items']) && count($cart['items']) >0 && $subj_rate) {
                $cart['userdetails']['amount'] = count($cart['items'])*$subj_rate;
                $cart['userdetails']['total_time'] = (count($cart['items'])*15)." ".JText::_('COM_VIDEOTRANSLATION_MIN');
                $cart['userdetails']['pair_id'] = $pair_id;
            }
            else {
                $cart['userdetails']['amount'] = 0;
                $cart['userdetails']['total_time'] = 0;
            }

            $session->set('cart', $cart);

            return $cart['userdetails']['amount'];
        }
        else {
            return 0;
        }


    }


    public function getSubjectrate ($paid_id,$subj_id) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $row = array();
        if($paid_id && $subj_id) {
            $query = "SELECT a.rate FROM #__vt_subjectsrate as a where a.pair_id = ".$paid_id." AND a.subj_id =".$subj_id;

            $db->setQuery($query);
            $row = $db->loadResult();
        }

        return $row;

    }

    public function getDefaultSubjectId() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query = "SELECT a.id FROM #__vt_subjects as a where a.default = 1";

        $db->setQuery($query);
        $row = $db->loadResult();

        return $row;
    }


    public function getPairId($langId1, $langId2) {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query = "SELECT a.id FROM #__vt_pairslanguages as a where (a.langId1 = ".$langId1." AND a.langId2 = ".$langId2.") OR  (a.langId1 = ".$langId2." AND a.langId2 = ".$langId1.")";

        $db->setQuery($query);
        $row = $db->loadResult();

        return $row;
    }


    public function mergeTime($array) {

        if(count($array)) {

            $temp_array = array();
            $temp_array1 = array();

            $i=0;

            foreach($array['items'] as $k=>$v) {
                $temp_array[$i] = new stdClass();
                $temp_array[$i]->start = $k;
                $temp_array[$i]->end = $k+900;
                $i++;
            }

            for($i=0;$i<count($temp_array);$i++) {
                if(!isset($temp_array[$i]->included)) {
                    $temp = $temp_array[$i];

                    for($j=0;$j<count($temp_array);$j++) {
                        if( $temp_array[$j]->start == $temp->end) {
                            $temp->end = $temp_array[$j]->end;
                            $temp_array[$j]->included = 1;
                            $temp->time[] = $temp_array[$j]->start;
                        }
                    }
                    $temp_array1[] = $temp;
                }
            }
//
//
//            echo "<pre>";
//            print_r($temp_array1); die;

            return $temp_array1;

        }
    }

    public function getForm($data = array(), $loadData = true)
    {
//        // Get the form.
//        $form = $this->loadForm('com_videotranslation.timezones', 'timezones', array('control' => 'jform', 'load_data' => $loadData));
//        return $form;
    }


    public function getSession() {

        $timeId = JRequest::getVar('timeId');

        $add = JRequest::getVar('add');

        $my_partner_timezone = JRequest::getVar('my_partner_timezone');
        $my_timezone = JRequest::getVar('my_timezone');

        $your_language = JRequest::getInt('your_language');
        $your_partner_language = JRequest::getInt('your_partner_language');
        $callMode = JRequest::getVar('callMode');

        $sendStat = JRequest::getVar('status');
        $isIOS = JRequest::getVar('isIOS');
        $socket_id = JRequest::getVar('sessionid');

        $session = JFactory::getSession();
        $cart = $session->get('cart');

        if($my_partner_timezone) {
            $cart['my_partner_timezone'] = $my_partner_timezone;
        }
        if($my_timezone) {
            $cart['my_timezone'] = $my_timezone;
        }

        if($timeId) {
            $cart['items'][$timeId] = $timeId;
        }

        if($your_language) {
            $cart['your_language'] = $your_language;
        }

        if($your_partner_language) {
            $cart['your_partner_language'] = $your_partner_language;
        }

        if(!$add && $timeId) {
            unset($cart['items'][$timeId]);
        }
        if($callMode) {
            $cart['call_mode'] = $callMode;
        }

              if ($sendStat) {
                $now = new DateTime();
                $date = time();    
                $user = JFactory::getUser();
                $statData = new stdClass();

                if ($sendStat == 'start') {
                    $statData->caller     = $user->id;
                    $statData->connected  = 1;
                    $statData->time_start = $date;
                    $statData->socket_id = $socket_id;
                    $result = JFactory::getDbo()->insertObject('#__vt_statistic', $statData);
                }

                if ($sendStat == 'busy') {
                    $statData->caller     = $user->id;
                    $statData->connected  = 0;
                    $statData->time_end = $date;
                    $statData->socket_id = $socket_id;
                    $result = JFactory::getDbo()->updateObject('#__vt_statistic', $statData, 'socket_id');
                }

                if ($sendStat == 'interpreter_answer') {
                    $statData->interpreter_id     = $user->id;
                    $statData->connected  = 1;
                    $statData->socket_id = $socket_id;
                    $statData->isIOS = $isIOS;
                    //$result = JFactory::getDbo()->updateObject('#__vt_statistic', $statData, 'socket_id');
                    $result = JFactory::getDbo()->insertObject('#__vt_statistic', $statData);

                }

                if ($sendStat == 'end') {
                    //$statData->caller     = $username;
                    $statData->connected  = 100;
                    $statData->time_end = $date;
                    $statData->socket_id = $socket_id;
                    $result = JFactory::getDbo()->updateObject('#__vt_statistic', $statData, 'socket_id');
                }


        }

        $session->set('cart', $cart);


//        echo "<pre>";
//        print_r($cart); die;
        //return json_encode($session->get('cart'));
    }



}
