<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class VideoTranslationController extends JControllerLegacy
{
	function display($cachable = false) 
	{
		// Set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view','languages'));
		
		parent::display($cachable);

 //       $submenu = JRequest::getVar('view');

//        if($submenu!='pairslanguages') {
//            // Add submenu
//            VideotranslationHelper::addSubmenu($submenu);
//        }
	}
}
