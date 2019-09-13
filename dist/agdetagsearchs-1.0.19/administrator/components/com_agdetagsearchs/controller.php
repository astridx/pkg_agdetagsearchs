<?php
/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2018 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Agdetagsearchs Main Controller
 *
 * @since  1.5
 */
class AgdetagsearchsController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cacheable  If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types,
	 *                               for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JControllerLegacy  This object to support chaining.
	 *
	 * @since   1.5
	 */
	public function display($cacheable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT . '/helpers/agdetagsearchs.php';

		$view   = $this->input->get('view', 'agdetagsearchs');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		// Check for edit form.
		if ($view == 'agdetagsearch' && $layout == 'edit' && !$this->checkEditId('com_agdetagsearchs.edit.agdetagsearch', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_agdetagsearchs&view=agdetagsearchs', false));

			return false;
		}

		return parent::display();
	}
}
