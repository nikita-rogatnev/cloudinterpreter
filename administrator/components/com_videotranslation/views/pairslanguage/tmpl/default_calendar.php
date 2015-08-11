<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;
?>


<table border="0" cellspasing="0" cellpadding="0">

    <tr>
        <td>
            <table cellspasing="0" cellpadding="0" >
                <tr>
                    <td width="1300" height="116" style="border: 1px solid #050607;text-transform: uppercase;" valign="top">

                        <div style="font-size: 38px; padding: 26px 0 0 7px;"><?php echo $this->calendar->language1;?> - <?php echo $this->calendar->language2;?></div>
                        <div style="font-size: 29px; padding: 33px 0 0 7px;"><?php echo $this->calendar->monday;?> - <?php echo $this->calendar->sunday;?></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table border="0" cellspasing="0" cellpadding="0">
                <tr>
                    <td></td>
                    <?php for($i=0;$i<count($this->calendar->weekdays);$i++) {?>
                        <td style="border: 1px solid #050607; border-top: 0px; height: 32px; font-size: 16px;" align="center">
                            <?php echo $this->calendar->weekdays[$i];?>
                        </td>
                    <?php }?>
                </tr>
                <tr>
                    <td></td>
                    <?php for($j=0;$j<count($this->calendar->weekdays);$j++) {?>
                        <td style="width: 90px; border-bottom: 1px solid #050607;border-left:1px solid #050607; <?php if($j==count($this->calendar->weekdays)-1)  {echo "border-right: 1px solid #050607;";} else {echo "border-right: 0px;";}?>">
                            <table border="0" cellspasing="0" cellpadding="0" width="100%">
                                <tr>
                                     <?php for($i=0;$i<count($this->calendar->interpreters);$i++) {?>
                                        <td style="<?php if($i>0) {echo "border-left: 1px solid #050607;";}?>  width: 44px; height: 187px; font-size: 17px;">
                                            <div class="rotate">
                                            <?php echo substr($this->calendar->interpreters[$i]->name,0,22);?>
                                            </div>
                                        </td>
                                    <?php }?>
                                 </tr>

                              </table>
                        </td>
                    <?php }?>
                </tr>

                <?php for($k=0;$k<count($this->calendar->items);$k++) {?>
                    <tr>
                        <td style=" height: 15px;background: #d6d5d5; width: 73px;  padding-left: 5px;  border-bottom: 1px solid #050607; font-size: 13px; border-right: 1px dotted #050607">
                            <?php echo $this->calendar->items[$k]->time;?>
                        </td>
                        <?php for($i=0;$i<count($this->calendar->weekdays);$i++) {?>
                            <td>
                                <table border="0" cellspasing="0" cellpadding="0" width="100%">
                                    <tr style="border-right: 1px solid #050607;">
                                        <?php for($j=0;$j<count($this->calendar->interpreters);$j++) {?>
                                            <td style="width: 44px; height: 18px; border-right: 1px dotted #050607; border-bottom: 1px solid #050607; background:

                                            <?php
                                                    if(
                                                        $this->calendar->rows[$this->calendar->weekdays[$i]][$k]->free_for_select

                                                        && $this->calendar->rows[$this->calendar->weekdays[$i]][$k]->interpreter_id == $this->calendar->interpreters[$j]->id
                                                    )
                                                    {
                                                        echo "#31ec50";
                                                    }
                                                    elseif(
                                                        $this->calendar->rows[$this->calendar->weekdays[$i]][$k]->time_is_waiting

                                                        && $this->calendar->rows[$this->calendar->weekdays[$i]][$k]->interpreter_id == $this->calendar->interpreters[$j]->id
                                                    )
                                                    {
                                                        echo "#ffe430";
                                                    }
                                                    elseif(
                                                        $this->calendar->rows[$this->calendar->weekdays[$i]][$k]->time_is_busy

                                                        && $this->calendar->rows[$this->calendar->weekdays[$i]][$k]->interpreter_id == $this->calendar->interpreters[$j]->id
                                                    )
                                                    {
                                                        echo "#f36366";
                                                    }
                                                    else {
                                                        echo "#c4d4ff";
                                                    }
                                             ?>

                                                ;">
                                                <?php //echo $this->calendar->rows[$this->calendar->weekdays[$i]][$k]->free_for_select;?>
                                            </td>
                                        <?php }?>
                                    </tr>
                                </table>
                            </td>
                        <?php }?>
                    </tr>
                <?php }?>



            </table>
        </td>
    </tr>


</table>

<form action="<?php echo JRoute::_('index.php?option=com_videotranslation&view=pairslanguage&layout=edit&id='.(int) JRequest::getInt('id')); ?>"	method="post" name="adminFormCalendar" id="pairslanguage-form-calendar">
    <input type="hidden" name="task" value="<?php echo JRequest::getVar('task');?>" />
    <input type="hidden" name="option" value="<?php echo JRequest::getVar('option');?>" />
    <input type="hidden" name="layout" value="<?php echo JRequest::getVar('layout');?>" />

    <input type="hidden" name="filter.interpreter_id" value="<?php echo $this->filter_interpreter_id;?>" />

    <input type="hidden" name="previousMonday" value="<?php echo $this->previousMonday;?>" />
    <input type="hidden" name="nextMonday" value="<?php echo $this->nextMonday;?>" />

    <?php echo JHtml::_('form.token'); ?>
 </form>