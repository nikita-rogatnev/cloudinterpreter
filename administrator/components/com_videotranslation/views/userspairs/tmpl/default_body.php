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
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_videotranslation&task=userspair.edit&id=' . $item->id); ?>">
				<?php echo $item->name; ?>
			</a>

		</td>
        <td>
            <?php echo $item->pair_name; ?>
        </td>
        <td>
            <?php echo $item->rate;?>
        </td>

        <td>
            <?php echo $item->subjects;?>
        </td>

        <td>
            <?php echo $item->user_id;?>
        </td>

        <td>
            <?php echo $item->start_time_hours.":".$item->start_time_minutes."-".$item->end_time_hours.":".$item->end_time_minutes;?>
            <div style="font-size: 9px;">
                Lunch:<?php echo $item->lunch_time_start_hours.":".$item->lunch_time_start_minutes."-".$item->lunch_time_end_hours.":".$item->lunch_time_end_minutes;?>
            </div>
        </td>

        <td>
            <?php echo $item->countWorkingDays."/".$item->countDayOff;?>
        </td>
	</tr>
<?php endforeach; ?>
