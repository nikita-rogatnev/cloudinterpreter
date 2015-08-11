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


//require_once JPATH_SITE . '/modules/mod_balance/helper.php';
//BannersHelper::updateReset();
//$item = &ModBalanceHelper::getBalance($params);

$document = &JFactory::getDocument();

$document->addStyleSheet(JURI::base().'modules/mod_cloudlanguages/css/custom.css');
$document->addScript(JURI::base().'modules/mod_cloudlanguages/js/custom.js');

$renderer = $document->loadRenderer('modules');
$options = array('style'=>'raw');
$content_language_module =  $renderer->render('cloudlanguages',$options,null);

require JModuleHelper::getLayoutPath('mod_cloudlanguages', $params->get('layout', 'default'));
