<?php
/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2018 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

$wa->registerAndUseScript('scriptfrontend','com_agdetagsearchs/scriptfrontend.js', [], ['defer' => true], []);
$wa->registerAndUseScript('cookie','com_agdetagsearchs/js.cookie.js', [], ['defer' => true], []);
$wa->registerAndUseStyle('stylefrontend', 'com_agdetagsearchs/stylefrontend.css', [], [], []);


// Create a shortcut for params.
$params    = $this->params;

$image_button_formfield_up = $params->get('image_button_formfield_up', 'media/com_agdetagsearchs/images/arrows/circle_down.png');
$image_button_formfield_down =	$params->get('image_button_formfield_down', 'media/com_agdetagsearchs/images/arrows/circle_left.png');

$n = count($this->items);
$columns = 3;
$spanClass = 'span4';
$agtagscolumnlimit_fields = 3;

if ($this->state->get('agtagscolumnlimit_fields')) {
	$agtagscolumnlimit_fields = $this->state->get('agtagscolumnlimit_fields');
}

if ($agtagscolumnlimit_fields == 1) {
	$spanClass = 'span12';
	$columns = 1;
}

if ($agtagscolumnlimit_fields == 2) {
	$spanClass = 'span6';
	$columns = 2;
}

if ($agtagscolumnlimit_fields == 3) {
	$spanClass = 'span4';
	$columns = 3;
}

if ($agtagscolumnlimit_fields == 4) {
	$spanClass = 'span3';
	$columns = 4;
}

if ($n < $columns) {
	$columns = $n;
}

if ($columns != 0) {
	$rows = ceil($n/$columns);
} else {
	$rows = 0;
}

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

// variable for collection the Js for the toggle
$html = [];
?>

<?php $class_for_columns_of_formfields = ''; ?>
<?php if ($this->state->get('agtagscolumnlimit_fields') == 3) : ?>
<?php $class_for_columns_of_formfields = 'id="agdesearch_ul_three"'; ?>
<?php elseif ($this->state->get('agtagscolumnlimit_fields') == 2) : ?>
<?php $class_for_columns_of_formfields = 'id="agdesearch_ul_two"'; ?>
<?php else : ?>
<?php $class_for_columns_of_formfields = ''; ?>
<?php endif; ?>

<?php if (empty($this->items)) : ?>
<p> <?php echo JText::_('COM_AGDETAGSEARCHS_NO_AGDETAGSEARCHS'); ?></p>
<?php else : ?>
<form action="<?php echo JRoute::_('index.php?option=com_agdetagsearchs'); ?>" method="post" name="adminForm"
    id="adminForm">

    <!-- Start a fluid row layout with columns set in the variable $columns -->
    <div class="items-row row-fluid category list-condensed divaroundformfield">
        <?php foreach ($this->items as $i => $item) : ?>
        <?php if ($rows > 1 && $i !== 0 && $i % $columns == 0) :?>
    </div>
    <div class="items-row row-fluid category list-condensed divaroundformfield">
        <?php endif; ?>


        <?php // jquery for toggle?>
        <?php
	// if the cookie is set
		$display = '';
		$element = 'toggletarget_' . $item->alias;
		if (isset($_COOKIE[$element])) {
			if (filter_input(INPUT_COOKIE, $element, FILTER_SANITIZE_SPECIAL_CHARS) == 'true') {
				// Do nothing
			} else {
				$display = 'style="display:none;"';
			}
		} else if ($item->collapse == '1' && $params->get('show_headings')) {
			$display = 'style="display:none;"';
		}
		?>
        <?php
		$html[] = "<script>"
		. "jQuery(function ($) {"
		. "$( document ).ready(function() {"
		. "if($('.toggletarget_" . $item->alias . "').is(':visible'))"
		. "{"
		. "$('.toggle_symbol_" . $item->alias . "').attr('src', '" . JURI::base() . $image_button_formfield_up . "')"
		. "} else {"
		. "$('.toggle_symbol_" . $item->alias . "').attr('src', '" . JURI::base() . $image_button_formfield_down . "')"
		. "}"
		. "});"
		. "$('.toggle_" . $item->alias . "').on('click', function () { "
		. "$('.toggletarget_" . $item->alias . "').fadeToggle('slow', function(){"
		. "if($('.toggletarget_" . $item->alias . "').is(':visible'))"
		. "{"
		. "Cookies.set('toggletarget_" . $item->alias . "', 'true', { expires: 1 });"
		. "$('.toggle_symbol_" . $item->alias . "').attr('src', '" . JURI::base() . $image_button_formfield_up . "')"
		. "} else {"
		. "Cookies.set('toggletarget_" . $item->alias . "', 'false', { expires: 1 });"
		. "$('.toggle_symbol_" . $item->alias . "').attr('src', '" . JURI::base() . $image_button_formfield_down . "')"
		. "}"
		. "});"
		. "});"
		. "});"
		. "</script>";
		?>

        <?php if (in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
        <?php if ($this->items[$i]->state == 0) : ?>
        <!-- was li-->
        <div
            class="<?php echo $spanClass; ?> system-unpublished list-striped cat-list cat-list-row<?php echo $i % 2; ?>">
            <?php else : ?>
            <div class="<?php echo $spanClass; ?> cat-list list-striped cat-list-row<?php echo $i % 2; ?>">
                <?php endif; ?>


                <?php // Images plus title begin ?>
                <?php	// Images plus title
		// We need the image twice because float left and none should displayed above the tile
		// and float right shoud display after the title so that the
		// toggle symbol is at the most left positon?>
                <?php  if ($params->get('show_headings', 1)) : ?>
                <div class="agdeagtagsearchstoggle toggle_<?php echo $item->alias; ?>">

                    <?php 
					if (isset($item->images)) {
						$images = json_decode($item->images); 
					}
					?>

                    <?php  if (isset($images->image_first) and ($images->float_first == 'left' or $images->float_first == 'none') and !empty($images->image_first)) : ?>
                    <?php $imgfloat = (empty($images->float_first)) ? $params->get('float_first') : $images->float_first; ?>
                    <div class="img-intro-float-<?php echo htmlspecialchars($imgfloat); ?>">
                        <img src="<?php echo htmlspecialchars($images->image_first); ?>"
                            alt="<?php echo htmlspecialchars($images->image_first_alt); ?>" />
                    </div>
                    <?php endif; ?>


                    <div class="list-title flex">
                        <?php
				$headings_type = $params->get('headings_type', 'h3');
				echo
				'<'. $headings_type . ' class="inlineformfieldheadings">' . $this->escape($item->title) .
				'</'. $headings_type . '>' .
				'<img src="' . JURI::base() . $image_button_formfield_down . '" class="toggle_symbol toggle_symbol_field toggle_symbol_'.$item->alias.'" />';
				?>
                    </div>

                    <?php  if (isset($images->image_first) and ($images->float_first == 'right') and !empty($images->image_first)) : ?>
                    <?php $imgfloat = (empty($images->float_first)) ? $params->get('float_first') : $images->float_first; ?>
                    <div class="img-intro-float-<?php echo htmlspecialchars($imgfloat); ?>">
                        <img src="<?php echo htmlspecialchars($images->image_first); ?>"
                            alt="<?php echo htmlspecialchars($images->image_first_alt); ?>" />
                    </div>
                    <?php endif; ?>

                </div>

                <div class="agde_clear">
                </div>
                <?php else : //showheading?>
                <?php echo ""; // no title should be shown ?>
                <?php endif; //showheading ?>
                <?php // Images plus title ende ?>


                <?php
				$tagsData = [];
				if (isset($item->tags)) {
			$tagsData = $item->tags->getItemTags('com_agdetagsearchs.agdetagsearch', $item->id);
				} 
			$agde_searchtags = [];
			foreach ($tagsData as $tag) {
				$tagid = $tag->tag_id;

				$labelcontent ="";
				$tagname = $tag->title;
				$tagimageArray = json_decode($tag->images);
				$tagimagePath = JURI::Root() . $tagimageArray->image_intro;
				$tagimage = "<img class='imageinformfield' src='" . htmlspecialchars($tagimagePath) ."' title='" . $tagname ."' alt='" . $tagname . "'>";

				if ($params->get('show_tagimage', 'text') == 'both') {
					$labelcontent = $tagimage . '<br /><div style="margin-left:' . $params->get('show_tagimage_both_margin_left_for_text', '28') . 'px;font-size:' . $params->get('show_tagimage_both_fontsize', '13') . 'px;">' . $tagname . '</div><br />';
				} else if ($params->get('show_tagimage', 'text') == 'image') {
					$labelcontent = $tagimage;
				} else {
					$labelcontent = $tagname;
				}

				$agde_searchtags[] = HTMLHelper::_('select.option', $tagid, $labelcontent);
			}
			$postvalue = 'agdetagsearchs_' . $item->alias;
			$values = $this->state->get($postvalue);
			?>
                <div <?php echo $display; ?> class="<?php echo 'toggletarget_' . $item->alias ?>">
                    <?php if ($item->fieldtype == 'select') : ?>
                    <?php
				$list = HTMLHelper::_('select.radiolist', $agde_searchtags, 'agdetagsearchs_' . $item->alias . '[]', 'multiple class="inputbox"', 'value', 'text', $values);
				$list = str_replace('type="radio"', 'type="checkbox"', $list);
				echo $list;
				?>
                    <?php endif;?>
                    <?php if ($item->fieldtype == 'checkbox') : ?>
                    <?php
					if (isset($values[0])) {
						echo HTMLHelper::_('select.radiolist', $agde_searchtags, 'agdetagsearchs_' . $item->alias . '[]', 'multiple class="inputbox"', 'value', 'text', $values[0]);
					}
					?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="agde_clear"></div>


        <?php //SUBMITBUTTON START?>
        <div class="form-actions">
            <button id="submitbutton" name="agdetagsearchbutton" value="submit" class="btn hasTooltip"
                title="<?php echo HTMLHelper::_('tooltipText', 'COM_AGDETAGSEARCH_DEAGTAGSEARCH'); ?>">
                <span class="icon-search"></span>
                <?php echo JText::_('COM_AGDETAGSEARCH_SUBMIT'); ?>
            </button>
            <button id="resetbutton" name="agdetagresetbutton" value="reset" class="btn hasTooltip"
                title="<?php echo HTMLHelper::_('tooltipText', 'COM_AGDETAGSEARCH_DEAGTAGSRESET'); ?>">
                <span class="icon-backward"></span>
                <?php echo JText::_('COM_AGDETAGSEARCH_RESET'); ?>
            </button>
        </div>
        <?php //SUBMITBUTTON ENDE?>

        <?php //Redirect over task so that the back button of the browser works without refresh?>
        <input type="hidden" name="task" value="agdetagsearch" />
</form>
<div class="agde_clear">
    <hr style="margin-top:10%">
</div>

<?php endif; ?>

<?php echo implode("\n", $html); ?>