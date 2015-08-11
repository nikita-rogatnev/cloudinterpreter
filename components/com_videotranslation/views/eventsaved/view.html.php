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

class VideoTranslationViewEventSaved extends JViewLegacy
{
	function display($tpl = null) 
	{

        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base().'components/com_videotranslation/css/style.css');

        $document->addScript(JURI::base().'components/com_videotranslation/js/jquery.min.js');
        $document->addScript(JURI::base().'components/com_videotranslation/js/eventsaved.js');
        $document->setTitle(JText::_('COM_VIDEOTRANSLATION_THANK_YOU'));

        // Assign data to the view
//		$this->item = $this->get('item');
        //$this->form = $this->get('Form');

		// Display the view
		parent::display($tpl);
	}
}
