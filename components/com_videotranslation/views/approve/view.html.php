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

class VideoTranslationViewApprove extends JViewLegacy
{
	function display($tpl = null) 
	{

        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base().'components/com_videotranslation/css/style.css');
        $document->setTitle(JText::_('COM_VIDEOTRANSLATION_APPROVE_INVITATION_PAGE'));

	//Assign data to the view
        $this->item = $this->get('item');
        $this->form = $this->get('Form');

        $rows = $this->get('wasApproved');

        if($rows['order']->approved == 1) {
            $tpl = 'wasapproved';

            JFactory::getApplication()->enqueueMessage(
                JText::sprintf('COM_VIDEOTRANSLATION_YOU_ALREADY_ACCEPTED_INVITATION',
                    $rows['event']->location.'&order_id='.$rows['order']->id.'&usertype=2&Itemid=155'
                )
                , 'Notice');

        }
        elseif($rows['order']->approved == -1) {
            $tpl = 'wasapproved';

            JFactory::getApplication()->enqueueMessage(
                JText::sprintf('COM_VIDEOTRANSLATION_YOU_ALREADY_DECLINED_INVITATION',
                    'index.php'
                )
                , 'Notice');
        }


		// Display the view
		parent::display($tpl);
	}

    function orderApprove() {
        $model = $this->getModel ( 'approve' );
        $this->msg = $model->getApprove();
        //parent::display('thankyou');
    }

}
