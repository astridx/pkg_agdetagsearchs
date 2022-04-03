<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agdetagsearchs
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgdetagsearchsNamespace\Component\Agdetagsearchs\Site\View\Category;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\CategoryView;
use AgdetagsearchsNamespace\Component\Agdetagsearchs\Site\Helper\RouteHelper;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * HTML View class for the Agdetagsearchs component
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends CategoryView
{

	/**
	 * tag ids
	 *
	 * @var    array
	 * @since  3.7
	 */
	protected $tagids;

	/**
	 * pagination for the result data
	 *
	 * @var    array
	 * @since  3.7
	 */
	protected $pagination;

	/**
	 * result sum one array item for one field
	 *
	 * @var    array
	 * @since  3.7
	 */
	protected $results;

	/**
	 * resultSum of all fields
	 *
	 * @var    array
	 * @since  3.7
	 */
	protected $resultSum;

	/**
	 * resultSum of all fields
	 *
	 * @var    array
	 * @since  3.7
	 */
	protected $resultSumUnique;


	/**
	 * @var    string  The name of the extension for the category
	 * @since  __BUMP_VERSION__
	 */
	protected $extension = 'com_agdetagsearchs';

	/**
	 * @var    string  Default title to use for page title
	 * @since  __BUMP_VERSION__
	 */
	protected $defaultPageTitle = 'COM_AGDETAGSEARCH_DEFAULT_PAGE_TITLE';

	/**
	 * @var    string  The name of the view to link individual items to
	 * @since  __BUMP_VERSION__
	 */
	protected $viewName = 'category';

	/**
	 * Run the standard Joomla plugins
	 *
	 * @var    boolean
	 * @since  __BUMP_VERSION__
	 */
	protected $runPlugins = true;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		parent::commonCategoryDisplay();

		
		$this->state = $this->get('state');

		$this->resultSumUnique = $this->get('data');
		$this->total = $this->get('total');
		$this->pagination->hideEmptyLimitstart = true;

		foreach ($this->items as $item) {
			$item->slug = $item->id;
			$temp = $item->params;
			$item->params = clone $this->params;
			$item->params->merge($temp);
		}

		$file = JPATH_ROOT . '/media/com_agdetagsearchs/js/disableifnotpossible.js';

		if ($this->params->get('disable_formfields_if_no_result', '0')) {
			// Datei erstellen oder skript einbinden
			if (!File::exists($file)) {
				// We have to create the file
				$htmldisablefields = $this->_getDisableQuery();
				$content = implode("\n", $htmldisablefields);

				// Write the file to disk
				if (!File::write($file, $content)) {
					throw new RuntimeException(JText::_('COM_AGDETAGSEARCHS_ERROR_WRITE_FAILED'));
				}
			}
			HTMLHelper::_('jquery.framework');
		} else {
			// It is not activated. So we can tidy up.
			File::delete($file);
		}

		return parent::display($tpl);
	}


	/**
	 * Counts the jquery for disabling
	 *
	 * @return  array  A empty array, if not selected - else jquery for disabe
	 */
	protected function _getDisableQuery()
	{
		$params = $this->params;

		$htmldisablefields[] = "";

		if ($params->get('disable_formfields_if_no_result', '0')) {
			$agde_tagidarray = [];

			foreach ($this->items as $i => $item) {
				$tagsData = $item->tags->getItemTags('com_agdetagsearchs.agdetagsearch', $item->id);

				foreach ($tagsData as $tag) {
					$tagid = $tag->tag_id;

					if (!in_array($tagid, $agde_tagidarray, true)) {
						array_push($agde_tagidarray, $tagid);
					}
				}
			}

			$array_with_associated_tags = [];

			foreach ($agde_tagidarray as $t => $tag) {
				$model = $this->getModel();
				$resultsForTag = $model->getTagdata($tag);
				$array_with_associated_tags[$tag] = [];

				foreach ($resultsForTag as $t => $result) {
					$resultsForTagData = $item->tags->getItemTags($result->type_alias, $result->content_item_id);

					foreach ($resultsForTagData as $d => $data) {
						$data = $data->id;

						if (!in_array($data, $array_with_associated_tags[$tag], true)) {
							array_push($array_with_associated_tags[$tag], $data);
						}
					}
				}
			}

			$model = $this->getModel();
			$resultsForAllTag = $model->getTagdata($agde_tagidarray);

			$array_with_associated_tags_all = [];

			foreach ($resultsForAllTag as $t => $result) {
				$array_with_associated_tags_all[$t] = [];
				$resultsForAllTagData = $item->tags->getItemTags($result->type_alias, $result->content_item_id);

				foreach ($resultsForAllTagData as $d => $data) {
					$data = $data->id;

					if (!in_array($data, $array_with_associated_tags_all[$t], true)) {
						array_push($array_with_associated_tags_all[$t], $data);
					}
				}
			}

			// $htmldisablefields[] .= "<script>";
			$htmldisablefields[] = "jQuery(function ($) {";

			$htmldisablefields[] .= "$('#submitbutton').click(function(){";
			$htmldisablefields[] .= "$('input').each(function() {";
			$htmldisablefields[] .= "$(this).removeAttr('disabled');";
			$htmldisablefields[] .= "});";
			$htmldisablefields[] .= "});";

			$htmldisablefields[] .= "$('#resetbutton').click(function(){";
			$htmldisablefields[] .= "$('input').each(function() {";
			$htmldisablefields[] .= "$(this).removeAttr('disabled');";
			$htmldisablefields[] .= "$(this).prop('checked', false );";
			$htmldisablefields[] .= "});";
			$htmldisablefields[] .= "});";

			$htmldisablefields[] .= "$('document').ready(function() {";
			$htmldisablefields[] .= "$('input').each(function() {";
			$htmldisablefields[] .= "$(this).removeAttr('disabled');";

			foreach ($array_with_associated_tags as $main => $maintag) {
				if (empty($maintag)) {
					$htmldisablefields[] .= "if ($(this).val() == '" . $main . "') {";
					$htmldisablefields[] .= "$(this).attr('disabled', true);";
					$htmldisablefields[] .= "}";
				}
			}

			$htmldisablefields[] .= "});";
			$htmldisablefields[] .= "});";

			$htmldisablefields[] .= "$('input').change(function() {";
			$htmldisablefields[] .= "$.foo_changed = $(this);";
			$htmldisablefields[] .= "$('input').each(function() {";
			$htmldisablefields[] .= "$(this).removeAttr('disabled');";
			$htmldisablefields[] .= "});";
			$htmldisablefields[] .= "if ($(this).attr('checked') == 'checked') {";
			$htmldisablefields[] .= "$('input').each(function() {";
			$htmldisablefields[] .= "$.foo_status = 'true';";

			foreach ($array_with_associated_tags as $main => $maintag) {
				foreach ($maintag as $assoc => $assoctag) {
					$htmldisablefields[] .= "if ($(this).val() == '" . $assoctag . "' && $.foo_changed.val() == '" . $main . "') {";
					$htmldisablefields[] .= "$.foo_status = 'false';";
					$htmldisablefields[] .= "}";
				}
			}

			$htmldisablefields[] .= "if ($.foo_status == 'true') {";
			$htmldisablefields[] .= "$(this).attr('disabled', true);";
			$htmldisablefields[] .= "}";
			$htmldisablefields[] .= "});";
			$htmldisablefields[] .= "$.foo_selected = [];";
			$htmldisablefields[] .= "$('input').each(function() {";
			$htmldisablefields[] .= "if ($(this).is(':checked')) {";
			$htmldisablefields[] .= "$.foo_selected.push($(this).val());";
			$htmldisablefields[] .= "}";
			$htmldisablefields[] .= "});";

			foreach ($array_with_associated_tags as $main => $maintag) {
				foreach ($maintag as $assoc => $assoctag) {
					$htmldisablefields[] .= "if ($.foo_changed.val() == '" . $main . "') {";
					$htmldisablefields[] .= "$('input').each(function() {";
					$htmldisablefields[] .= "if ($(this).val() == '" . $assoctag . "') {";
					$htmldisablefields[] .= "$.array_with_associated_tags_all = " . json_encode($array_with_associated_tags_all) . ";";
					$htmldisablefields[] .= "jQuery.each($.array_with_associated_tags_all, "
							. "function(index_array_with_associated_tags_all, item_array_with_associated_tags_all) {";
					$htmldisablefields[] .= "jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP, item_arrayFromPHP) {";
					$htmldisablefields[] .= "if ('" . $assoctag . "' == item_arrayFromPHP) {";
					$htmldisablefields[] .= "jQuery.each("
						. "item_array_with_associated_tags_all, function(index_arrayFromPHP_inner, item_arrayFromPHP_inner) {";
					$htmldisablefields[] .= "if (index_arrayFromPHP == index_arrayFromPHP_inner) {";
					$htmldisablefields[] .= "jQuery.each($.foo_selected, function(index_selected, item_selected) {";
					$htmldisablefields[] .= "if(jQuery.inArray(item_selected,item_array_with_associated_tags_all) == -1){;";
					$htmldisablefields[] .= "$.array_with_associated_tags_all[index_array_with_associated_tags_all]=[];";
					$htmldisablefields[] .= "}";
					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "}";
					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "}";
					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "$.foo_this_should_be_disables = 'true';";
					$htmldisablefields[] .= "jQuery.each($.array_with_associated_tags_all, "
							. "function(index_array_with_associated_tags_all_end, item_array_with_associated_tags_all_end) {";
					$htmldisablefields[] .= "if(jQuery.inArray('" . $assoctag . "',item_array_with_associated_tags_all_end) > -1 ){;";
					$htmldisablefields[] .= "$.foo_this_should_be_disables = 'false';";
					$htmldisablefields[] .= "}";
					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "if ($.foo_this_should_be_disables == 'true') {;";
					$htmldisablefields[] .= "$('input').each(function() {";
					$htmldisablefields[] .= "if ($(this).val() == '" . $assoctag . "') {";
					$htmldisablefields[] .= "$(this).attr('disabled', true);";
					$htmldisablefields[] .= "}";
					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "}";
					$htmldisablefields[] .= "}";
					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "}";
				}
			}

			$htmldisablefields[] .= "} else {";
			$htmldisablefields[] .= "$.foo_one_selected = 'false';";
			$htmldisablefields[] .= "$('input').each(function() {";
			$htmldisablefields[] .= "if ($(this).is(':checked')) {";
			$htmldisablefields[] .= "$.foo_one_selected = 'true';";
			$htmldisablefields[] .= "}";
			$htmldisablefields[] .= "});";
			$htmldisablefields[] .= "if ($.foo_one_selected == 'true') {";
			$htmldisablefields[] .= "$('input').each(function() {";
			$htmldisablefields[] .= "$.foo_status = 'true';";

			foreach ($array_with_associated_tags as $main => $maintag) {
				foreach ($maintag as $assoc => $assoctag) {
					$htmldisablefields[] .= "if ($(this).val() == '" . $assoctag . "' && $.foo_changed.val() == '" . $main . "') {";
					$htmldisablefields[] .= "$.foo_status = 'false';";
					$htmldisablefields[] .= "}";
				}
			}

			$htmldisablefields[] .= "if ($.foo_status == 'true') {";
			$htmldisablefields[] .= "$(this).attr('disabled', true);";
			$htmldisablefields[] .= "}";
			$htmldisablefields[] .= "});";
			$htmldisablefields[] .= "$.foo_selected = [];";
			$htmldisablefields[] .= "$('input').each(function() {";
			$htmldisablefields[] .= "if ($(this).is(':checked')) {";
			$htmldisablefields[] .= "$.foo_selected.push($(this).val());";
			$htmldisablefields[] .= "}";
			$htmldisablefields[] .= "});";

			foreach ($array_with_associated_tags as $main => $maintag) {
				foreach ($maintag as $assoc => $assoctag) {
					$htmldisablefields[] .= "if ($.foo_changed.val() == '" . $main . "') {";
					$htmldisablefields[] .= "$('input').each(function() {";
					$htmldisablefields[] .= "if ($(this).val() == '" . $assoctag . "') {";

					$htmldisablefields[] .= "$.array_with_associated_tags_all = " . json_encode($array_with_associated_tags_all) . ";";
					$htmldisablefields[] .= "jQuery.each($.array_with_associated_tags_all, "
							. "function(index_array_with_associated_tags_all, item_array_with_associated_tags_all) {";
					$htmldisablefields[] .= "jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP, item_arrayFromPHP) {";
					$htmldisablefields[] .= "if ('" . $assoctag . "' == item_arrayFromPHP) {";
					$htmldisablefields[] .= "jQuery.each("
						. "item_array_with_associated_tags_all, function(index_arrayFromPHP_inner, item_arrayFromPHP_inner) {";
					$htmldisablefields[] .= "if (index_arrayFromPHP == index_arrayFromPHP_inner) {";
					$htmldisablefields[] .= "jQuery.each($.foo_selected, function(index_selected, item_selected) {";
					$htmldisablefields[] .= "if(jQuery.inArray(item_selected,item_array_with_associated_tags_all) == -1){;";
					$htmldisablefields[] .= "$.array_with_associated_tags_all[index_array_with_associated_tags_all]=[];";
					$htmldisablefields[] .= "}";

					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "}";
					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "}";
					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "$.foo_this_should_be_disables = 'true';";
					$htmldisablefields[] .= "jQuery.each($.array_with_associated_tags_all, "
							. "function(index_array_with_associated_tags_all_end, item_array_with_associated_tags_all_end) {";
					$htmldisablefields[] .= "if(jQuery.inArray('" . $assoctag . "',item_array_with_associated_tags_all_end) > -1 ){;";
					$htmldisablefields[] .= "$.foo_this_should_be_disables = 'false';";
					$htmldisablefields[] .= "}";
					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "if ($.foo_this_should_be_disables == 'true') {;";
					$htmldisablefields[] .= "$('input').each(function() {";
					$htmldisablefields[] .= "if ($(this).val() == '" . $assoctag . "') {";
					$htmldisablefields[] .= "$(this).attr('disabled', true);";
					$htmldisablefields[] .= "}";
					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "}";
					$htmldisablefields[] .= "}";
					$htmldisablefields[] .= "});";
					$htmldisablefields[] .= "}";
				}
			}

			$htmldisablefields[] .= "} else {";
			$htmldisablefields[] .= "}";
			$htmldisablefields[] .= "}";
			$htmldisablefields[] .= "});";
			$htmldisablefields[] .= "});";

			// $htmldisablefields[] .= "</script>";
		}

		return $htmldisablefields;
	}
}
