<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class VideoTranslationModelCredit extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_videotranslation.credit', 'credit', array('control' => 'jform', 'load_data' => $loadData));
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_videotranslation.edit.credit.data', array());
		if(empty($data)){
			$data = $this->getItem();
		}
		return $data;
	}

	public function getTable($name = '', $prefix = 'VideoTranslationTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}
}
