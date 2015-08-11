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

class VideoTranslationViewCart extends JViewLegacy
{
    function display($tpl = null)
    {

        $this->get('session');

        // Assign data to the view
        $this->items['orders'] = $this->get('items');

        $this->items['amount'] = $this->get('amount');

//        echo "<pre>";
//        print_r($this->items); die;

        //echo json_encode($this->items);

        $lang = JRequest::getVar('lang');

        require_once(JPATH_SITE.'/components/com_videotranslation/lib/russian-date.php');
        switch($lang) {
            case 'ru':
                $tag = 'ru-RU';
                break;
            case 'en':
                $tag = 'en-US';
                break;
        }


        $language =& JFactory::getLanguage();
        $language->load('mod_videotranslation' , JPATH_SITE, $tag, true);

        $this->lang = $lang;

        header('Content-Type: text/html; charset=UTF-8');



        // Display the view
        parent::display($tpl);
    }

    function checkIfSomethingInSession() {

        $user =& JFactory::getUser();

        $amount = $this->get('amount');
        $balance = $this->get('balance');

        $item = new stdClass();
        $item->amount = $amount;
        $item->balance = $balance;
        $item->count_items =  count($this->get('items'));
        $item->user_id = $user->id;

        return json_encode($item);

    }

}
