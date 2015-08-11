<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if ($params->def('prepare_content', 1))
{
	JPluginHelper::importPlugin('content');
	$module->content = JHtml::_('content.prepare', $module->content, '', 'mod_balance.content');
}


require_once JPATH_SITE . '/modules/mod_balance/helper.php';
//BannersHelper::updateReset();
$item = &ModBalanceHelper::getBalance($params);

$user =& JFactory::getUser();

if (!$user->guest) {
   $username =  $user->name;
}


require JModuleHelper::getLayoutPath('mod_balance', $params->get('layout', 'default'));
