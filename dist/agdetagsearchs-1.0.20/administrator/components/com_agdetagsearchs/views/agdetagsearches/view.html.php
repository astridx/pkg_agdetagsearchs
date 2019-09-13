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
class AgdetagsearchsViewAgdetagsearches extends JViewLegacy
{

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$app =& JFactory::getApplication();
		$app->redirect('index.php?option=com_agdetagsearchs&view=agdetagsearchs');
	}
}
