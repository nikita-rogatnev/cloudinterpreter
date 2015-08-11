<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');


if(count($this->items['orders'])) {?>

    <div>
        <?php echo JText::_('MOD_VIDEOTRANSLATION_YOU_HAVE_SELECTED');?>
    </div>

    <?php for($i=0;$i<count($this->items['orders']);$i++) {?>
        <div style="width: 200px;">
            <div style="float: left; margin-right: 20px;"><?php echo maxsite_the_russian_time(date('j F H:i',$this->items['orders'][$i]->start),$this->lang);?> - <?php echo date('H:i',$this->items['orders'][$i]->end);?></div>
            <div style="float: left; color: #B1231F; cursor: pointer;" class="removeTimeFromSession" time="<?php echo $this->items['orders'][$i]->start; if(isset($this->items['orders'][$i]->time)) echo ',',implode(',',$this->items['orders'][$i]->time);?>">x</div>
        </div>
        <div style="clear: both;"></div>
    <?php }?>

        <div class="cart-amount">
            <?php echo JText::_('MOD_VIDEOTRANSLATION_YOUR_AMOUNT');
                echo $this->items['amount'];
            ?> RUB</div>

<?php }?>

<?php if(!count($this->items['orders'])) {
    echo JText::_('MOD_VIDEOTRANSLATION_CART_IS_EMPTY');
}?>