<?php
/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2018 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Agdetagsearchs Component Category Tree.
 *
 * @since  1.6
 */
class AgdetagsearchsCategories extends JCategories
{
	/**
	 * Constructor
	 *
	 * @param   array  $options  Array of options
	 *
	 * @since   1.6
	 */
	public function __construct($options = [])
	{
		$options['table'] = '#__agdetagsearchs';
		$options['extension'] = 'com_agdetagsearchs';

		parent::__construct($options);
	}
}
