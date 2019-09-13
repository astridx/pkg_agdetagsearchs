<?php
/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2018 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Agdetagsearchs Component Controller
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
	 * @return  AgdetagsearchsController  This object to support chaining.
	 *
	 * @since   1.5
	 */
	public function display($cacheable = false, $urlparams = false)
	{
		// Huh? Why not just put that in the constructor?
		$cacheable = true;

		/**
		 * Set the default view name and format from the Request.
		 * Note we are using w_id to avoid collisions with the router and the return page.
		 * Frontend is a bit messier than the backend.
		 */
		$id    = $this->input->getInt('w_id');
		$vName = $this->input->get('view', 'category');
		$this->input->set('view', $vName);

		if (JFactory::getUser()->id ||($this->input->getMethod() == 'POST' && $vName == 'categories'))
		{
			$cacheable = false;
		}

		$safeurlparams = array(
			'id'               => 'INT',
			'limit'            => 'UINT',
			'limitstart'       => 'UINT',
			'filter_order'     => 'CMD',
			'filter_order_Dir' => 'CMD',
			'lang'             => 'CMD'
		);

		// Check for edit form.
		if ($vName == 'form' && !$this->checkEditId('com_agdetagsearchs.edit.agdetagsearch', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}

		return parent::display($cacheable, $safeurlparams);
	}

	/**
	 * agdetagsearch
	 * This tasks is important for redirect back
	 * If we use post the browser back button will force a reload of the page
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function agdetagsearch()
	{
		$app = JFactory::getApplication();
		$values = $app->input->getArray($vars = array(), $datasource = null, $defaultFilter = 'unknown');
		$post = array();

		foreach ($values as $key => $value)
		{
			$post[$key] = $value;
		}

		// The Itemid from the request, we will use this if it's a search page or if there is no search page available
		$post['Itemid'] = $this->input->getInt('Itemid');

		// Set Itemid id for links from menu
		$app  = JFactory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getItem($post['Itemid']);

		// The requested Item is not a search page so we need to find one
		if ($item && ($item->component !== 'com_agdetagsearchs' || $item->query['view'] !== 'category'))
		{
			// Get item based on component, not link. link is not reliable.
			$item = $menu->getItems('component', 'com_agdetagsearchs', true);

			// If we found a search page, use that.
			if (!empty($item))
			{
				$post['Itemid'] = $item->id;
			}
		}

		unset($post['task'], $post['submit']);

		$uri = JUri::getInstance();

		if ($post['agdetagsearchbutton'] === 'submit')
		{
			$uri->setQuery($post);
		}

		$uri->setVar('option', 'com_agdetagsearchs');

		$redirect_url = JRoute::_($uri->toString(array('scheme', 'path', 'host', 'query', 'fragment')), false);

		if ($post['agdetagsearchbutton'] === 'submit')
		{
			$redirect_url = $redirect_url . '#agdetagsearch-resultlist';
		}
		else
		{
			$redirect_url = $redirect_url . '#agdetagsearchform';
		}

		$this->setRedirect($redirect_url);
	}
}
