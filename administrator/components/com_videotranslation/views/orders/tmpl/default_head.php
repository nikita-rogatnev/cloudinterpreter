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
<tr>
    <th width="20">
        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>

    <th width="52" class="center">
		Order id
	</th>

	<th>
		Name
	</th>

    <th>
        Email
    </th>

    <th>
        Invitee
    </th>



    <th>
        Order date
    </th>

    <th>
        Amount
    </th>
    <th class="center">
        Approved
    </th>

    <th class="center">
        Translator
    </th>

</tr>
