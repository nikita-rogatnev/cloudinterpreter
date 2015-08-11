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
//JHtml::_('behavior.formvalidation');
?>
<div id="dialog" title="">
    Incoming call
</div>
<div class="row">
<div id='call_block'>
<h2>Panel</h2>

<table>
     <tr>
        <td>server status:</td>
        <td>
            <div id="ifOnline"></div>
        </td>
    </tr>
    <tr>
        <td>socket id:</td>
        <td>
            <div id="socketId" style="font-size: 12px;"></div>
        </td>
    </tr>
    <tr>
        <td>interpreter status:</td>
        <td>
        <div class="styled-select24">
            <select id="ifUserOnline" name="ifUserOnline">
                <option value="1">online</option>
                <option value="2">busy</option>
                <option value="3">away</option>
				<option value="4">answering</option>
            </select>
        </div>
        </td>
    </tr>

    <tr>
        <td>interpreter language:</td>
        <td>
        <div class="styled-select24">
            <select id="interpreterLanguage" name="interpreterLanguage">
                <option value="0">--select language--</option>
                <option value="online english">english</option>
                <option value="online german">german</option>
            </select>
        </div>
        </td>
    </tr>

    <tr>
		<td>calling users:</td>
		<td>
			<div id="waitingUsers" style="font-size: 12px;"></div>
		</td>
    </tr>
    <tr>
        <td>visitors:</td>
        <td>
            <div id="visitors" style="font-size: 12px;"></div>
        </td>
    </tr>

<style type="text/css">
    #rt-mainbody-surround {
    background: #252D35 !important;
    color: #C7C7C7;
}
</style>
<script type="text/javascript">
    var base_url = '<?php echo JURI::base(); ?>';
</script>
</table>


</div>
<div></div>
<div id='history_block_big'>
   <h2>Call history</h2>
    <div id='history_block'>
      <img src="<?echo JURI::base();?>/components/com_videotranslation/img/ajax-loader.gif">
    </div>
</div>

</div>
<style type="text/css">
    #rt-mainbody-surround {
        background: #252D35 !important;
        color: #C7C7C7;
    }

    #call_block {
        width: 40%;
    }
    #history_block_big {
        width: 40%;
    }
    #history_block_big, #call_block {
        float:left;
    }
    #history_block_big>h2, #call_block>h2 {
        color: #BFC3CA;
        border-bottom: none;
    }
    .device {
        width: 10%;
        -webkit-filter: invert(100%);
        padding-bottom: 1%;
    }
    .call_to {
        -webkit-transform:rotate(180deg);
        width: 5%;
        -webkit-filter: invert(100%);
    }
    table.history td {
        padding: 3px;
    }
    .call_yes {
        color: rgb(0, 138, 0);
    }
    .call_no {
        color: rgb(255, 116, 116);
    }
    .margin_left_td {
        padding-left: 20px !important;
    }
</style>