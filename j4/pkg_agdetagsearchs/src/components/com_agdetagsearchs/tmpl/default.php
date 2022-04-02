<?php
/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2018 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('formbehavior.chosen', 'select');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

$params    = $this->params;
$category  = $this->get('category');
$extension = $category->extension;
$canEdit   = $params->get('access-edit');
$className = substr($extension, 4);
$headingform_type = $params->get('headingform_type', 'h2');


$image_button_form_up =	$params->get('image_button_form_up', 'media/com_agdetagsearchs/images/arrows/circle_down.png');
$image_button_form_down =	$params->get('image_button_form_down', 'media/com_agdetagsearchs/images/arrows/circle_left.png');

$html[] = "<script>"
. "jQuery(function ($) {"
. "$( document ).ready(function() {"
. "if($('.toggletarget_form').is(':visible'))"
. "{"
. "$('.toggle_symbol_form').attr('src', '" . JURI::base() . $image_button_form_up . "')"
. "} else {"
. "$('.toggle_symbol_form').attr('src', '" . JURI::base() . $image_button_form_down . "')"
. "}"
. "});"
. "$('.toggle_form').on('click', function () {"
. "$('.toggletarget_form').slideToggle('slow', function(){"
. "if($('.toggletarget_form').is(':visible'))"
. "{"
. "Cookies.set('toggletarget_form', 'true', { expires: 1 });"
. "$('.toggle_symbol_form').attr('src', '" . JURI::base() . $image_button_form_up . "')"
. "} else {"
. "Cookies.set('toggletarget_form', 'false', { expires: 1 });"
. "$('.toggle_symbol_form').attr('src', '" . JURI::base() . $image_button_form_down . "')"
. "}"
. "});"
. "});"
. "});"
. "</script>";



$formBorder = '';
if ($params->get('show_border', 0)) {
	$formBorder = 'border: solid black thin;';
}
$formBackgroundColor = $params->get('formBackgroundColor', '');
$buttonColor_Symbol = $params->get('buttonColor_Symbol', '');
$buttonColor =	$params->get('buttonColor', '');

$dispatcher = JEventDispatcher::getInstance();

$category->text = $category->description;
$dispatcher->trigger('onContentPrepare', [$extension . '.categories', &$category, &$params, 0]);
$category->description = $category->text;

$results = $dispatcher->trigger('onContentAfterTitle', [$extension . '.categories', &$category, &$params, 0]);
$afterDisplayTitle = trim(implode("\n", $results));

$results = $dispatcher->trigger('onContentBeforeDisplay', [$extension . '.categories', &$category, &$params, 0]);
$beforeDisplayContent = trim(implode("\n", $results));

$results = $dispatcher->trigger('onContentAfterDisplay', [$extension . '.categories', &$category, &$params, 0]);
$afterDisplayContent = trim(implode("\n", $results));

/**
 * This will work for the core components but not necessarily for other components
 * that may have different pluralisation rules.
 */
if (substr($className, -1) === 's') {
	$className = rtrim($className, 's');
}
$tagsData = $category->tags->itemTags;
?>
<div id="agdetagsearchform" style="<?php echo $formBorder; ?>;background-color:<?php echo $formBackgroundColor; ?>">
	<div class="<?php echo $className .'-category ' . $this->pageclass_sfx; ?>">
		<?php if ($params->get('show_page_heading')) : ?>
			<h1>
				<?php
				echo $this->escape($params->get('page_heading'));
				?>
			</h1>
		<?php endif; ?>

		<?php if ($params->get('show_category_title', 1)) : ?>
			<div class=" flex">
			<<?php echo $headingform_type; ?> class="inlineformheading toggle_form">
			<?php echo JHtml::_('content.prepare', $category->title, '', $extension . '.category.title'); ?>
			</<?php echo $headingform_type; ?>>
			<img src="<?php echo JURI::base() . $image_button_form_down ?>" class=" toggle_form toggle_symbol_form" />
			</div>
		<?php endif; ?>
		<?php echo $afterDisplayTitle; ?>

		<?php if ($params->get('show_cat_tags', 1)) : ?>
			<?php echo JLayoutHelper::render('joomla.content.tags', $tagsData); ?>
		<?php endif; ?>

		<?php if ($beforeDisplayContent || $afterDisplayContent || $params->get('show_description', 1) || $params->def('show_description_image', 1)) : ?>
			<div class="category-desc">
				<?php if ($params->get('show_description_image') && $category->getParams()->get('image')) : ?>
					<img src="<?php echo $category->getParams()->get('image'); ?>" alt="<?php echo htmlspecialchars($category->getParams()->get('image_alt'), ENT_COMPAT, 'UTF-8'); ?>"/>
				<?php endif; ?>
				<?php echo $beforeDisplayContent; ?>
				<?php if ($params->get('show_description') && $category->description) : ?>
					<?php echo JHtml::_('content.prepare', $category->description, '', $extension . '.category.description'); ?>
				<?php endif; ?>
				<?php echo $afterDisplayContent; ?>
				<div class="clr"></div>
			</div>
		<?php endif; ?>

		<div class="agdetagsearch_items toggletarget_form">
		<?php echo $this->loadTemplate('items'); ?>
		</div>

		<div class="agdetagsearch_result toggletarget_form">

			<h3 id="agdetagsearch-resultlist">
				<?php echo JText::_('COM_AGDETAGSEARCHS_RESULT_HEADLINE'); ?>
			</h3>
			<?php echo $this->loadTemplate('result'); ?>
		</div>
	</div>
</div>
<?php echo implode("\n", $html); ?>