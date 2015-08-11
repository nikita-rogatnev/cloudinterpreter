<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class VideoTranslationControllerPairsLanguage extends JControllerForm
{

    function previous() {

        JRequest::setVar('monday', JRequest::getInt('previousMonday'));

        $this->DisplayView();
    }


    function next() {

        JRequest::setVar('monday', JRequest::getInt('nextMonday'));

        $this->DisplayView();
    }

    function DisplayView() {

        $submenu = JRequest::getVar('view');
        // Add submenu
        VideotranslationHelper::addSubmenu($submenu);

        parent::display();
    }

}
