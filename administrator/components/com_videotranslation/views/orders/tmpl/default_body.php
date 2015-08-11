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
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>

		<td class="center">

            <a href="<?php echo JRoute::_('index.php?option=com_videotranslation&task=order.edit&id=' . $item->id); ?>">
			<?php echo $item->id; ?>
            </a>

		</td>

		<td>
			<?php  echo $item->first_name.' '.$item->last_name.' ('.$item->name.')'; ?>
		</td>

        <td>
            <?php echo $item->email; ?>
        </td>

        <td>
            <?php  echo $item->envitee_name.'  ('.$item->envitee_email.')'; ?>
        </td>



        <td>
            <?php echo $item->time; ?>
        </td>

        <td>
                <?php echo $item->amount; ?> RUB
        </td>
        <td class="center">
            <?php echo JHtml::_('jgrid.published', $item->state, $i, 'orders.', 1, 'cb'); ?>
        </td>

        <td class="center">
            <?php echo $item->translator_name; ?>
        </td>

    </tr>
<?php endforeach; ?>
