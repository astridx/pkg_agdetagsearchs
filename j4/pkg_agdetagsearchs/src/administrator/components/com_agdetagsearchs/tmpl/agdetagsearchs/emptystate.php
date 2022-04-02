<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_agdetagsearchs
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;

$displayData = [
	'textPrefix' => 'COM_AGDETAGSEARCHS',
	'formURL'    => 'index.php?option=com_agdetagsearchs',
	'helpURL'    => 'https://github.com/astridx/pkg_agdetagsearchs/tree/master/j4/pkg_agdetagsearchs',
	'icon'       => 'icon-address-book contact',
];

$user = Factory::getApplication()->getIdentity();

if ($user->authorise('core.create', 'com_agdetagsearchs') || count($user->getAuthorisedCategories('com_contact', 'core.create')) > 0)
{
	$displayData['createURL'] = 'index.php?option=com_agdetagsearchs&task=agdetagsearch.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
