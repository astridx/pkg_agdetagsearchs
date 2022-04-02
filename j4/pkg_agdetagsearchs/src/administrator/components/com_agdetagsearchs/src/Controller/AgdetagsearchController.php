<?php
/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2018 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgdetagsearchsNamespace\Component\Agdetagsearchs\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;

/**
 * Controller for a single agdetagsearch
 *
 * @since  __BUMP_VERSION__
 */
class AgdetagsearchController extends FormController
{

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_list = "agdetagsearchs";

	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = "agdetagsearchs";

	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean   True if successful, false otherwise and internal error is set.
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function batch($model = null)
	{
		$this->checkToken();

		$model = $this->getModel('Agdetagsearch', 'Administrator', array());

		// Preset the redirect
		$this->setRedirect(Route::_('index.php?option=com_agdetagsearchs&view=agdetagsearchs' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
}
