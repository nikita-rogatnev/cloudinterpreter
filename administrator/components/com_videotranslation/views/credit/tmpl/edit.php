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
        if (task == 'credit.cancel' || document.formvalidator.isValid(document.id('credit-form')))
        {
            Joomla.submitform(task, document.getElementById('credit-form'));
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_videotranslation&layout=edit&id='.(int) $this->item->id); ?>" 	method="post" name="adminForm" id="credit-form" class="form-validate form-horizontal">
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
