<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class VideoTranslationModelTranslatorsPairs extends JModelList
{
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*, b.name as language1,c.name as language2');

		// From the hello table
		$query->from('#__vt_pairslanguages as a');
		$query->leftJoin('#__vt_languages as b on a.langId1 = b.id');
        $query->leftJoin('#__vt_languages as c on a.langId2 = c.id');

        $query->order('a.id');
		return $query;
	}
}
