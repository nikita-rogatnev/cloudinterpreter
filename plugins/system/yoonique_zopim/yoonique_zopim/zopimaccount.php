<?php

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

if (!class_exists('JFakeElementBase')) {
	if (version_compare(JVERSION, '1.6.0', 'ge')) {

		class JFakeElementBase extends JFormField {

			public function getInput() {
				
			}

		}

	} else {

		class JFakeElementBase extends JElement {
			
		}

	}
}

class JFakeElementZopimAccount extends JFakeElementBase {

	public $_name = 'ZopimAccount';

	public function fetchElement($name, $value, &$node, $control_name) {
		return $this->getInput();
	}

	public function getInput() {
		global $mainframe;

		$uri = str_replace("/", "/", str_replace(JPATH_SITE, JURI::base(), dirname(__FILE__)));
		$uri = str_replace("/administrator/", "", $uri);
		$token = JFactory::getSession()->getFormToken();

		JHTML::script($uri."/zopim.js", false, true);
		$html = '<script type="text/javascript" >
			var url="' . $uri . '/zopimajax.php' . '";
			</script>';

		$html2 = <<<EOFORM
		<style type="text/css">
			#jform_params_zopimlink-lbl { min-width:0px;padding:0;}
		</style>
    <div>
      <h3>
        Enter your Zopim username and password and link up to your Zopim account by clicking on the 'Get Widget Id' button.
      </h3>
      <div>
        <table>
          <tr valign="top">
            <th scope="row">Username</th>
            <td>
              <input size="40" type="text" id="zopimUsername" name="zopimUsername" value="" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">Password</th>
            <td>
              <input type="password" id="zopimPassword" name="zopimPassword" value="" />
            </td>
          </tr>
          <tr valign="top">
            <td></td>
            <td>
              <input type="button" onclick="zopimconnect('$token')" value="Get Widget Id" />
            </td>
          </tr>
        </table>
      </div>
      <h3>If you do not have an account yet, please click <a target="_blank" href="http://yoonique.net/index.php?option=com_weblinks&task=weblink.go&id=1">here</a> to get a free account.</h3>
      <h3 id="zopimstatus"></h3>
    </div>
	<hr />
EOFORM;

		return $html . $html2;
	}

}

if (version_compare(JVERSION, '1.6.0', 'ge')) {

	class JFormFieldZopimAccount extends JFakeElementZopimAccount {
		
	}

} else {

	class JElementZopimAccount extends JFakeElementZopimAccount {
		
	}

}
