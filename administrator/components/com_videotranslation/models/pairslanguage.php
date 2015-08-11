<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class VideoTranslationModelPairsLanguage extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_videotranslation.pairslanguage', 'pairslanguage', array('control' => 'jform', 'load_data' => $loadData));
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_videotranslation.edit.pairslanguage.data', array());

		if(empty($data)){
			$data = $this->getItem();
		}

		return $data;
	}

	public function getTable($name = '', $prefix = 'VideoTranslationTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}

    public function getPreviousMonday() {
        $monday = JRequest::getInt('monday',strtotime('monday this week'));

        return $monday-60*60*24*7;
    }

    public function getNextMonday() {
        $monday = JRequest::getInt('monday',strtotime('monday this week'));

        return $monday+60*60*24*7;

    }

    public function getCalendar() {

        $data = new stdClass();

        $pairId = JRequest::getInt('id');
        $monday = JRequest::getInt('monday',strtotime('monday this week'));

        $pair =& JTable::getInstance('pairslanguage', 'VideoTranslationTable');
        $pair->load($pairId);

        $language =& JTable::getInstance('language', 'VideoTranslationTable');
        $language->load($pair->langId1);

        $data->language1 = $language->name;

        $language->load($pair->langId2);

        $data->language2 = $language->name;

        $data->monday = date("F j",$monday);
        $data->sunday = date("F j, Y",$monday+6*24*60*60);


        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_videotranslation/models', 'VideoTranslationModel');
        $frontpage = JModelLegacy::getInstance('frontpage', 'VideoTranslationModel', array('ignore_request' => true));

        $this->populateState();
        $filter_interpreter = $this->getState('filter.interpreter_id');


        for($i=0;$i<7;$i++){
            $data->weekdays[] = date("l",$monday+$i*24*60*60);

            //list items
            $data->rows[date("l",$monday+$i*24*60*60)] = $frontpage->getItemsForThisDay($monday+$i*24*60*60,$monday+$i*24*60*60+24*60*60,$pairId,0,$filter_interpreter);
        }

        $userspair =& JTable::getInstance('userspair', 'VideoTranslationTable');
       // $language->load($pair->langId1);

        $data->interpreters = $userspair->getInterpreters($pairId,$filter_interpreter);

        for($i=0;$i<96;$i++) {
            $data->items[$i] = new stdClass();
            $data->items[$i]->time = date("G:i",$monday+$i*15*60);
        }




        return $data;
    }

    public function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication('administrator');

        $state = $app->getUserStateFromRequest($this->context . '.filter.interpreter_id', 'filter_interpreter_id', '', 'string');

        $this->setState('filter.interpreter_id', $state);




        // List state information.
        parent::populateState();
    }
}
