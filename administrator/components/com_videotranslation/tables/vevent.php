<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

class VideoTranslationTableVevent extends JTable
{
	function __construct(&$db) 
	{
		parent::__construct('#__jevents_vevent', 'ev_id', $db);
	}
}
