<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$languageMenu = '<li class="item134 parent">
                    <a href="javascript:void(0);" class="item">'.substr($curLang,0,2).'</a>
                    <div class="dropdown languageslist">
                        '.$content_language_module.'
                    </div>
                </li>
';
?>

<div class="login-link">
    <?php
    $user =& JFactory::getUser();
        if (!$user->guest) {

//            echo "<div class='username-logged-header'>".$user->username."";

            ?>
            <ul class="gf-menu l1">

                <li class="item133 timer-container" style="display: none;">
                    <a class="item">
                        <div id="defaultCountdown"></div>
                    </a>
                </li>

                <li class="item135 parent">
                    <a href="javascript:void(0);" class="item" style="line-height: 20px; padding: 3px 15px;">
                        <?php echo $username;?> <br />
                        <?php echo $item;?> RUB
                    </a>
                    <?php
                    echo "<div class='dropdown userprofile'><div class='column'>".$usermenu."</div></div>";
                    ?>
                </li>
                <?php echo $languageMenu;?>
              </ul>
            <?php
        }
        else {

            ?>
            <ul class="gf-menu l1 ">

                <li class="item133 timer-container" style="display: none;">
                    <a class="item">
                        <div id="defaultCountdown"></div>
                    </a>
                </li>

                <li class="item135">
                    <a href="javascript:void(0);" class="item login-link-header" style="text-decoration: none;" >
                        <?php
                            echo JText::_('MOD_CLOUDLOGIN_LOGIN');
                        ?>
                    </a>
                </li>
                <?php echo $languageMenu;?>
            </ul>
            <?php
        }
    ?>
</div>

<div id="dialog-modal-registration" title="<?php echo JText::_("MOD_VIDEOTRANSLATION_LOGIN_IN_SYSTEM");?>" style="display: none;">
    <?php
    jimport('joomla.application.module.helper');
    $mods = JModuleHelper::getModules('login_module');
    echo JModuleHelper::renderModule($mods[0]);
    ?>
</div>

<div id="dialog-dont-have-enough-money" style="display: none;">
    <?php echo JText::_('MOD_VIDEOTRANSLATION_DONT_HAVE_ENOUGH_MONEY');?>
</div>

<div id="dialog-select-date" style="display: none;">
    <?php echo JText::_('MOD_VIDEOTRANSLATION_CLICK_ON_DATE_AND_SELECT_TIME');?>
</div>


<div id="live_site" style="display: none;"><?php echo JURI::base();?></div>