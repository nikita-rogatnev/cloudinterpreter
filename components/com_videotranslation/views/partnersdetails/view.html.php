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

class VideoTranslationViewPartnersDetails extends JViewLegacy
{
	function display($tpl = null) 
	{

        $document =& JFactory::getDocument();

        $document->addStyleSheet(JURI::base().'components/com_videotranslation/css/style.css');

        $document->addScript(JURI::base().'components/com_videotranslation/js/jquery.min.js');
        $document->addScript(JURI::base().'components/com_videotranslation/js/parnersdetails.js');

        $document->setTitle(JText::_('COM_VIDEOTRANSLATION_PARTNER_CONTACT_DATA'));

		// Assign data to the view
//		$this->item = $this->get('item');
        $this->form = $this->get('Form');

        $this->textArea = $this->get('InvitationTextArea');

//        echo "<pre>";
//        print_r($this->textArea); die;


		// Display the view
		parent::display($tpl);
	}
}
