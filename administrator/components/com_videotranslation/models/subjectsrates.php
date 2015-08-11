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

class VideoTranslationModelSubjectsRates extends JModelList
{
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.id, concat(d.name,"-", e.name) as pair_name, c.name as subject_name, a.rate');

		// From the hello table
		$query->from('#__vt_subjectsrate as a, #__vt_subjects as c, #__vt_pairslanguages as b');

        $query->leftJoin('#__vt_languages as d on b.langId1 = d.id');
        $query->leftJoin('#__vt_languages as e on b.langId2 = e.id');

        $query->where('a.pair_id = b.id');
        $query->where('c.id = a.subj_id');

        $query->order('pair_name,subject_name');
		return $query;
	}
}
