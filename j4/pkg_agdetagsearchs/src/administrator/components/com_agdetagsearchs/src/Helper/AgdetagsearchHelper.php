<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_agdetagsearchs
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgdetagsearchsNamespace\Component\Agdetagsearchs\Administrator\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;

/**
 * Agdetagsearch component helper.
 *
 * @since  __BUMP_VERSION__
 */
class AgdetagsearchHelper extends ContentHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	public static function addSubmenu($vName)
	{
		if (ComponentHelper::isEnabled('com_fields') && ComponentHelper::getParams('com_agdetagsearchs')->get('custom_fields_enable', '1'))
		{
			\JHtmlSidebar::addEntry(
				Text::_('JGLOBAL_FIELDS'),
				'index.php?option=com_fields&context=com_agdetagsearchs.agdetagsearch',
				$vName == 'fields.fields'
			);
			\JHtmlSidebar::addEntry(
				Text::_('JGLOBAL_FIELD_GROUPS'),
				'index.php?option=com_fields&view=groups&context=com_agdetagsearchs.agdetagsearch',
				$vName == 'fields.groups'
			);
		}
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

		foreach ($items as $item) {
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

			foreach ($agdetagsearchs as $agdetagsearch) {
				if ($agdetagsearch->state == 1) {
					$item->count_published = $agdetagsearch->count;
				} else if ($agdetagsearch->state == 0) {
					$item->count_unpublished = $agdetagsearch->count;
				} else if ($agdetagsearch->state == 2) {
					$item->count_archived = $agdetagsearch->count;
				} else if ($agdetagsearch->state == -2) {
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

		foreach ($items as $item) {
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

			foreach ($agdetagsearchs as $agdetagsearch) {
				if ($agdetagsearch->state == 1) {
					$item->count_published = $agdetagsearch->count;
				}

				if ($agdetagsearch->state == 0) {
					$item->count_unpublished = $agdetagsearch->count;
				}

				if ($agdetagsearch->state == 2) {
					$item->count_archived = $agdetagsearch->count;
				}

				if ($agdetagsearch->state == -2) {
					$item->count_trashed = $agdetagsearch->count;
				}
			}
		}

		return $items;
	}

}
