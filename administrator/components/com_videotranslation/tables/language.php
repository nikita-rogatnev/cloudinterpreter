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

class VideoTranslationTableLanguage extends JTable
{
	function __construct(&$db) 
	{
		parent::__construct('#__vt_languages', 'id', $db);
	}

    function getLanguageIdByTagName($tag) {

        $row = '';
        if($tag) {
            $query = "SELECT id FROM #__vt_languages WHERE system_language = '".$tag."'";
            $this->_db->setQuery($query);
            $row =  $this->_db->loadResult();
        }
        return $row;
    }


}
