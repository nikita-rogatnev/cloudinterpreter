<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class VideoTranslationControllerOrders extends JControllerAdmin
{
	public function getModel($name = 'order', $prefix = 'VideoTranslationModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

    public function publish() {

        $mainframe =& JFactory::getApplication();

        $task = JRequest::getVar('task');

        $cid = JRequest::getVar('cid');

        JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_videotranslation/tables');

        $order =JTable::getInstance('order', 'VideoTranslationTable');

        $order->load($cid[0]);

        $event =& JTable::getInstance('vevent', 'VideoTranslationTable');
        $event->load($order->event_id);

        $event->state = ($task=='publish')?1:0;

        $event->store();

        $msg= "Order was ";

        $msg .= ($task=='publish')?'approved':'cancelled';

        $mainframe->Redirect('index.php?option=com_videotranslation&view=orders',$msg);
        return false;
    }


}
