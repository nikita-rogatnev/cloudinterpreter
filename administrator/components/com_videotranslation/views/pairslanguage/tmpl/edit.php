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
JHtml::_('formbehavior.chosen', 'select');


?>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {

        if (task == 'pairslanguage.cancel' || document.formvalidator.isValid(document.id('pairslanguage-form')))
        {
            Joomla.submitform(task, document.getElementById('pairslanguage-form'));
        }

        if (task == 'pairslanguage.previous' || task == 'pairslanguage.next') {
            Joomla.submitform(task, document.getElementById('pairslanguage-form-calendar'))
        }
    }
</script>


<div id="j-sidebar-container" class="span2">
<form action="<?php echo JRoute::_('index.php?option=com_videotranslation&view=pairslanguage&layout=edit&id='.(int) JRequest::getInt('id')); ?>"	method="post" name="adminFormCalendar" id="pairslanguage-form-calendar">
    <input type="hidden" name="task" value="<?php echo JRequest::getVar('task');?>" />
    <input type="hidden" name="option" value="<?php echo JRequest::getVar('option');?>" />
    <input type="hidden" name="layout" value="<?php echo JRequest::getVar('layout');?>" />
    <input type="hidden" name="id" value="<?php echo JRequest::getInt('id');?>" />

    <input type="hidden" name="previousMonday" value="<?php echo $this->previousMonday;?>" />
    <input type="hidden" name="nextMonday" value="<?php echo $this->nextMonday;?>" />

    <?php echo JHtml::_('form.token'); ?>

        <?php echo $this->sidebar; ?>

</form>
</div>


<div id="j-main-container" class="span10">
    <form action="<?php echo JRoute::_('index.php?option=com_videotranslation&layout=edit&id='.(int) $this->item->id); ?>" 	method="post" name="adminForm" id="pairslanguage-form" class="form-validate form-horizontal">

        <fieldset class="adminform">
            <legend>Details</legend>
            <?php foreach($this->form->getFieldset() as $field): ?>
                <?php echo $field->label;echo $field->input;?>
            <?php endforeach; ?>
        </fieldset>
        <div>
            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </div>

    </form>


    <?php echo $this->loadTemplate('calendar');?>
</div>



