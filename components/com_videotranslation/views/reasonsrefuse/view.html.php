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

class VideoTranslationViewReasonsRefuse extends JViewLegacy
{
	function display($tpl = null) 
	{

        $document = JFactory::getDocument();

        $document->addScript(JURI::base().'components/com_videotranslation/js/jquery.min.js');
        $document->addScript(JURI::base().'components/com_videotranslation/js/reasonsrefuse.js');

        $document->setTitle(JText::_('COM_VIDEOTRANSLATION_SPECIF_REASONS_OF_REFUSE'));

	//Assign data to the view
      $this->item = $this->get('item');

      $this->form = $this->get('Form');

		// Display the view
		parent::display($tpl);
	}

    function orderRefuse() {
        $model = $this->getModel ( 'reasonsrefuse' );
        $this->msg = $model->getRefuse();
        //parent::display('thankyou');
    }

}
