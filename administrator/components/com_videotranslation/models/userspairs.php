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

class VideoTranslationModelUsersPairs extends JModelList
{
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*, b.name');

        $query->select('concat(d.name,"-", e.name) as pair_name');

		$query->from('#__users as b, #__vt_translatorpair as a, #__vt_pairslanguages as c');

        $query->leftJoin('#__vt_languages as d on c.langId1 = d.id');
        $query->leftJoin('#__vt_languages as e on c.langId2 = e.id');

        $query->where('a.user_id = b.id');
        $query->where('c.id = a.pair_id');

        $query->order('a.id');

		return $query;
	}

    public function getSubjects($items) {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

//echo "<pre>";
//        print_r($items); die;

        for($i=0;$i<count($items);$i++) {

            $query = "SELECT GROUP_CONCAT(`name`) from #__vt_subjects WHERE id IN(SELECT subj_id FROM #__vt_user_subject WHERE user_id = ".$items[$i]->user_id.")";

            $db->setQuery($query);
            $items[$i]->subjects = $db->loadResult();
        }

        return $items;
    }
}
