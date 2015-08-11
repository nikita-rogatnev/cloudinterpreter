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
<style type="text/css">

@media screen and (max-width: 640px) {

    .rt-grid-3.rt-omega, .rt-grid-6  {
        display:none !important;
    }

    .rt-container{
        width: 100% !important;
    }

    .form-actions {
        padding-left: 15px !important;
        width: 90% !important;
    }
    .rt-block,  {
        width: 90% !important;
    }
    .control-group, .validate-username {
        width: 100% !important;
    }
    .zopim {
        display: none !important;
    }

/*
    body {
        background: green;
    }*/
}
}
</style>
<article class="item-page">
	<h2>
		<a href="/en/payment/payment-methods"><?php echo JText::_('COM_VIDEOTRANSLATION_PAYMENT_METHODS');?></a>
    </h2>

    <?php echo JText::_('COM_VIDEOTRANSLATION_PAYMENT_METHODS_TEXT');?>

    <form action="<?php echo JRoute::_('index.php?option=com_videotranslation'); ?>" method="post" name="adminForm" id="payment-form" class="form-validate form-horizontal">
            <?php foreach($this->form->getFieldset() as $field): ?>
                <?php echo $field->label;echo $field->input;?>
            <?php endforeach; ?>

        <br />
        <br />
        <input type="submit" class="readon userform-next" value="<?php echo JText::_('COM_VIDEOTRASLATION_PAY');?>" >

        <div>
            <input type="hidden" name="task" value="paymentForm" />
            <input type="hidden" name="order_id" value="<?php echo $this->order_id;?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>

</article>

<script type="text/javascript">
    jQuery('input[class="readon userform-next"]').on('click', function() {
        if ( jQuery('#jform_order_sum').val() > 0  ) {
            }
        else {
            alert('You must enter a valid payment sum!');
            return false;
        }
});
</script>







