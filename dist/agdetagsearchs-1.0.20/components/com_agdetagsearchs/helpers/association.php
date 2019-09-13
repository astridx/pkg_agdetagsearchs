<?php
/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2018 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('AgtagsearchsHelper', JPATH_ADMINISTRATOR . '/components/com_agtagsearchs/helpers/agtagsearchs.php');
JLoader::register('AgtagsearchsHelperRoute', JPATH_SITE . '/components/com_agtagsearchs/helpers/route.php');
JLoader::register('CategoryHelperAssociation', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/association.php');

/**
 * Agtagsearchs Component Association Helper
 *
 * @since  3.0
 */
abstract class AgtagsearchsHelperAssociation extends CategoryHelperAssociation
{
	/**
	 * Method to get the associations for a given item
	 *
	 * @param   integer  $id    Id of the item
	 * @param   string   $view  Name of the view
	 *
	 * @return  array   Array of associations for the item
	 *
	 * @since   3.0
	 */
	public static function getAssociations($id = 0, $view = null)
	{
		$jinput = JFactory::getApplication()->input;
		$view   = is_null($view) ? $jinput->get('view') : $view;
		$id     = empty($id) ? $jinput->getInt('id') : $id;

		if ($view == 'category' || $view == 'categories')
		{
			return self::getCategoryAssociations($id, 'com_agtagsearchs');
		}

		return array();
	}
}
