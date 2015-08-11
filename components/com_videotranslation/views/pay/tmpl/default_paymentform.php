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


echo $this->form;

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





