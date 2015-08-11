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

class VideoTranslationViewPay extends JViewLegacy
{
	function display($tpl = null) 
	{

        $user = JFactory::getUser();
        $user_id = $user->id;

        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base().'components/com_videotranslation/css/style.css');
        $document->setTitle(JText::_('COM_VIDEOTRANSLATION_PAYMENT_METHODS'));

        $this->form = $this->get('Form');
        //$this->order_id = $this->get('OrderId');

        if(!$user_id) {
            $tpl = 'login';
        }
		parent::display($tpl);
	}


    function showForm($tpl = null) {


        $this->form = $this->get('PaykeeperForm');

        parent::display($tpl);
    }


    function showTemplate($tpl = null) {

        parent::display($tpl);
    }

}
