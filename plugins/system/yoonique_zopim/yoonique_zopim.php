<?php
/*
 * @version   4.1.1 Fri Sep 20 04:16:18 2013 -0700
 * @package   yoonique zoo shortcut plugin
 * @author    yoonique[.]net
 * @copyright Copyright (C) yoonique[.]net and all rights reserved.
 * @license   GPL v3
 */



// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class plgSystemYoonique_zopim extends JPlugin
{
	function plgSystemYoonique_zopim(&$subject, $config)
	{
		parent::__construct($subject, $config);

	}

	function onAfterRender()
	{
		$app = &JFactory::getApplication();

		$format = JRequest::getCmd('format');
		$tmpl = JRequest::getCmd('tmpl');

		if($format == 'raw' || $tmpl == 'component') {
			return;
		}

		$zopim_id = $this->params->get('zopim_id', '');

		if($zopim_id == '' || $app->isAdmin()) {
			return;
		}

		$showcount = 0;
		$hidecount = 0;

		$position_show = $this->params->get('zopim_show', '');
		$position_hide = $this->params->get('zopim_hide', '');
		$zopim_version = $this->params->get('zopim_version', 'v1');

		if (!($position_hide || $position_show)) {
			$showcount = 1;
		} else {
			if ($position_show) {
				jimport( 'joomla.application.module.helper' );
				$showcount = count(JModuleHelper::getModules($position_show));
			}
			if (!$showcount && $position_hide) {
				jimport( 'joomla.application.module.helper' );
				$hidecount = count(JModuleHelper::getModules($position_hide));
			}
		}


		if (!($showcount || $hidecount))
			return;

		$tablet = $this->params->get('zopim_tablet', 0);
		$mobile = $this->params->get('zopim_mobile', 0);

		if ($tablet || $mobile) {
			require_once(dirname(__FILE__).'/yoonique_zopim/mobile_detect/Mobile_Detect.php');
			$detect = new Mobile_Detect();
			if ($tablet && $detect->IsTablet())
				return;
			if ($mobile && $detect->isMobile() && !$detect->isTablet())
				return;
		}

		$language_id = $this->params->get('language_id', '');

		$buffer = JResponse::getBody();

		$zopim = '$zopim';

		if ($zopim_version === 'v1') {
		$zopim_widget = <<<EOF
<!--Start of yoonique.net Zopim Live Chat Script-->
<script type="text/javascript">window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set._.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');$.src='//cdn.zopim.com/?$zopim_id';z.t=+new Date;$.type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');</script>
<!--End of yoonique.net Zopim Live Chat Script-->
EOF;
		} else {
		$zopim_widget = <<<EOF
<!--Start of yoonique.net Zopim Live Chat Script-->
<script type="text/javascript">window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set._.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8'); $.src='//v2.zopim.com/?$zopim_id';z.t=+new Date;$.type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');</script>
<!--End of yoonique.net Zopim Live Chat Script-->
EOF;
		}

		$zopim_language = <<<EOF
<script type="text/javascript">$zopim(function() { $zopim.livechat.setLanguage('$language_id'); });</script>
EOF;

		if ($zopim_version === 'v1') {
		$zopim_hide = <<<EOF
<script type="text/javascript">$zopim(function() { $zopim.livechat.button.hide();$zopim.livechat.window.hide();$zopim.livechat.bubble.hide(); });</script>
EOF;
		} else {
		$zopim_hide = <<<EOF
<script type="text/javascript">$zopim(function() { $zopim.livechat.hideAll(); });</script>
EOF;
		}

		$buffer = str_replace ("</head>", $zopim_widget."</head>", $buffer);

		$zopim_body = '';

		if (!$showcount && $hidecount) {
			$zopim_body .= $zopim_hide;
		}

		if ($language_id) {
			$zopim_body .= $zopim_language;
		}

		if ($zopim_body) {
			$buffer = str_replace ("</body>", $zopim_body."</body>", $buffer);
		}

		JResponse::setBody($buffer);

		return true;
	}
}
