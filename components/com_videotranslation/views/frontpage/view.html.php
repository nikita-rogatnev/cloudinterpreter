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

class VideoTranslationViewFrontpage extends JViewLegacy
{

    function getTimezones($tpl = null) {
        $mytimezone = $this->get('MyTimezone');
        // get the Data
        $this->form = $this->get('Form');

        // Display the view
        parent::display($tpl);
    }

    function getMyTimezone() {

        return json_encode($this->get('MyTimezone'));
    }

    function getTranslatorTime() {
        $this->items = $this->get('TranslatorTime');
        echo json_encode($this->items);
    }

    function getPartnerSessionLanguage() {

        $session = JFactory::getSession();
        $cart = $session->get('cart');

        $SelectedLanguage = JRequest::getInt('SelectedLanguage');

//        $langRequest= JRequest::getVar('lang');
//
//        $lang =& JFactory::getLanguage();
//        $lang->setLanguage( $langRequest );
//        $lang->load();

        $item = new stdClass();
        $item->selectedLanguageId = $SelectedLanguage;

        $languageTable =& JTable::getInstance('language', 'VideoTranslationTable');
        $languageTable->load($SelectedLanguage);
        $item->selected_language = substr($languageTable->system_language,0,2);

        if(isset($cart['your_partner_language']) && $cart['your_partner_language']) {
           $item->your_partner_language = $cart['your_partner_language'];
        }
        return json_encode($item);

        //else {
        //    return 0;
       // }

    }

    function getUnixTime() {

        return $this->get('UnixTime');
    }

    function getBusyTime() {

        echo json_encode($this->get('BusyTime'));
    }

    function getLanguages() {

        $lang = JRequest::getVar('lang');

        if($lang) {
            switch($lang) {
                case 'ru':
                    $tag = 'ru-RU';
                break;
                case 'en':
                    $tag = 'en-US';
                break;
                default:
                    $tag = 'ru-RU';
                default;
            }

            $language =& JFactory::getLanguage();
            $language->load('mod_videotranslation' , JPATH_SITE, $tag, true);

        }

        $items = $this->get('Languages');

        foreach($items as $k=>$v){
            $items[$k] = JText::_($v);
        }

        return json_encode($items);
    }
    
    function getCallMode() {
        $session = JFactory::getSession();
        $cart = $session->get('cart');
        return $cart['call_mode'];
    }

}
