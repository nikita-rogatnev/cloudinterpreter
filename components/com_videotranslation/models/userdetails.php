<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
require_once "details.php";

jimport('joomla.application.component.modelitem');

class VideoTranslationModelUserDetails extends VideoTranslationModelDetails
{

    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_videotranslation.userdetails', 'userdetails', array('control' => 'jform', 'load_data' => $loadData));
        return $form;
    }

    protected function loadFormData()
    {
        $user =& JFactory::getUser();

        $app  = JFactory::getApplication();
        $data = $app->getUserState('com_videotranslation.edit.userdetails.data', array());

        if (empty($data))
                {
                    $session = JFactory::getSession();
                    $cart = $session->get('cart');



                    if(isset($cart['userdetails']) && count($cart['userdetails'])) {

                        $cart['userdetails']['first_name'] = $user->name;
                        $cart['userdetails']['email'] = $user->email;

                        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
                        $cart_model = JModelLegacy::getInstance('Cart', 'VideoTranslationModel', array('ignore_request' => true));

                        $items = $cart_model->getItems();

                        $str = '';
                        for($i=0;$i<count($items);$i++) {
                            $str .= '  '.JText::_('COM_VIDEOTRANSLATION_FROM').' '.date("G:i",$items[$i]->start).' '.JText::_('COM_VIDEOTRANSLATION_TO'). ' ' .date("G:i",$items[$i]->end);
                        }


                        $cart['userdetails']['total_time'] = $cart['userdetails']['total_time'].$str;

                        return $cart['userdetails'];
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

        $cart['userdetails'] = $data['jform'];
        $session->set('cart', $cart);

        if ($cart['call_mode'] == '2') {
            return $this->saveCart($cart, $session, $mainframe);
        } else {
            $mainframe->Redirect(JRoute::_('index.php?option=com_videotranslation&view=partnersdetails', false));
            return false;
        }
        
    }

    public function getPriceDependsOnSubject() {

        $selectedSubject = JRequest::getInt('selectedSubject');

        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $model = JModelLegacy::getInstance('Cart', 'VideoTranslationModel', array('ignore_request' => true));

        $amount = $model->getAmount();

        $session = JFactory::getSession();
        $cart = $session->get('cart');

        $pair_id = $model->getPairId($cart['your_language'],$cart['your_partner_language']);

        $Items = new stdClass();

        $Items->subjects = $this->getSubjectsList($pair_id);
        $Items->amount = $amount . "  RUB";

        $Items->selectedSubject = $selectedSubject;

        $cart['userdetails']['amount'] = $amount;
        $cart['userdetails']['selectedSubject'] = $selectedSubject;
        $cart['userdetails']['pair_id'] = $pair_id;

        $session->set('cart', $cart);

        return $Items;
    }

    public function getSubjectsList($pair_id) {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query = "SELECT c.id, c.name FROM #__vt_translatorpair as a, #__vt_user_subject as b, #__vt_subjects as c where a.pair_id = ".$pair_id." AND a.user_id = b.user_id AND c.id=b.subj_id GROUP BY c.id ORDER BY c.default desc";

        $db->setQuery($query);
        $rows = $db->loadObjectList();

        return $rows;



//        $array = array();
//
////        echo "<pre>";
////        print_r($rows); die;
//
//        for($i=0;$i<count($rows);$i++) {
//            $query = "SELECT id,name FROM #__vt_subjects where id IN (SELECT subj_id from ".$rows[$i]->subj_ids.") ORDER BY `default` DESC";
//
//            $db->setQuery($query);
//            $array[] = $db->loadAssocList();
//
//            //$array[] = $rows;
//        }
//
//        $array1 = array();
//        for($i=0;$i<count($array);$i++) {
//            for($j=0;$j<count($array[$i]);$j++) {
//                $array1[$array[$i][$j]['id']] = $array[$i][$j]['name'];
//            }
//        }
//
//       return $array1;

    }


    public function getLogin() {

        $login = JRequest::getString('login');
        $password = JRequest::getString('password');

        $app = JFactory::getApplication();

        // Get the log in credentials.
        $credentials = array();
        $credentials['username'] = $login;
        $credentials['password'] = $password;

        // Get the log in options.
        $options = array();
        $options['remember'] = false;
        $options['return'] = '';


        $row = new stdClass();
        // Perform the log in.
        if (true === $app->login($credentials, $options))
        {

            $session =& JFactory::getSession();

            $user = JFactory::getUser();

            $payments =& JTable::getInstance('credit', 'VideoTranslationTable');

            $amount = $payments->getAmountByUserId($user->id);

            $row->user_id = $user->id;
            $row->name = $user->name;
            //$row->amount = $amount;
            $row->minutes = ($amount * 15)/590;
            $row->token = $session->getId();

//
//            echo "<pre>";
//            print_r($row); die;

        }
        else
        {
            $row->name = '';
            $row->amount = 0;
        }


        return $row;
    }


    public function getCharge() {

        $userid = JRequest::getString('userid');
        $time= JRequest::getString('time');

        $base = strtotime('00:00:00');
        $ourTime = strtotime($time);
        $difference = $ourTime-$base;
        $chargeAmount = 0.66*$difference;

        $payments =& JTable::getInstance('credit', 'VideoTranslationTable');

        $userAmount = $payments->getAmountByUserId($userid);

        $tableId = $payments->getIdByUserId($userid);

        //$row = new stdClass();

        $row =& JTable::getInstance('credit', 'VideoTranslationTable');
        $row->load( $tableId );

        $row->id = $tableId;
        $row->user_id = $userid;
        //$row->amount = $userAmount - $chargeAmount;

        $row->minutes = (($userAmount - $chargeAmount) * 15)/590;

        if (!$payments->bind( $row )) {
            return JError::raiseWarning( 500, $row->getError() );
        }

        if (!$payments->store()) {
            JError::raiseError(500, $row->getError() );
        }

        return $row;
    }

}
