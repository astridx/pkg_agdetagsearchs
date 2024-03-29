<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_agdetagsearchs
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$app = Factory::getApplication();
$input = $app->input;

$assoc = Associations::isEnabled();

$this->ignore_fieldsets = ['item_associations'];
$this->useCoreUI = true;

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate')
	->useScript('com_agdetagsearchs.scriptbackend');

$layout  = 'edit';
$tmpl = $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';
?>
<form action="<?php echo Route::_('index.php?option=com_agdetagsearchs&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="agdetagsearch-form" class="form-validate">

	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>
	<div>
		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'details']); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', empty($this->item->id) ? Text::_('COM_AGDETAGSEARCHS_NEW_AGDETAGSEARCH') : Text::_('COM_AGDETAGSEARCHS_EDIT_AGDETAGSEARCH')); ?>
		<div class="row">
			<div class="col-md-9">
				<div class="row">
					<div class="col-md-6">
						<?php echo $this->getForm()->renderField('fieldtype'); ?>
						<?php echo $this->getForm()->renderField('collapse'); ?>
						<?php echo $this->getForm()->renderField('tags'); ?>
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="card">
					<div class="card-body">
						<?php //echo LayoutHelper::render('joomla.edit.global', $this); ?>
						<?php echo $this->getForm()->renderField('published'); ?>
						<?php echo $this->getForm()->renderField('catid'); ?>
						<?php echo $this->getForm()->renderField('access'); ?>
						<?php echo $this->getForm()->renderField('language'); ?>
					</div>
				</div>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>
		
		<?php if ($assoc) : ?>
			<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'associations', Text::_('JGLOBAL_FIELDSET_ASSOCIATIONS')); ?>
			<?php echo $this->loadTemplate('associations'); ?>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php endif; ?>
		
		<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'publishing', Text::_('JGLOBAL_FIELDSET_PUBLISHING')); ?>
		<div class="row">
			<div class="col-md-6">
				<fieldset id="fieldset-publishingdata" class="options-form">
					<legend><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
					<div>
					<?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
					</div>
				</fieldset>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
