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

class VideoTranslationViewPairsLanguage extends JViewLegacy
{
	public function display($tpl = null) 
	{

        $this->state = $this->get('State');

		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');
        $calendar = $this->get('Calendar');

        $this->filter_interpreter_id = $this->state->get('filter.interpreter_id');
		// Assign the Data
		$this->form = $form;
		$this->item = $item;
        $this->calendar = $calendar;

        $this->previousMonday = $this->get('PreviousMonday');
        $this->nextMonday = $this->get('NextMonday');

		// Set the toolbar
		$this->addToolBar();

        $this->sidebar = JHtmlSidebar::render();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	protected function addToolBar() 
	{
		JRequest::setVar('hidemainmenu', true);
		$isNew = ($this->item->id == 0);
		JToolBarHelper::title($isNew ? 'Add new Language Pair'
			: 'Edit Language Pair', 'pairslanguages');
		JToolBarHelper::save('pairslanguage.save');
		JToolBarHelper::cancel('pairslanguage.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');

        JToolBarHelper::custom( 'pairslanguage.previous', 'camera.png', 'camera.png', 'Previous Week', false );
        JToolBarHelper::custom( 'pairslanguage.next', 'camera.png', 'camera.png', 'Next Week', false );

        JHtmlSidebar::setAction('index.php?option=com_videotranslation&view=pairslanguage&layout=edit&id='.JRequest::getInt('id'));

        JHtmlSidebar::addFilter(
            'Select Interpreter',
            'filter_interpreter_id',
            JHtml::_('select.options', VideoTranslationHelper::getInterpreters(), 'value', 'text', $this->state->get('filter.interpreter_id'), true)
        );

    }

	protected function setDocument() 
	{
		$isNew = ($this->item->id < 1);
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? 'Add new Language Pair' : 'Edit Language Pair');
		$document->addStyleSheet(JURI::root() . "administrator/components/com_videotranslation/css/style.css");
        $document->addScript(JURI::root() . "administrator/components/com_videotranslation/js/script.js");
	}
}
