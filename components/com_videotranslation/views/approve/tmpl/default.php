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

require_once(JPATH_SITE.'/components/com_videotranslation/lib/russian-date.php');

?>
<h1>
<?php echo JText::_('COM_VIDEOTRANSLATION_INVITATION');?>
</h1>


<div>
<?php echo JText::sprintf(

    'COM_VIDEOTRANSLATION_SOMEBODY_INVITES_YOU_ON_MEETING',
    $this->item->first_name.' '.$this->item->last_name,
    maxsite_the_russian_time($this->item->date),
    $this->item->start,
    $this->item->end,
    $this->item->envitee_timezone

);?>
</div>

<br />

<form action="<?php echo JRoute::_('index.php'); ?>"
      method="post" name="adminForm" id="userdetails-form" class="form-validate">


    <?php foreach($this->form->getFieldset() as $field): ?>
        <div>
            <?php //echo $field->label;echo $field->input;?>
        </div>
    <?php endforeach; ?>
    <div>
        <input type="button" class="readon userform-next" value="<?php echo JText::_('COM_VIDEOTRASLATION_I_AGREE');?>" onclick="document.adminForm.approve.value=1; document.adminForm.submit();">
        <input type="button" class="readon readon-back userform-back" value="<?php echo JText::_('COM_VIDEOTRASLATION_I_DONT_AGREE');?>" onclick="document.adminForm.approve.value=0; document.adminForm.submit();">

    </div>

    <br /><br />
<!--    <input type="submit" class="btn btn-large btn-success userform-next" value="--><?php //echo JText::_('COM_VIDEOTRANSLATION_NEXT');?><!--" >-->

    <div>
        <input type="hidden" name="approve" value="" />
        <input type="hidden" name="option" value="com_videotranslation" />
        <input type="hidden" name="task" value="orderapprove" />
        <input type="hidden" name="order_id" value="<?php echo $this->item->id;?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

