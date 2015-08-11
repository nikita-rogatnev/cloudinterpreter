<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_banners
 *
 * @package     Joomla.Site
 * @subpackage  mod_banners
 * @since       1.5
 */
class ModVideoTranslationHelper
{
	public static function &getList(&$params)
	{
        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
		$model = JModelLegacy::getInstance('Cart', 'VideoTranslationModel', array('ignore_request' => true));

        $items['orders'] = $model->getItems();
        $items['amount'] = $model->getAmount();

//        echo "<pre>";
//        print_r($items); die;

		return $items;
	}

    public static function &getLanguages() {
        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $model = JModelLegacy::getInstance('frontpage', 'VideoTranslationModel', array('ignore_request' => true));

        $items = $model->getLanguages();

        foreach($items as $k=>$v){
            $items[$k] = JText::_($v);
        }

        return $items;

    }

    public function getLanguageFromSession() {

        $session = JFactory::getSession();
        $cart = $session->get('cart');

        $lang = JFactory::getLanguage();
        $currentLanguage = $lang->getTag();

        $lang_id = ModVideoTranslationHelper::getLanguageIdByTag($currentLanguage);

        return $lang_id;
//        if(isset($cart['your_langugage']) && $cart['your_langugage']) {
//
//            return $cart['your_langugage'];
//        }
//        else {
//            return 0;
//        }

    }

    public function getLanguageIdByTag($currentLanguage = '') {

        if(!$currentLanguage) {
            $lang = JFactory::getLanguage();
            $currentLanguage = $lang->getTag();
        }


        JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_videotranslation/tables');
        $languageTable =& JTable::getInstance('language', 'VideoTranslationTable');

        $lang_id = $languageTable->getLanguageIdByTagName($currentLanguage);

        return $lang_id;


    }

    public function getLanguageTag() {
        $lang = JFactory::getLanguage();
        $currentLanguage = $lang->getTag();

        return substr($currentLanguage,0,2);
    }


}
