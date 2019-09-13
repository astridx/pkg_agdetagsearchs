<?php
/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2018 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View to edit a agdetagsearch.
 *
 * @since  1.5
 */
class AgdetagsearchsViewAgdetagsearch extends JViewLegacy
{
	/**
	 * Agdetagsearch state
	 *
	 * @var array
	 */
	protected $state;

	/**
	 * Agdetagsearch item
	 *
	 * @var array
	 */
	protected $item;

	/**
	 * Agdetagsearch form
	 *
	 * @var array
	 */
	protected $form;

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->state = $this->get('State');
		$this->item  = $this->get('Item');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');

			return false;
		}

		$this->addToolbar();

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
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user       = JFactory::getUser();
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));

		// Since we don't track these assets at the item level, use the category id.
		$canDo = JHelperContent::getActions('com_agdetagsearchs', 'category', $this->item->catid);

		JToolbarHelper::title(
			$isNew ? JText::_('COM_AGDETAGSEARCHS_MANAGER_AGDETAGSEARCH_NEW') :
			JText::_('COM_AGDETAGSEARCHS_MANAGER_AGDETAGSEARCH_EDIT'), 'link agdetagsearchs'
		);

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||(count($user->getAuthorisedCategories('com_agdetagsearchs', 'core.create')))))
		{
			JToolbarHelper::apply('agdetagsearch.apply');
			JToolbarHelper::save('agdetagsearch.save');
		}

		if (!$checkedOut && (count($user->getAuthorisedCategories('com_agdetagsearchs', 'core.create'))))
		{
			JToolbarHelper::save2new('agdetagsearch.save2new');
		}

		// If an existing item, can save to a copy.
		if (!$isNew && (count($user->getAuthorisedCategories('com_agdetagsearchs', 'core.create')) > 0))
		{
			JToolbarHelper::save2copy('agdetagsearch.save2copy');
		}

		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('agdetagsearch.cancel');
		}
		else
		{
			if ($this->state->params->get('save_history', 0) && $user->authorise('core.edit'))
			{
				JToolbarHelper::versions('com_agdetagsearchs.agdetagsearch', $this->item->id);
			}

			JToolbarHelper::cancel('agdetagsearch.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_COMPONENTS_AGDETAGSEARCHS_LINKS_EDIT');
	}
}
