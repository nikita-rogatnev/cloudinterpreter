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

$session = JFactory::getSession();
$cart = $session->get('cart');
$call_mode = $cart['call_mode'];

echo $this->loadTemplate('topmenu');

?>
<form action="<?php echo JRoute::_('index.php'); ?>"
      method="post" name="adminForm" id="userdetails-form" class="form-validate">

    <?php

        $i=0;

    foreach($this->form->getFieldset() as $field): ?>

        <?php if($i==3) {?>
            <div style="float: left;">
        <?php }?>

        <div>
            <?php echo $field->label;echo $field->input;?>
        </div>

        <?php if($i==8) {?>
        </div><div style="float: left; margin-left: 100px;">
        <?php }

         if($i==17) {?>
            </div>
        <?php }

        $i++;?>

    <?php endforeach; ?>
    <div style="clear: both;"></div>
    <br /><br />
    <div style="margin-left: 315px;">
        <input type="button" class="readon readon-back userform-back" value="<?php echo JText::_('COM_VIDEOTRANSLATION_BACK');?>" onclick="javascript:document.location.href=document.getElementById('live_site').innerHTML; return false;">
        <input type="submit" class="readon userform-next" value=" <?php
    if ($call_mode == '2' ) {
        echo JText::_('COM_VIDEOTRANSLATION_DONE');
    } else {
        echo JText::_('COM_VIDEOTRANSLATION_NEXT');
    }
?>" >
    </div>

    <div>
        <input type="hidden" name="option" value="com_videotranslation" />
        <input type="hidden" name="task" value="userdetails.save" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

