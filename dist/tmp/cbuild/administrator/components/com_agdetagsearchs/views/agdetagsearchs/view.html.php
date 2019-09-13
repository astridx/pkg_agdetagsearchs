<?php
/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2018 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of agdetagsearchs.
 *
 * @since  1.5
 */
class AgdetagsearchsViewAgdetagsearchs extends JViewLegacy
{
	/**
	 * Agdetagsearch data array
	 *
	 * @var array
	 */
	protected $items;

	/**
	 * Agdetagsearch data array
	 *
	 * @var array
	 */
	protected $pagination;

	/**
	 * Agdetagsearch data array
	 *
	 * @var array
	 */
	protected $state;

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$this->state         = $this->get('State');
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		AgdetagsearchsHelper::addSubmenu('agdetagsearchs');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/agdetagsearchs.php';

		$state = $this->get('State');
		$canDo = JHelperContent::getActions('com_agdetagsearchs', 'category', $state->get('filter.category_id'));
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_AGDETAGSEARCHS_MANAGER_AGDETAGSEARCHS'), 'link agdetagsearchs');

		if (count($user->getAuthorisedCategories('com_agdetagsearchs', 'core.create')) > 0)
		{
			JToolbarHelper::addNew('agdetagsearch.add');
		}

		if ($canDo->get('core.edit') || $canDo->get('core.edit.own'))
		{
			JToolbarHelper::editList('agdetagsearch.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('agdetagsearchs.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('agdetagsearchs.unpublish', 'JTOOLBAR_UNPUBLISH', true);

			JToolbarHelper::archiveList('agdetagsearchs.archive');
			JToolbarHelper::checkin('agdetagsearchs.checkin');
		}

		if ($state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'agdetagsearchs.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('agdetagsearchs.trash');
		}

		// Add a batch button
		if ($user->authorise('core.create', 'com_agdetagsearchs') && $user->authorise('core.edit', 'com_agdetagsearchs')
			&& $user->authorise('core.edit.state', 'com_agdetagsearchs'))
		{
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		if ($user->authorise('core.admin', 'com_agdetagsearchs') || $user->authorise('core.options', 'com_agdetagsearchs'))
		{
			JToolbarHelper::preferences('com_agdetagsearchs');
		}

		JToolbarHelper::help('JHELP_COMPONENTS_AGDETAGSEARCHS_LINKS');
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.state' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'a.hits' => JText::_('JGLOBAL_HITS'),
			'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
