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



<div id="videoLine">

    <div id="remotes"></div>

</div>

<div style="clear: both;"></div>

<div class="localVideo-container">
    <video id="localVideo" autoplay="autoplay"></video>
<!--    <div class="some-info-about-user" style="margin-top: 12px;"></div>-->
</div>


<div id="room-id" style="display: none;"><?php echo $this->room;?></div>
<!--<div id="userinfo" style="display: none;">--><?php //echo $this->userinfo->name." ".$this->userinfo->location['city']?><!--</div>-->

<div id="userinfo" style="display: none;"><?php echo $this->userinfo->name;?></div>


<div id="currentLanguageTag" style="display: none;"><?php echo JRequest::getVar('lang');?></div>
<div id="order_id" style="display: none;"><?php echo JRequest::getVar('order_id');?></div>



