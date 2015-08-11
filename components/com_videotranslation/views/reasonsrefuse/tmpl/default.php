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

?>

<form action="<?php echo JRoute::_('index.php'); ?>"
      method="post" name="adminForm" id="userdetails-form" class="form-validate">


    <?php foreach($this->form->getFieldset() as $field): ?>
        <div>
            <?php echo $field->label;echo $field->input;?>
        </div>
    <?php endforeach; ?>

    <br /><br />
    <input type="submit" class="btn btn-large btn-success userform-next" value="<?php echo JText::_('COM_VIDEOTRANSLATION_NEXT');?>" >
    <input type="button" class="btn btn-large btn-success userform-back" value="<?php echo JText::_('COM_VIDEOTRANSLATION_BACK');?>" onclick="window.history.back();">


    <div>
        <input type="hidden" name="option" value="com_videotranslation" />
        <input type="hidden" name="task" value="orderrefuse" />
        <input type="hidden" name="order_id" value="<?php echo $this->item->id;?>" />
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />

        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

