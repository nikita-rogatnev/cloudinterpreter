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

class VideoTranslationModelFrontpage extends JModelForm
{
	protected $item;


//	public function getCountries()
//	{
//        $db = JFactory::getDBO();
//        $query = $db->getQuery(true);
//
//        $this->items= new stdClass();
//
//        $query = "SELECT * FROM #__vt_countries ORDER BY time, name";
//
//        $db->setQuery($query);
//        $this->items = $db->loadobjectList();
//
//        $short_name = $this->getMyLocation();
//
//        for($i=0;$i<count($this->items);$i++) {
//            if($this->items[$i]->short_name == $short_name) {
//                $this->items[$i]->selected = 1;
//            }
//        }
//
//        return $this->items;
//	}

    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_videotranslation.timezones', 'timezones', array('control' => 'jform', 'load_data' => $loadData));
        return $form;
    }

//    protected function loadFormData()
//    {
//        // Check the session for previously entered form data.
//        $data = JFactory::getApplication()->getUserState('com_videotranslation.edit.timezones.data', array());
//                if (empty($data))
//                {
//                    //$data = $this->getItem();
//                    $data->set('my-timezone',$this->getMyTimezone());
//                }
//                return $data;
//    }

    public function getMyTimezone() {
        //return @geoip_time_zone_by_country_and_region  ( geoip_country_code_by_name('85.12.204.141'));

        $session = JFactory::getSession();
        $cart = $session->get('cart');

        $item = new stdClass();
        if(isset($cart['my_timezone']) && $cart['my_timezone']) {
            $item->my_timezone = $cart['my_timezone'];
        }
        else {

            if($_SERVER['HTTP_HOST'] == 'cloudinterpreter.tst') {
                $item->my_timezone = 'Europe/Moscow';
            }
            else {
                $item->my_timezone = @geoip_time_zone_by_country_and_region  ( geoip_country_code_by_name($_SERVER['REMOTE_ADDR']));
            }
        }

        if(isset($cart['my_partner_timezone']) && $cart['my_partner_timezone']) {
            $item->my_partner_timezone = $cart['my_partner_timezone'];
        }
        else {

            if($_SERVER['HTTP_HOST'] == 'cloudinterpreter.tst') {
                $item->my_partner_timezone = 'Europe/Moscow';
            }
            else {
                $item->my_partner_timezone =  @geoip_time_zone_by_country_and_region  ( geoip_country_code_by_name($_SERVER['REMOTE_ADDR']));
            }
        }

        if(!$item->my_timezone) {
            $item->my_timezone = 'Europe/Moscow';
            $item->my_partner_timezone = 'Europe/Moscow';
        }

        return $item;
    }

    public function addVariableInSession($name, $value) {
        $session = JFactory::getSession();
        $cart = $session->get('cart');

        $cart[$name] = $value;

        $session->set('cart', $cart);

    }



    public function getTranslatorTime() {

        $my_timezone = JRequest::getVar('my_timezone');
        $my_partner_timezone = JRequest::getVar('my_partner_timezone');
        $translator_timezone = $this->findTranslator();

        $timezones = new stdClass();

        $timezones->my_timezone = $this->getTimeZone($my_timezone);
        $timezones->my_partner_timezone = $this->getTimeZone($my_partner_timezone);
        $timezones->translator_timezone = $this->getTimeZone($translator_timezone);

        $this->addVariableInSession('my_timezone', $my_timezone);
        $this->addVariableInSession('my_partner_timezone', $my_partner_timezone);

        return $timezones;
    }

    public function findTranslator() {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query = "SELECT a.id FROM #__users as a, #__user_usergroup_map as b, #__usergroups as c WHERE a.id=b.user_id AND c.id=b.group_id AND c.id = 10";

        $db->setQuery($query);
        $this->item = $db->loadResult();

        $user = JFactory::getUser($this->item);

        return $user->getParam('timezone');
    }

    public function findTranslatorEmail() {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query = "SELECT a.id FROM #__users as a, #__user_usergroup_map as b, #__usergroups as c WHERE a.id=b.user_id AND c.id=b.group_id AND c.id = 10";

        $db->setQuery($query);
        $this->item = $db->loadResult();

        $user = JFactory::getUser($this->item);

        return $user->email;
    }

    public static function getTimeZone($userTz) {

        $date =& JFactory::getDate();
        $date->setTimezone(new DateTimeZone($userTz));

        return $date->getOffsetFromGMT()/3600;
    }

    public  function getUnixTime() {
        $time = JRequest::getVar('time');
        return strtotime($time);
    }

    public function mergeWithAllIntervals($busyRows, $min, $max,$difference) {

        $differenceMSEC = $difference*60*60;

        $rows = array();
        $j=0;
        for($i=$min;$i<$max;$i=$i+900) {

            $rows[$j] = new stdClass();

            $rows[$j]->ev_id = '';
            $rows[$j]->created_by = '';

            for($k=0;$k<count($busyRows);$k++) {
                if($i>=($busyRows[$k]->dtstart+$differenceMSEC) && $i<=($busyRows[$k]->dtend+$differenceMSEC)) {
                    $rows[$j]->ev_id = $busyRows[$k]->ev_id;
                    $rows[$j]->created_by = $busyRows[$k]->created_by;

                    if(!$busyRows[$k]->state) {
                        $rows[$j]->time_is_waiting = 1;
                    }
                }
            }

            $rows[$j]->dtstart = $i;
            $rows[$j]->dtend = $i+900;

            $j++;
        }

        return $rows;

    }

    public function ifTodayDayOffForTranslator($users_for_pair,$today) {

        $dayoff = $this->calculateIfDayOff($today,$users_for_pair->startday,$users_for_pair->countWorkingDays,$users_for_pair->countDayOff);

        return $dayoff;
    }

    public function calculateIfDayOff($today, $startday, $countWD,$countDO) {

        $workingDay = 0;

        $todayUNIX = strtotime($today);
        $startdayUNIX = strtotime($startday);

        if($todayUNIX >= $startdayUNIX) {

            $currentWdNumber = 1;
            $currentDoNumber = $countWD * -1;

            for($i=$startdayUNIX;$i<=$todayUNIX;$i=$i+24*60*60) {
                    $workingDay = $currentWdNumber;

                $currentWdNumber++;
                $currentDoNumber++;

                if($currentWdNumber == 0) $currentWdNumber = 1;
                if($currentDoNumber == 0) $currentDoNumber = 1;

                if($currentWdNumber > $countWD) {
                    $currentWdNumber = $countDO * -1;
                }

                if($currentDoNumber > $countDO) {
                    $currentDoNumber = $countWD * -1;
                }
            }
        }

        return $workingDay;
    }


    public function getItemsForThisDay($min,$max,$pair_id,$difference,$filter_interpreter = '') {

        $today = date('Y-m-d',$min);

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        //all events in database for this day
        $query = "SELECT b.ev_id,b.created_by,a.dtstart,a.dtend,b.state FROM #__jevents_vevdetail as a, #__jevents_vevent as b WHERE "
            ." a.dtstart >= ".$min." AND a.dtend <= ".$max." AND b.ev_id = a.evdet_id AND b.state >= 0";

        $db->setQuery($query);
        $rows = $db->loadObjectList();


        //merge all events with all others intervals
        $rows = $this->mergeWithAllIntervals($rows, $min, $max,$difference);


       // if($rows[$i]->dtstart == 1379408400) {
//            echo "<pre>";
//            print_r($rows); die;

        //}



        $users_for_pair= $this->getTranslatorsByPair($pair_id,$filter_interpreter);

        for($i=0;$i<count($rows);$i++) {

            for($j=0;$j<count($users_for_pair);$j++) {

                if($users_for_pair[$j]->user_id != $rows[$i]->created_by) {
                    $query = "SELECT a.evdet_id, a.dtstart, a.dtend,b.created_by from #__jevents_vevdetail as a, #__jevents_vevent as b WHERE b.ev_id = a.evdet_id AND b.created_by = ".$users_for_pair[$j]->user_id

                        ." AND ((a.dtstart < ".$rows[$i]->dtstart." AND a.dtend > ".$rows[$i]->dtstart.") OR (a.dtstart = ".$rows[$i]->dtstart." AND a.dtend = ".$rows[$i]->dtend.") OR (a.dtstart < ".$rows[$i]->dtend." AND a.dtend > ".$rows[$i]->dtend."))"
                    ;

                    $db->setQuery($query);
                    $times = $db->loadObjectList();

                    $workingDay = $this->ifTodayDayOffForTranslator($users_for_pair[$j],$today);

                    //if night shift started today and finished tomorrow
                    if( $users_for_pair[$j]->end_time_hours < $users_for_pair[$j]->start_time_hours ) {
                        $tomorrow = date('Y-m-d',$min+24*60*60);
                        $yesterday = date('Y-m-d',$min-24*60*60);

                        if($workingDay != 1) {
                            $working_time_start = strtotime($yesterday.' '.($users_for_pair[$j]->start_time_hours).':'.$users_for_pair[$j]->start_time_minutes)+$difference*60*60;
                            $working_time_end = strtotime($today.' '.($users_for_pair[$j]->end_time_hours).':'.$users_for_pair[$j]->end_time_minutes)+$difference*60*60;
                        }
                        else {
                            $working_time_start = strtotime($today.' '.($users_for_pair[$j]->start_time_hours).':'.$users_for_pair[$j]->start_time_minutes)+$difference*60*60;
                            $working_time_end = strtotime($tomorrow.' '.($users_for_pair[$j]->end_time_hours).':'.$users_for_pair[$j]->end_time_minutes)+$difference*60*60;
                        }

                        if($workingDay > 0) {
                            $working_time_start2 = strtotime($today.' '.($users_for_pair[$j]->start_time_hours).':'.$users_for_pair[$j]->start_time_minutes)+$difference*60*60;
                            $working_time_end2 = strtotime($tomorrow.' '.($users_for_pair[$j]->end_time_hours).':'.$users_for_pair[$j]->end_time_minutes)+$difference*60*60;
                        }
                        else {
                            if($workingDay == ($users_for_pair[$j]->countDayOff * -1) && $rows[$i]->dtend <= $working_time_end) {
                                $workingDay = $users_for_pair[$j]->countWorkingDays;
                            }
                        }

                    }
                    else {
                        $working_time_start = $working_time_start2 = strtotime($today.' '.($users_for_pair[$j]->start_time_hours).':'.$users_for_pair[$j]->start_time_minutes)+$difference*60*60;
                        $working_time_end = $working_time_end2 = strtotime($today.' '.($users_for_pair[$j]->end_time_hours).':'.$users_for_pair[$j]->end_time_minutes)+$difference*60*60;

                    }

                    $lunch_time_start = strtotime($today.' '.($users_for_pair[$j]->lunch_time_start_hours).':'.$users_for_pair[$j]->lunch_time_start_minutes)+$difference*60*60;
                    $lunch_time_end = strtotime($today.' '.($users_for_pair[$j]->lunch_time_end_hours).':'.$users_for_pair[$j]->lunch_time_end_minutes)+$difference*60*60;



                    if(
                        !count($times)
                        &&

                        (
                            ($rows[$i]->dtstart >= $working_time_start && $rows[$i]->dtend <= $working_time_end) ||
                            ($rows[$i]->dtstart >= $working_time_start2 && $rows[$i]->dtend <= $working_time_end2)         //working hours
                        )

                        &&

                        (
                           ($rows[$i]->dtstart < $lunch_time_start && $rows[$i]->dtend < $lunch_time_end) ||
                           ($rows[$i]->dtstart > $lunch_time_start && $rows[$i]->dtend > $lunch_time_end)    //lunch hour
                        )

                        && $workingDay > 0

                    )
                    {
                        $rows[$i]->free_for_select =1;
                        $rows[$i]->interpreter_id = $users_for_pair[$j]->user_id;
                    }
                    else {
//
 //                      if($rows[$i]->created_by) {
//                            echo "<pre>";
//                            print_r($rows[$i]); die;
//                            $rows[$i]->time_is_busy =1;
//                            $rows[$i]->interpreter_id = $users_for_pair[$j]->user_id;
 //                       }
                     }
                }


                else {
                    $rows[$i]->time_is_busy =1;
                    $rows[$i]->interpreter_id = $users_for_pair[$j]->user_id;
                }
            }
        }

        return $rows;
    }

    public function getBusyTime() {

        $my_timezone = JRequest::getVar('my_timezone');

        $timezones = $this->getTranslatorTime();

        $min = JRequest::getVar('min');

        $max = JRequest::getVar('max');

        $langId1 = JRequest::getInt('langId1');
        $langId2 = JRequest::getInt('langId2');

        $difference = JRequest::getInt('difference');

        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $model = JModelLegacy::getInstance('Cart', 'VideoTranslationModel', array('ignore_request' => true));

        $pair_id = $model->getPairId($langId1, $langId2);

        //list items
        $rows = $this->getItemsForThisDay($min,$max,$pair_id,$difference);

       $difference = ($timezones->my_timezone - $timezones->translator_timezone)*60*60;

       for($i=0;$i<count($rows);$i++) {
        // $rows[$i]->dtstart = $rows[$i]->dtstart + $difference;
        // $rows[$i]->dtend = $rows[$i]->dtend + $difference;
       }

       date_default_timezone_set('UTC');

       $time = time() + $timezones->my_timezone*60*60;

//        echo 'Time now: '.date('Y F d H:i',$time);
//        echo 'Start time: '.date('Y F d H:i',$min);
//        echo 'End time: '.date('Y F d H:i',$max);
        //echo $timezones->my_timezone; die;

       for($i=($min);$i<($max);$i=$i+900) {
            if($i < $time) {
                $temp = new stdClass();
                $temp->dtstart = $i;
                $temp->dtend = $i+900;
                $rows[] = $temp;
            }
       }


        return $rows;
//        echo "<pre>";
//        print_r($rows); die;

    }

    public function getTranslatorsByPair($pair,$filter_interpreter) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query = "SELECT * FROM #__vt_translatorpair as a where a.pair_id = ".$pair;

        if($filter_interpreter) {
            $query .=" AND a.user_id = ".$filter_interpreter;
        }

        $db->setQuery($query);
        $rows = $db->loadObjectList();

        return $rows;
    }

    public function getLanguages() {

        $selectedLanguage = JRequest::getVar('selectedLanguage');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);


        $query = "SELECT a.*,b.* FROM #__vt_translatorpair as a, #__vt_pairslanguages as b where a.pair_id = b.id";


        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $array  = array();
        for($i=0;$i<count($rows);$i++) {
            $query = "SELECT id, name FROM #__vt_languages where id IN(".$rows[$i]->langId1.",".$rows[$i]->langId2.")";

            $db->setQuery($query);
            $array[] = $db->loadObjectList();

        }

        $array1 = array();
        for($i=0;$i<count($array);$i++) {

            for($j=0;$j<count($array[$i]);$j++) {

                if($selectedLanguage && ($array[$i][$j]->id != $selectedLanguage)) {
                    $array1[$array[$i][$j]->id] = $array[$i][$j]->name;
                }
                elseif(!$selectedLanguage) {
                    $array1[$array[$i][$j]->id] = $array[$i][$j]->name;
                }
            }
        }

        return $array1;
    }

    public function getCallMode() {
        $cart = $session->get('cart');
        return $cart['call_mode'];
    }


}
