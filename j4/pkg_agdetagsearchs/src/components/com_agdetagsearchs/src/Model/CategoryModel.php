<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agdetagsearchs
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgdetagsearchsNamespace\Component\Agdetagsearchs\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

/**
 * Single item model for a agdetagsearch
 *
 * @package     Joomla.Site
 * @subpackage  com_agdetagsearchs
 * @since       __BUMP_VERSION__
 */
class CategoryModel extends ListModel
{
	/**
	 * Category item data
	 *
	 * @var    CategoryNode
	 */
	protected $_item = null;

	/**
	 * Array of agdetagsearchs in the category
	 *
	 * @var    \stdClass[]
	 */
	protected $_articles = null;

	/**
	 * Category left and right of this one
	 *
	 * @var    CategoryNode[]|null
	 */
	protected $_siblings = null;

	/**
	 * Array of child-categories
	 *
	 * @var    CategoryNode[]|null
	 */
	protected $_children = null;

	/**
	 * Parent category of the current one
	 *
	 * @var    CategoryNode|null
	 */
	protected $_parent = null;

	/**
	 * The category that applies.
	 *
	 * @var    object
	 */
	protected $_category = null;

	/**
	 * The list of other agdetagsearch categories.
	 *
	 * @var    array
	 */
	protected $_categories = null;

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'state', 'a.state',
				'ordering', 'a.ordering',
				'featuredordering', 'a.featured'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to get a list of items.
	 *
	 * @return  mixed  An array of objects on success, false on failure.
	 */
	public function getItems()
	{
		// Invoke the parent getItems method to get the main list
		$items = parent::getItems();

		if ($items === false)
		{
			return false;
		}

		// Convert the params field into an object, saving original in _params
		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$item = &$items[$i];

			if (!isset($this->_params))
			{
				$item->params = new Registry($item->params);
			}

			// Some contexts may not use tags data at all, so we allow callers to disable loading tag data
			if ($this->getState('load_tags', true))
			{
				$item->tags = new TagsHelper;
				$item->tags->getItemTags('com_agdetagsearchs.agdetagsearch', $item->id);
			}
		}

		return $items;
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string    An SQL query
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function getListQuery()
	{
		$user   = Factory::getUser();
		$groups = $user->getAuthorisedViewLevels();

		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select($this->getState('list.select', 'a.*'))
			->select($this->getSlugColumn($query, 'a.id', 'a.alias') . ' AS slug')
			->select($this->getSlugColumn($query, 'c.id', 'c.alias') . ' AS catslug')
			->from($db->quoteName('#__agdetagsearchs_details', 'a'))
			->leftJoin($db->quoteName('#__categories', 'c') . ' ON c.id = a.catid')
			->whereIn($db->quoteName('a.access'), $groups);

		// Filter by category.
		if ($categoryId = $this->getState('category.id'))
		{
			$query->where($db->quoteName('a.catid') . ' = :acatid')
				->whereIn($db->quoteName('c.access'), $groups);
			$query->bind(':acatid', $categoryId, ParameterType::INTEGER);
		}

		// Filter by state
		$state = $this->getState('filter.published');

		if (is_numeric($state))
		{
			$query->where($db->quoteName('a.published') . ' = :published');
			$query->bind(':published', $state, ParameterType::INTEGER);
		}
		else
		{
			$query->whereIn($db->quoteName('c.published'), [0,1,2]);
		}

		// Filter by start and end dates.
		$nowDate = Factory::getDate()->toSql();

		if ($this->getState('filter.publish_date'))
		{
			$query->where('(' . $db->quoteName('a.publish_up')
				. ' IS NULL OR ' . $db->quoteName('a.publish_up') . ' <= :publish_up)'
			)
				->where('(' . $db->quoteName('a.publish_down')
					. ' IS NULL OR ' . $db->quoteName('a.publish_down') . ' >= :publish_down)'
				)
				->bind(':publish_up', $nowDate)
				->bind(':publish_down', $nowDate);
		}

		// Filter by search in title
		$search = $this->getState('list.filter');

		if (!empty($search))
		{
			$search = '%' . trim($search) . '%';
			$query->where($db->quoteName('a.name') . ' LIKE :name ');
			$query->bind(':name', $search);
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$language = [Factory::getLanguage()->getTag(), '*'];
			$query->whereIn($db->quoteName('a.language'), $language);
		}

		// Set sortname ordering if selected
		if ($this->getState('list.ordering') === 'sortname')
		{
			$query->order($db->escape('a.sortname1') . ' ' . $db->escape($this->getState('list.direction', 'ASC')))
				->order($db->escape('a.sortname2') . ' ' . $db->escape($this->getState('list.direction', 'ASC')))
				->order($db->escape('a.sortname3') . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
		}
		elseif ($this->getState('list.ordering') === 'featuredordering')
		{
			$query->order($db->escape('a.featured') . ' DESC')
				->order($db->escape('a.ordering') . ' ASC');
		}
		else
		{
			$query->order($db->escape($this->getState('list.ordering', 'a.ordering')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
		}

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = Factory::getApplication();
		$params = ComponentHelper::getParams('com_agdetagsearchs');

		// Get list ordering default from the parameters
		if ($menu = $app->getMenu()->getActive())
		{
			$menuParams = $menu->getParams();
		}
		else
		{
			$menuParams = new Registry;
		}

		$mergedParams = clone $params;
		$mergedParams->merge($menuParams);

		// List state information
		$format = $app->input->getWord('format');

		$numberOfAgdetagsearchsToDisplay = $mergedParams->get('agdetagsearchs_display_num');

		if ($format === 'feed')
		{
			$limit = $app->get('feed_limit');
		}
		elseif (isset($numberOfAgdetagsearchsToDisplay))
		{
			$limit = $numberOfAgdetagsearchsToDisplay;
		}
		else
		{
			$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'uint');
		}

		$this->setState('list.limit', $limit);

		$limitstart = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $limitstart);


		// Get the selected list of types from the request. If none are specified all are used.
		$typesr = $app->input->get('types', array(), 'array');

		if ($typesr)
		{
			// Implode is needed because the array can contain a string with a coma separated list of ids
			$typesr = implode(',', $typesr);

			// Sanitise
			$typesr = explode(',', $typesr);
			$typesr = ArrayHelper::toInteger($typesr);

			$this->setState('typesr', $typesr);
		}

		$language = $app->input->getString('tag_list_language_filter');
		$this->setState('language', $language);


		// Optional filter text
		$itemid = $app->input->get('Itemid', 0, 'int');
		$search = $app->getUserStateFromRequest('com_agdetagsearchs.category.list.' . $itemid . '.filter-search', 'filter-search', '', 'string');
		$this->setState('list.filter', $search);

		$orderCol = $app->input->get('filter_order', $mergedParams->get('initial_sort', 'ordering'));

		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'ordering';
		}

		$this->setState('list.ordering', $orderCol);

		$listOrder = $app->input->get('filter_order_Dir', 'ASC');

		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}

		$this->setState('list.direction', $listOrder);

		$id = $app->input->get('id', 0, 'int');
		$this->setState('category.id', $id);

		$user = Factory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_agdetagsearchs')) && (!$user->authorise('core.edit', 'com_agdetagsearchs')))
		{
			// Limit to published for people who can't edit or edit.state.
			$this->setState('filter.published', 1);

			// Filter by start and end dates.
			$this->setState('filter.publish_date', true);
		}

		$this->setState('filter.language', Multilanguage::isEnabled());

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Method to get category data for the current category
	 *
	 * @return  object  The category object
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function getCategory()
	{
		if (!is_object($this->_item))
		{
			$app = Factory::getApplication();
			$menu = $app->getMenu();
			$active = $menu->getActive();

			if ($active)
			{
				$params = $active->getParams();
			}
			else
			{
				$params = new Registry;
			}

			$options = array();
			$options['countItems'] = $params->get('show_cat_items', 1) || $params->get('show_empty_categories', 0);
			$categories = Categories::getInstance('Agdetagsearchs', $options);
			$this->_item = $categories->get($this->getState('category.id', 'root'));

			if (is_object($this->_item))
			{
				$this->_children = $this->_item->getChildren();
				$this->_parent = false;

				if ($this->_item->getParent())
				{
					$this->_parent = $this->_item->getParent();
				}

				$this->_rightsibling = $this->_item->getSibling();
				$this->_leftsibling = $this->_item->getSibling(false);
			}
			else
			{
				$this->_children = false;
				$this->_parent = false;
			}
		}

		return $this->_item;
	}

	/**
	 * Get the parent category.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 */
	public function getParent()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_parent;
	}

	/**
	 * Get the sibling (adjacent) categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 */
	public function &getLeftSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_leftsibling;
	}

	/**
	 * Get the sibling (adjacent) categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 */
	public function &getRightSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_rightsibling;
	}

	/**
	 * Get the child categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 */
	public function &getChildren()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_children;
	}

	/**
	 * Generate column expression for slug or catslug.
	 *
	 * @param   \JDatabaseQuery  $query  Current query instance.
	 * @param   string           $id     Column id name.
	 * @param   string           $alias  Column alias name.
	 *
	 * @return  string
	 *
	 * @since   __BUMP_VERSION__
	 */
	private function getSlugColumn($query, $id, $alias)
	{
		return 'CASE WHEN '
			. $query->charLength($alias, '!=', '0')
			. ' THEN '
			. $query->concatenate(array($query->castAsChar($id), $alias), ':')
			. ' ELSE '
			. $query->castAsChar($id) . ' END';
	}





















	/**
	 * Method to get neighbour tags of a tag
	 *
	 * @param   integer  $tagid  Optional primary key of the category to increment.
	 *
	 * @access public
	 * @return array
	 */
	public function getTagdata($tagid)
	{
		$this->typesr = $this->getState('typesr');
		$this->language = $this->getState('language');

		$params = ComponentHelper::getParams('com_agdetagsearchs');
		$anyOrAll = true;

		$db = Factory::getDbo();
		$jhelpertags = new TagsHelper;
		$tagquery = $jhelpertags->getTagItemsQuery(
			$tagid,
			$this->typesr,
			$includeChildren = false,
			$orderByOption = 'c.core_title',
			$orderDir = 'ASC',
			$anyOrAll,
			$this->language,
			$stateFilter = '1'
		);

		$db->setQuery($tagquery);
		$result = $db->loadObjectList();

		return $result;
	}

	/**
	 * Method to get agdetagsearch item data for the category
	 *
	 * @access public
	 * @return array
	 */
	public function getData()
	{
		// Prepare the data.
		// Compute the agdetagsearch slug & link url.
		$tagids = $this->tagids = [];

		$app = Factory::getApplication();

		$result = $this->results = [];
		$resultSum = $this->resultSum = [];
		$resultSumUnique = $this->resultSumUnique = [];

		$typesr = $this->typesr = $this->getState('typesr');
		$language = $this->language = $this->getState('language');
		$limit = $this->limit = $this->getState('limit');
		$agtagscolumnlimit = $this->agtagscolumnlimit = $this->getState('agtagscolumnlimit');

		$params = ComponentHelper::getParams('com_agdetagsearchs');

		// $anyOrAll = true;
		$anyOrAll = $params->get('andor', 1);

		$items = $this->items = $this->getItems();

		foreach ($this->items as $item) {
			$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
			$postvalue = 'agdetagsearchs_' . str_replace(' ', '', $item->alias);
			$pv = $app->input->get($postvalue);
			$tagids = $this->tagids[] = $pv;
			$item->tagids[$postvalue] = $pv;
		}

		$unitqtagids = $this->uniqtagids = [];

		foreach ($this->tagids as $tagid) {
			if (is_array($tagid)) {
				$this->uniqtagids = array_unique(array_merge($this->uniqtagids, $tagid));
			}
		}

		$db = Factory::getDbo();
		$jhelpertags = new TagsHelper;

		/*
		 * @param   mixed    $tagId            Tag or array of tags to be matched
		 * @param   mixed    $typesr           Null, type or array of type aliases for content types to be included in the results
		 * @param   boolean  $includeChildren  True to include the results from child tags
		 * @param   string   $orderByOption    Column to order the results by
		 * @param   string   $orderDir         Direction to sort the results in
		 * @param   boolean  $anyOrAll         True to include items matching at least one tag, false to include
		 *                                     items all tags in the array.
		 * @param   string   $languageFilter   Optional filter on language. Options are 'all', 'current' or any string.
		 * @param   string   $stateFilter      Optional filtering on publication state, defaults to published or unpublished.
		 */
		$tagquery = $jhelpertags->getTagItemsQuery(
			$this->uniqtagids,
			$this->typesr,
			$includeChildren = false,
			$orderByOption = 'c.core_title',
			$orderDir = 'ASC',
			$anyOrAll,
			$this->language,
			$stateFilter = '1'
		);

		$db->setQuery($tagquery);
		$result = $this->result = $db->loadObjectList();

		$this->_total = count($this->result);

		$this->_data = [];

		if ($this->getState('limit') > 0) {
			$this->_data = array_splice($this->result, $this->getState('limitstart'), $this->getState('limit'));
		} else {
			$this->_data = $this->result;
		}

		return $this->_data;
	}

}
