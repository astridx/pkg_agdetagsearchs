<?php
/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2018 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$app       = JFactory::getApplication();
$form      = $this->form;
$input     = $app->input;
$component = $input->getCmd('option', 'com_content');

$saveHistory = JComponentHelper::getParams($component)->get('save_history', 0);

$fields = [
	['parent', 'parent_id'],
	['published', 'state', 'enabled'],
	['category', 'catid'],
	'featured',
	'sticky',
	'access',
	'language',
	'note',
	'version_note',
];

$hiddenFields = [];

if (!$saveHistory) {
	$hiddenFields[] = 'version_note';
}

$html   = [];
$html[] = '<fieldset class="form-vertical">';

foreach ($fields as $field) {
	foreach ((array) $field as $f) {
		if ($form->getField($f)) {
			if (in_array($f, $hiddenFields)) {
				$form->setFieldAttribute($f, 'type', 'hidden');
			}

			$html[] = $form->renderField($f);
			break;
		}
	}
}

$html[] = '</fieldset>';

$fieldstring = implode('', $html);
$fieldstringwithoutCategory = str_replace('**Category**', JText::_('COM_AGDETAGSEARCHS_SEARCHFORM'), $fieldstring);

echo $fieldstringwithoutCategory;
