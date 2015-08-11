<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('bootstrap.framework');

if ($params->def('prepare_content', 1))
{
	JPluginHelper::importPlugin('content');
	$module->content = JHtml::_('content.prepare', $module->content, '', 'mod_balance.content');
}

$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::base().'modules/mod_videotranslation/css/jquery-ui-1.10.3.custom.css');
$document->addStyleSheet(JURI::base().'modules/mod_cloudlogin/css/custom.css');
$document->addScript(JURI::base().'modules/mod_videotranslation/js/jquery-ui-1.10.3.custom.min.js');
$document->addScript(JURI::base().'modules/mod_videotranslation/js/jquery.timeline.js');
$document->addScript(JURI::base().'modules/mod_cloudlogin/js/custom.js');

$renderer = $document->loadRenderer('modules');
$options = array('style'=>'raw');
$usermenu =  $renderer->render('user-menu-header',$options,null);

$options = array('style'=>'raw');
$content_language_module =  $renderer->render('cloudlanguages',$options,null);

$lang = JFactory::getLanguage();
$curLang = $lang->getTag();

//echo "<pre>";
//print_r($content_language_module); die;

require_once JPATH_SITE . '/modules/mod_cloudlogin/helper.php';
//BannersHelper::updateReset();
$item = &ModCloudloginHelper::getBalance($params);

$user =& JFactory::getUser();

if (!$user->guest) {
    $username =  $user->name;
}

require JModuleHelper::getLayoutPath('mod_cloudlogin', $params->get('layout', 'default'));
