<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class VideoTranslationViewCallcenter extends JViewLegacy
{

    function display($tpl = null) 
    {    
        $document =& JFactory::getDocument();
        $document->setTitle('Call-Center');



        $document->addScript('http://code.jquery.com/jquery-1.9.1.js');
        $document->addScript('http://code.jquery.com/ui/1.10.4/jquery-ui.js');
        $document->addScript('http://78.140.138.8:1999/socket.io/socket.io.js');

        $document->addScript(JURI::base().'components/com_videotranslation/js/call-center.js');

        $document->addStyleSheet('http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css');
        $document->addStyleSheet(JURI::base()."components/com_videotranslation/css/style.css");

        parent::display($tpl);
    }




}
