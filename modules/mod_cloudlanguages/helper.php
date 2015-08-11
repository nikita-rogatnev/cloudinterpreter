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
class ModBalanceHelper
{
	public static function &getBalance(&$params)
	{
        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
		$model = JModelLegacy::getInstance('cart', 'VideoTranslationModel', array('ignore_request' => true));

        $item = $model->getBalance();

		return $item;
	}

}
