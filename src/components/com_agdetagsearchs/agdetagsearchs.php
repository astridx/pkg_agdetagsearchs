<?php
/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2018 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/helpers/route.php';

$controller	= JControllerLegacy::getInstance('Agdetagsearchs');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
