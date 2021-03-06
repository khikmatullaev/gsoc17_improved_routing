<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Installer.packageinstaller
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

$token   = JSession::getFormToken();
$return  = JFactory::getApplication()->input->getBase64('return');
$maxSize = JFilesystemHelper::fileUploadMaxSize();
?>

<legend><?php echo JText::_('PLG_INSTALLER_PACKAGEINSTALLER_UPLOAD_INSTALL_JOOMLA_EXTENSION'); ?></legend>

<hr>

<div id="uploader-wrapper">
	<div id="dragarea">
		<div id="dragarea-content" class="text-center">
			<p>
				<span id="upload-icon" class="icon-upload" aria-hidden="true"></span>
			</p>
			<p class="lead">
				<?php echo JText::_('PLG_INSTALLER_PACKAGEINSTALLER_DRAG_FILE_HERE'); ?>
			</p>
			<p>
				<button id="select-file-button" type="button" class="btn btn-success">
					<span class="icon-copy" aria-hidden="true"></span>
					<?php echo JText::_('PLG_INSTALLER_PACKAGEINSTALLER_SELECT_FILE'); ?>
				</button>
			</p>
			<p>
				<?php echo JText::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', $maxSize); ?>
			</p>
		</div>
	</div>
</div>

<div id="legacy-uploader" style="display: none;">
	<div class="control-group">
		<label for="install_package" class="control-label"><?php echo JText::_('PLG_INSTALLER_PACKAGEINSTALLER_EXTENSION_PACKAGE_FILE'); ?></label>
		<div class="controls">
			<input class="form-control-file" id="install_package" name="install_package" type="file">
			<small class="form-text text-muted"><?php echo JText::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', $maxSize); ?></small>
		</div>
	</div>
	<div class="form-actions">
		<button class="btn btn-primary" type="button" id="installbutton_package" onclick="Joomla.submitbuttonpackage()">
			<?php echo JText::_('PLG_INSTALLER_PACKAGEINSTALLER_UPLOAD_AND_INSTALL'); ?>
		</button>
	</div>

	<input id="installer-return" name="return" type="hidden" value="<?php echo $return; ?>">
	<input id="installer-token" name="return" type="hidden" value="<?php echo $token; ?>">
</div>
