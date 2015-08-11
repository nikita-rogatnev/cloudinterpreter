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
<style>
    @media screen and (min-width: 1000px) {
        video {
            height: 214px;
        }
    }
</style>


<div id="container">

    <video autoplay></video>

</div>

<div style="display: none;" id="order_id"><?php echo JRequest::getInt('order_id');?></div>
<div style="display: none;" id="currentLanguageTag"><?php echo JRequest::getVar('lang');?></div>

<script language="JavaScript">
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

    var constraints = {audio: false, video: true};
    var video = document.querySelector("video");

    function successCallback(stream) {
        window.stream = stream; // stream available to console
        if (window.URL) {
            video.src = window.URL.createObjectURL(stream);
        } else {
            video.src = stream;
        }
        video.play();
    }

    function errorCallback(error){
        console.log("navigator.getUserMedia error: ", error);
    }

    navigator.getUserMedia(constraints, successCallback, errorCallback);
</script>