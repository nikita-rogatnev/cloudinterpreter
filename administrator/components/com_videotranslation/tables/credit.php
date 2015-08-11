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

class VideoTranslationTableCredit extends JTable
{
	function __construct(&$db) 
	{
		parent::__construct('#__vt_credits', 'id', $db);
	}


    function getIdByUserId($clientid) {

        $db =& JFactory::getDBO();
        $sql = "SELECT id FROM #__vt_credits where user_id =".$clientid;

        $db->setQuery( $sql	);
        return $db->loadResult();
    }

    function getAmountByUserId($clientid) {

        $db =& JFactory::getDBO();
        $sql = "SELECT amount FROM #__vt_credits where user_id =".$clientid;

        $db->setQuery( $sql	);
        return $db->loadResult();
    }
}
