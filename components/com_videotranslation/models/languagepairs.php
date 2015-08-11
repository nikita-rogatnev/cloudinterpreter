<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modelitem');

class VideoTranslationModellanguagepairs extends JModelForm
{
	protected $item;


    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_videotranslation.timezones', 'timezones', array('control' => 'jform', 'load_data' => $loadData));
        return $form;
    }


    public function getlanguagepairsinfo() {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query = "SELECT a.*, b.name as language1, c.name as language2 FROM #__vt_pairslanguages as a
                left join #__vt_languages as b ON a.langId1 = b.id
                left join #__vt_languages as c ON a.langId2 = c.id
                WHERE a.usesInApp > 0";

        $db->setQuery($query);
        $rows=  $db->loadObjectList();
        return json_encode($rows);
    }


}
