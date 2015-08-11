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

class VideoTranslationModelCredits extends JModelList
{
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.id,b.name,a.amount');

		// From the hello table
		$query->from('#__vt_credits as a, #__users as b');
		$query->where('a.user_id = b.id');
        $query->order('id');
		return $query;
	}

    public function getUpdateCredits() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $db = JFactory::getDBO();
        $query = "SELECT * FROM #__users where id NOT IN(SELECT user_id from #__vt_credits)";

        $db->setQuery($query);
        $rows = $db->loadobjectList();

//        echo "<pre>";
//        print_r($rows); die;

        for($i=0;$i<count($rows);$i++) {
            $query = $db->getQuery(true);

            $query = "insert into #__vt_credits (`id`, `user_id` ,`amount`) values (NULL , ".$rows[$i]->id.", 10000);";
            $db->setQuery($query);
            $db->query();
        }




    }
}
