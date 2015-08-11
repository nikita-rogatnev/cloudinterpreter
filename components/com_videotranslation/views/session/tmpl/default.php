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
video {	
	max-width: 240px !important;
	max-width: 400px !important;
}
#remotes {
	height: 320px !important;
    margin-left: 10px !important;
    margin-bottom: 20px !important;
}
</style>

<div id="videoLine">
    <div id="remotes" style="width:300px;"></div>

</div>

<div style="clear: both;"></div>

<div class="localVideo-container">
    <video id="localVideo" autoplay="autoplay"></video>
	<div class="OT_video-poster"></div>
	<div id="vidCtrls" style="opacity: 1;">
    <img src="<?php echo JURI::base(); ?>modules/mod_videotranslation/img/vid0.png"  id="pubVid" style="width: 20px;" alt="Toggle video" title="Toggle video" class="active">
    <img src="<?php echo JURI::base(); ?>modules/mod_videotranslation/img/voice0.png" id="pubAud" style="width: 10px;margin-left: 12px;" alt="Toggle audio" title="Toggle audio" class="active">
</div>
<!--    <div class="some-info-about-user" style="margin-top: 12px;"></div>-->
</div>



<div id="room-id" style="display: none;"><?php echo $this->room;?></div>
<div id="userinfo" style="display: none;"><?php echo $this->userinfo->name.", ".$this->userinfo->location['city']?></div>

<!--<div id="userinfo" style="display: none;">--><?php //echo $this->userinfo->name;?><!--</div>-->


<div id="currentLanguageTag" style="display: none;"><?php echo JRequest::getVar('lang');?></div>
<div id="order_id" style="display: none;"><?php echo JRequest::getVar('order_id');?></div>
<div id="noMediaInfo" style="display: none;"><?php echo JText::_('COM_VIDEOTRANSLATION_NO_MEDIA');?></div>
<div id="invalidBrowserInfo" style="display: none;"><?php echo JText::_('COM_VIDEOTRANSLATION_INVALID_BROWSER');?></div>


<script type="text/javascript">
    var base_url = '<?php echo JURI::base(); ?>';
</script>

