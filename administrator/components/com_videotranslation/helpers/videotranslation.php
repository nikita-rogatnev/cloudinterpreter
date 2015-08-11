<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

class VideoTranslationHelper
{
	public static function addSubmenu($vName)
	{

        if(JRequest::getVar('view') !='pairslanguage') {
            JHtmlSidebar::addEntry('Languages',
                'index.php?option=com_videotranslation', $vName == 'languages');
            JHtmlSidebar::addEntry('Language Pairs',
                'index.php?option=com_videotranslation&view=pairslanguages', $vName == 'pairslanguages');
            JHtmlSidebar::addEntry('Interpreters',
                'index.php?option=com_videotranslation&view=userspairs', $vName == 'userspairs');
            JHtmlSidebar::addEntry('Subjects',
                'index.php?option=com_videotranslation&view=subjects', $vName == 'subjects');
            JHtmlSidebar::addEntry('Subjects Rates',
                'index.php?option=com_videotranslation&view=subjectsrates', $vName == 'subjectsrates');
            JHtmlSidebar::addEntry('Credits',
                'index.php?option=com_videotranslation&view=credits', $vName == 'credits');
            JHtmlSidebar::addEntry('Orders',
                'index.php?option=com_videotranslation&view=orders', $vName == 'orders');
        }

    }

    public static function getInterpreters()
    {
        $options = array();

        $pair_id = JRequest::getInt('id');


        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('b.id As value, b.name As text')
            ->from('#__vt_translatorpair AS a, #__users as b')
            ->where('a.user_id = b.id AND a.pair_id='.$pair_id)
            ->order('b.name');

        // Get the options.
        $db->setQuery($query);

        try
        {
            $options = $db->loadObjectList();
        }
        catch (RuntimeException $e)
        {
            JError::raiseWarning(500, $e->getMessage());
        }

        // Merge any additional options in the XML definition.
        //$options = array_merge(parent::getOptions(), $options);

        //array_unshift($options, JHtml::_('select.option', '0', "No interpreters"));

        return $options;
    }
}
