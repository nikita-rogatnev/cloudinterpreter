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

class VideoTranslationModelOrders extends JModelList
{
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*,b.name,b.email,c.state,d.name as translator_name');

		// From the hello table
		$query->from('#__users as b, #__jevents_vevent as c,#__vt_orders as a');
        $query->leftJoin('#__users as d ON d.id = a.translator_id');
        $query->where('c.ev_id = a.event_id');
		$query->where('a.user_id = b.id');
        $query->order('id desc');
   		return $query;
	}
}
