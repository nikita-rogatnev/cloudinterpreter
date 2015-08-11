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

class VideoTranslationModelUsersPair extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_videotranslation.userspair', 'userspair', array('control' => 'jform', 'load_data' => $loadData));
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_videotranslation.edit.userspair.data', array());
		if(empty($data)){
			$data = $this->getItem();

            if($data->user_id) {
            $data->subj_ids = $this->getSubjects($data->user_id);
            }
		}
		return $data;
	}


    public function getSubjects($user_id) {

        $db = JFactory::getDBO();
        $query = "SELECT a.subj_id as value,b.name from #__vt_user_subject as a, #__vt_subjects as b WHERE a.user_id = ".$user_id." and a.subj_id = b.id";

        $db->setQuery($query);
        $rows = $db->loadobjectList();

        return $rows;

    }

	public function getTable($name = '', $prefix = 'VideoTranslationTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}


    public function save($data)
    {

        // Initialise variables;
        $dispatcher = JDispatcher::getInstance();
        $table      = $this->getTable();
        $key         = $table->getKeyName();
        $pk         = (!empty($data[$key])) ? $data[$key] : (int)$this->getState($this->getName().'.id');
        $isNew      = true;

        //$data['subj_ids'] = implode(',', $data['subj_ids']);

        // Load the row if saving an existing record.
        if ($pk > 0)
        {
            $table->load($pk);
            $isNew = false;
        }

        // Bind the data.
        if (!$table->bind($data))
        {
            $this->setError($table->getError());
            return false;
        }

        // Prepare the row for saving
        $this->prepareTable($table);

        // Check the data.
        if (!$table->check())
        {
            $this->setError($table->getError());
            return false;
        }

        // Trigger the onContentBeforeSave event.
        $result = $dispatcher->trigger($this->event_before_save, array($this->option . '.' . $this->name, $table, $isNew));
        if (in_array(false, $result, true))
        {
            $this->setError($table->getError());
            return false;
        }

        // Store the data.
        if (!$table->store())
        {
            $this->setError($table->getError());
            return false;
        }

        $this->saveUserSubjects($data);


        return true;
    }


    function saveUserSubjects($data) {

        $db = JFactory::getDBO();

//        echo "<pre>";
//        print_r($data); die;

        $query = $db->getQuery(true);
        $query = "delete from  #__vt_user_subject where user_id = ".$data['user_id'];

        $db->setQuery($query);
        $db->query();


        for($i=0;$i<count($data['subj_ids']);$i++) {
            $query = $db->getQuery(true);

            $subj_id = $data['subj_ids'][$i];

            $query = "insert into #__vt_user_subject (`id`, `user_id` ,`subj_id`) values (NULL , ".$data['user_id'].", ".$subj_id.");";
            $db->setQuery($query);
            $db->query();
        }

    }
}
