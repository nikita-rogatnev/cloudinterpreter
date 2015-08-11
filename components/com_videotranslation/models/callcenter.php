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

class VideoTranslationModelCallcenter extends JModelForm
{



    public function getForm($data = array(), $loadData = true)
    {

    }

    public function get_email()
    {
        $socket_id = JRequest::getVar('socket_id');
        if ($socket_id) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('caller')));
            $query->from($db->quoteName('#__vt_statistic'));
            $query->where('socket_id = "'.$socket_id.'"');

            $query->order('time_start DESC');
            $query->setLimit(1);
            $db->setQuery($query);
            $results1 = $db->loadObjectList(); 

          $db    = JFactory::getDbo();
          $query  = $db->getQuery(true);
          $query->select('email');
          $query->from('#__users');
          $query->where('id = '.$results1[0]->caller);
          $db->setQuery($query);
          $results = $db->loadObjectList();
          echo $results[0]->email;

         }
         else {
            echo 'no params for get email';
         }
    }




    public function get_history()
    {
        $table = '<table class="table history">';
        //$table .= '<thead><td>Время</td><td>Длительность</td><td>Третий</td></thead><tbody>';
        $db = JFactory::getDbo();
         
        // Create a new query object.
        $query = $db->getQuery(true);
         
        // Select all records from the user profile table where key begins with "custom.".
        // Order it by the ordering field.
        $query->select($db->quoteName(array('id', 'caller', 'connected', 'time_start', 'time_end', 'interpreter_id', 'isOS')));
        $query->from($db->quoteName('#__vt_statistic'));
        //$query->where($db->quoteName('profile_key') . ' LIKE '. $db->quote('\'custom.%\''));
        $query->limit(10);
        $query->order('time_start DESC');
         
        // Reset the query using our newly populated query object.
        $db->setQuery($query);
         
        // Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $results = $db->loadObjectList();
       
        foreach ($results as $row) {
            $table .= '<tr class="'.$this->call_ok($row->connected).'">';
            $table .= '<td>'.gmdate("d.m.y H:i", $row->time_start).' (UTC)</td>';
            $table .= '<td class="margin_left_td">'.$this->get_username_by_id($row->caller).' <img class="call_to" src=""> '.$this->get_username_by_id($row->interpreter_id).'</td>';
            //$table .= '<td>'.$this->get_username_by_id($row->interpreter_id).'</td>';
            //$table .= '<td align="right">'.$this->diff_dates($row->time_start, $row->time_end).'</td>';
            $table .= '<td>'.$this->isOS_render($row->isOS).'</td>';
            $table .= '</tr>';
        }

        echo $table."</tbody></table>";


    }

    private function diff_dates($time_start, $time_end)
    {
        if ($time_end == null) {
            return '0:00';
        } else {
            return gmdate("i:s", $time_end - $time_start);
            }
    }

    private function isOS_render($isOS)
    {
        $text_return = '';
        if ($isOS == null) {
            $text_return = '';
        }
        if ($isOS == 1) {
            $text_return = '<img class="device mobile" src="">';
        }
        if ($isOS == '0') {
            $text_return = '<img class="device desktop" src="">';
        }

        return $text_return;
    }

    private function get_username_by_id($id)
    {
        
      $db    = JFactory::getDbo();
      $query  = $db->getQuery(true);
      $query->select('username');
      $query->from('#__users');
      $query->where('id = '.$id);
      $db->setQuery($query);
      $results = $db->loadObjectList();

      /*$numrow = $db->getNumRows();
      if ($numrow != 0) {
        return $results[0]->username;
      } else {
        return 'guest';
      }*/
      if (isset($results[0]->username)) {
        return $results[0]->username;
      } else {
        return 'guest';
      }

    }

    private function call_ok($connected)
    {
         if ($connected == 0) {
            return 'call_no';   
         } else {
            return 'call_yes';  
         }
    }


}