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

class VideoTranslationViewUserspairs extends JViewLegacy
{
	function display($tpl = null) 
	{
		// Get data from the model
		$items = $this->get('Items');

        $model = $this->getModel ( 'userspairs' );

        $items = $model->getSubjects($items);

		$pagination = $this->get('Pagination');

		// Assign data to the view
		$this->items = $items;
		$this->pagination = $pagination;

        VideoTranslationHelper::addSubmenu('userspairs');
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
        JToolBarHelper::addNew('userspair.add');
        JToolBarHelper::editList('userspair.edit');
		JToolBarHelper::title('Interpreter for Language Pairs', 'userspairs');
		JToolBarHelper::deleteList('', 'userspairs.delete');


	}

	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle('VideoTranslation');
	}
}
