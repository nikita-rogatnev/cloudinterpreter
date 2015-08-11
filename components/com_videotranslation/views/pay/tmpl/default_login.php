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
        <a href="/en/payment/payment-methods"><?php echo JText::_('COM_VIDEOTRANSLATION_PLEASE_REGISTER');?></a>
    </h2>

    <?php //echo JText::_('COM_VIDEOTRANSLATION_PAYMENT_METHODS_TEXT');?>
<br />

<div style="width: 250px;">
<?php
jimport('joomla.application.module.helper');
// this is where you want to load your module position
$modules = JModuleHelper::getModules('login_module');
foreach($modules as $module)
{
echo JModuleHelper::renderModule($module);
}
?>
</div>

</article>





