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

class VideoTranslationViewUserDetails extends JViewLegacy
{
	function display($tpl = null) 
	{

        $document =& JFactory::getDocument();

//        $document->addStyleSheet(JURI::base().'modules/mod_videotranslation/css/custom.css');
        $document->addStyleSheet(JURI::base().'components/com_videotranslation/css/style.css');

        $document->addScript(JURI::base().'components/com_videotranslation/js/jquery.min.js');
        $document->addScript(JURI::base().'components/com_videotranslation/js/userdetails.js');

        $document->setTitle(JText::_('COM_VIDEOTRANSLATION_CONTACT_DATA'));

		// Assign data to the view
//		$this->item = $this->get('item');
        $this->form = $this->get('Form');

		// Display the view
		parent::display($tpl);
	}

    function countPriceDependsOnSubject() {

        $lang = JRequest::getVar('lang');
        switch($lang) {
            case 'ru':
                $tag = 'ru-RU';
                break;
            case 'en':
                $tag = 'en-US';
                break;
        }

        $language =& JFactory::getLanguage();
        $language->load('com_videotranslation' , JPATH_SITE, $tag, true);


        $items = $this->get('PriceDependsOnSubject');

        foreach($items->subjects as $k=>$v){
            $items->subjects[$k]->name = JText::_($v->name);
        }

        return json_encode($items);

    }

    function login() {

        $items = $this->get('login');

        return json_encode($items);

    }

    function charge() {

        $items = $this->get('charge');

        return json_encode($items);

    }

}
