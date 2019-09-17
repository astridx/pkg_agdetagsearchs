<?php
/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2018 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Agdetagsearchs helper.
 *
 * @since  1.6
 */
class AgdetagsearchsHelper extends JHelperContent
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu($vName = 'agdetagsearchs')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_AGDETAGSEARCHS_SUBMENU_AGDETAGSEARCHS'),
			'index.php?option=com_agdetagsearchs&view=agdetagsearchs',
			$vName == 'agdetagsearchs'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_AGDETAGSEARCHS_SUBMENU_FORMS'),
			'index.php?option=com_categories&extension=com_agdetagsearchs',
			$vName == 'categories'
		);
	}

	/**
	 * Adds Count Items for Agtagsearch Category Manager.
	 *
	 * @param   stdClass[]  &$items  The agdetagsearchs category objects.
	 *
	 * @return  stdClass[]  The agdetagsearchs category objects.
	 *
	 * @since   3.6.0
	 */
	public static function countItems(&$items)
	{
		$db = JFactory::getDbo();

		foreach ($items as $item)
		{
			$item->count_trashed     = 0;
			$item->count_archived    = 0;
			$item->count_unpublished = 0;
			$item->count_published   = 0;

			$query = $db->getQuery(true)
				->select('state, COUNT(*) AS count')
				->from($db->qn('#__agdetagsearchs'))
				->where($db->qn('catid') . ' = ' . (int) $item->id)
				->group('state');

			$db->setQuery($query);
			$agdetagsearchs = $db->loadObjectList();

			foreach ($agdetagsearchs as $agdetagsearch)
			{
				if ($agdetagsearch->state == 1)
				{
					$item->count_published = $agdetagsearch->count;
				}
				elseif ($agdetagsearch->state == 0)
				{
					$item->count_unpublished = $agdetagsearch->count;
				}
				elseif ($agdetagsearch->state == 2)
				{
					$item->count_archived = $agdetagsearch->count;
				}
				elseif ($agdetagsearch->state == -2)
				{
					$item->count_trashed = $agdetagsearch->count;
				}
			}
		}

		return $items;
	}

	/**
	 * Adds Count Items for Tag Manager.
	 *
	 * @param   stdClass[]  &$items     The agdetagsearch tag objects
	 * @param   string      $extension  The name of the active view.
	 *
	 * @return  stdClass[]
	 *
	 * @since   3.7.0
	 */
	public static function countTagItems(&$items, $extension)
	{
		$db = JFactory::getDbo();

		foreach ($items as $item)
		{
			$item->count_trashed = 0;
			$item->count_archived = 0;
			$item->count_unpublished = 0;
			$item->count_published = 0;

			$query = $db->getQuery(true);
			$query->select('published as state, count(*) AS count')
				->from($db->qn('#__contentitem_tag_map') . 'AS ct ')
				->where('ct.tag_id = ' . (int) $item->id)
				->where('ct.type_alias =' . $db->q($extension))
				->join('LEFT', $db->qn('#__categories') . ' AS c ON ct.content_item_id=c.id')
				->group('state');

			$db->setQuery($query);
			$agdetagsearchs = $db->loadObjectList();

			foreach ($agdetagsearchs as $agdetagsearch)
			{
				if ($agdetagsearch->state == 1)
				{
					$item->count_published = $agdetagsearch->count;
				}

				if ($agdetagsearch->state == 0)
				{
					$item->count_unpublished = $agdetagsearch->count;
				}

				if ($agdetagsearch->state == 2)
				{
					$item->count_archived = $agdetagsearch->count;
				}

				if ($agdetagsearch->state == -2)
				{
					$item->count_trashed = $agdetagsearch->count;
				}
			}
		}

		return $items;
	}
}
