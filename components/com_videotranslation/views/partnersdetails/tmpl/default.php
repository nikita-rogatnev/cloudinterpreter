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


$this->addTemplatePath( JPATH_COMPONENT.'/views/userdetails/tmpl' );
echo $this->loadTemplate('topmenu');

?>
<form action="<?php echo JRoute::_('index.php'); ?>"
      method="post" name="adminForm" id="userdetails-form" class="form-validate">

    <?php foreach($this->form->getFieldset() as $field): ?>
        <div>
            <?php echo $field->label;

            if($field->name == 'jform[partner_invitation1]') {

                echo str_replace('COM_VIDEOTRANSLATION_BODY_PARTNER1',$this->textArea, $field->input);
            }
            else {
                echo $field->input;
            }

            ?>
        </div>
    <?php endforeach; ?>

    <br /><br />
    <input type="button" class="readon readon-back userform-back" value="<?php echo JText::_('COM_VIDEOTRANSLATION_BACK');?>" onclick="javascript:document.location.href='index.php?option=com_videotranslation&view=userdetails&Itemid=493'; return false;">
    <input type="submit" class="readon userform-next" value="<?php echo JText::_('COM_VIDEOTRANSLATION_DONE');?>" >


    <div>
        <input type="hidden" name="option" value="com_videotranslation" />
        <input type="hidden" name="task" value="partnersdetails.save" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

