<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.database.table');

class VideoTranslationTableUsersPair extends JTable
{
	function __construct(&$db) 
	{
		parent::__construct('#__vt_translatorpair', 'id', $db);
	}

    function getInterpreters($pair_id,$filter_interpreter = 0) {

        // check for existing name
        $query = 'SELECT b.id, b.name FROM #__vt_translatorpair as a, #__users as b WHERE a.user_id = b.id AND a.pair_id = '.$pair_id;
        if($filter_interpreter) {
            $query .= " AND b.id = ".$filter_interpreter;
        }
        $this->_db->setQuery($query);

        $rows =  $this->_db->loadObjectList();

        return $rows;
    }

}
