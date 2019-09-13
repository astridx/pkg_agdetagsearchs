<?php
/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2018 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

if (!JFactory::getUser()->authorise('core.manage', 'com_agdetagsearchs'))
{
	throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

$controller	= JControllerLegacy::getInstance('Agdetagsearchs');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
