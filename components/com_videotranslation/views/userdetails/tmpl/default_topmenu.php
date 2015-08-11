<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$view = JRequest::getVar('view');
$session = JFactory::getSession();
$cart = $session->get('cart');
$call_mode = $cart['call_mode'];


?>
<div class="time-select" onclick="javascript:document.location.href=document.getElementById('live_site').innerHTML; return false;">
    <div><?php echo JText::_('COM_VIDEOTRANSLATION_MAIN_PAGE_TAB');?></div>
</div>
<div class="<?php echo ($view=='userdetails')?"your-data-active":"your-data";?>" <?php echo ($view=='partnersdetails')?"onclick='window.history.back();'":'';?>>
    <div><?php echo JText::_('COM_VIDEOTRANSLATION_YOUR_DETAILS_PAGE_TAB');?></div>
</div>
<div class="<?php echo ($view=='partnersdetails')?"partners-datails-active":"partners-datails";?>"  <?php echo ($call_mode=='2')?"style=\"display:none\"":""?>  >
    <div><?php echo JText::_('COM_VIDEOTRANSLATION_YOUR_PARTNERS_DETAIL_PAGE_TAB');?></div>
</div>

<div style="clear: both; padding-bottom: 40px;"></div>