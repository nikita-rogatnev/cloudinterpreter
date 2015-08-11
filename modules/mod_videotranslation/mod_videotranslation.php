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
	$module->content = JHtml::_('content.prepare', $module->content, '', 'mod_videotranslation.content');
}

JHtml::_('bootstrap.framework');

$document =& JFactory::getDocument();
$document->addStyleSheet(JURI::base().'modules/mod_videotranslation/css/bootstrap-modal.css');
$document->addStyleSheet(JURI::base().'modules/mod_videotranslation/css/jquery-ui-1.10.3.custom.css');

$document->addStyleSheet(JURI::base().'modules/mod_videotranslation/css/chosen.css');

$document->addStyleSheet(JURI::base().'modules/mod_videotranslation/css/jquery.mCustomScrollbar.css');
$document->addStyleSheet(JURI::base().'modules/mod_videotranslation/css/jumbotron-narrow.css');
$document->addStyleSheet(JURI::base().'modules/mod_videotranslation/css/custom.css');
//$document->addStyleSheet(JURI::base().'modules/mod_videotranslation/css/jquery.countdown.css');

//$document->addStyleSheet(JURI::base().'modules/mod_videotranslation/css/jquery.selectbox.css');
//$document->addStyleSheet(JURI::base().'modules/mod_videotranslation/css/tipsy.css');



//$document->addScript(JURI::base().'modules/mod_videotranslation/js/jquery.min.js');
//
//$document->addScript(JURI::base().'modules/mod_videotranslation/js/noconflict.js');

$document->addScript(JURI::base().'modules/mod_videotranslation/js/jquery.blockUI.js');

$document->addScript(JURI::base().'modules/mod_videotranslation/js/jquery.ajaxqueue.js');
$document->addScript(JURI::base().'modules/mod_videotranslation/js/jquery-ui-1.10.3.custom.min.js');

$document->addScript(JURI::base().'modules/mod_videotranslation/js/chosen.jquery.min.js');
$document->addScript(JURI::base().'modules/mod_videotranslation/js/jquery.tipsy.js');
$document->addScript(JURI::base().'modules/mod_videotranslation/js/jquery.countdown.min.js');
$document->addScript(JURI::base().'modules/mod_videotranslation/js/jquery.zclip.min.js');
$document->addScript(JURI::base().'modules/mod_videotranslation/js/jquery.mCustomScrollbar.concat.min.js');
//$document->addScript(JURI::base().'modules/mod_videotranslation/js/jquery.selectbox-0.2.min.js');

$lang = JRequest::getVar('lang');
switch($lang) {
    case 'ru':

        $document->addScript(JURI::base().'modules/mod_videotranslation/js/jquery.ui.datepicker-ru.js');
    break;
}

require_once(JPATH_SITE.'/components/com_videotranslation/lib/russian-date.php');

JText::script('MOD_VIDEOTRANSLATION_CLICK_ON_DATE_AND_SELECT_TIME');
JText::script('MOD_VIDEOTRANSLATION_YOURS_TIMEZONE');
JText::script('MOD_VIDEOTRANSLATION_YOUR_PARTNERS_TIMEZONE');
JText::script('MOD_VIDEOTRANSLATION_DONT_HAVE_ENOUGH_MONEY');

JText::script('MOD_VIDEOTRANSLATION_JANUARY');
JText::script('MOD_VIDEOTRANSLATION_FEBRUARY');
JText::script('MOD_VIDEOTRANSLATION_MARCH');
JText::script('MOD_VIDEOTRANSLATION_APRIL');
JText::script('MOD_VIDEOTRANSLATION_MAY');
JText::script('MOD_VIDEOTRANSLATION_JUNE');
JText::script('MOD_VIDEOTRANSLATION_JULY');
JText::script('MOD_VIDEOTRANSLATION_AUGUST');
JText::script('MOD_VIDEOTRANSLATION_SEPTEMBER');
JText::script('MOD_VIDEOTRANSLATION_OCTOBER');
JText::script('MOD_VIDEOTRANSLATION_NOVEMBER');
JText::script('MOD_VIDEOTRANSLATION_DECEMBER');

JText::script('MOD_VIDEOTRANSLATION_THIS_TIME_IS_NOT_AVAILABLE');

JText::script('MOD_VIDEOTRANSLATION_CANCEL');



$document->addScript(JURI::base().'modules/mod_videotranslation/js/jquery.timeline.js');
$document->addScript(JURI::base().'modules/mod_videotranslation/js/simplewebrtc.bundle.js');
$document->addScript(JURI::base().'modules/mod_videotranslation/js/socket.io.js');
$document->addScript(JURI::base().'modules/mod_videotranslation/js/bootstrap.min.js');
$document->addScript(JURI::base().'modules/mod_videotranslation/js/custom.js');
$document->addScript(JURI::base().'modules/mod_videotranslation/js/client.js');


$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require_once JPATH_SITE . '/modules/mod_videotranslation/helper.php';
//BannersHelper::updateReset();
$items = &ModVideotranslationHelper::getList($params);

$languages = &ModVideotranslationHelper::getLanguages();

$selected = &ModVideotranslationHelper::getLanguageFromSession();


$currentLanguage = &ModVideoTranslationHelper::getLanguageIdByTag();

$currentLanguageTag = &ModVideoTranslationHelper::getLanguageTag();

require JModuleHelper::getLayoutPath('mod_videotranslation', $params->get('layout', 'default'));
