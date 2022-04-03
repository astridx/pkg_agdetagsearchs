<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agdetagsearchs
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgdetagsearchsNamespace\Component\Agdetagsearchs\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Language\Multilanguage;

/**
 * Agdetagsearchs Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_agdetagsearchs
 * @since       __DEPLOY_VERSION__
 */
abstract class RouteHelper
{
	/**
	 * Get the URL route for a agdetagsearchs from a agdetagsearch ID, agdetagsearchs category ID and language
	 *
	 * @param   integer  $id        The id of the agdetagsearchs
	 * @param   integer  $catid     The id of the agdetagsearchs's category
	 * @param   mixed    $language  The id of the language being used.
	 *
	 * @return  string  The link to the agdetagsearchs
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getAgdetagsearchsRoute($id, $catid, $language = 0)
	{
		// Create the link
		$link = 'index.php?option=com_agdetagsearchs&view=agdetagsearchs&id=' . $id;

		if ($catid > 1) {
			$link .= '&catid=' . $catid;
		}

		if ($language && $language !== '*' && Multilanguage::isEnabled()) {
			$link .= '&lang=' . $language;
		}

		return $link;
	}

	/**
	 * Get the URL route for a agdetagsearch from a agdetagsearch ID, agdetagsearchs category ID and language
	 *
	 * @param   integer  $id        The id of the agdetagsearchs
	 * @param   integer  $catid     The id of the agdetagsearchs's category
	 * @param   mixed    $language  The id of the language being used.
	 *
	 * @return  string  The link to the agdetagsearchs
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getAgdetagsearchRoute($id, $catid, $language = 0)
	{
		// Create the link
		$link = 'index.php?option=com_agdetagsearchs&view=agdetagsearch&id=' . $id;

		if ($catid > 1) {
			$link .= '&catid=' . $catid;
		}

		if ($language && $language !== '*' && Multilanguage::isEnabled()) {
			$link .= '&lang=' . $language;
		}

		return $link;
	}

	/**
	 * Get the URL route for a agdetagsearchs category from a agdetagsearchs category ID and language
	 *
	 * @param   mixed  $catid     The id of the agdetagsearchs's category either an integer id or an instance of CategoryNode
	 * @param   mixed  $language  The id of the language being used.
	 *
	 * @return  string  The link to the agdetagsearchs
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getCategoryRoute($catid, $language = 0)
	{
		if ($catid instanceof CategoryNode) {
			$id = $catid->id;
		} else {
			$id = (int) $catid;
		}

		if ($id < 1) {
			$link = '';
		} else {
			// Create the link
			$link = 'index.php?option=com_agdetagsearchs&view=category&id=' . $id;

			if ($language && $language !== '*' && Multilanguage::isEnabled()) {
				$link .= '&lang=' . $language;
			}
		}

		return $link;
	}
}
