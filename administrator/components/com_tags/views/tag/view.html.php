<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * HTML View class for the Tags component
 *
 * @since  3.1
 */
class TagsViewTag extends JViewLegacy
{
	/**
	 * The JForm object
	 *
	 * @var  JForm
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * The model state
	 *
	 * @var  JObject
	 */
	protected $state;

	/**
	 * Flag if an association exists
	 *
	 * @var  boolean
	 */
	protected $assoc;

	/**
	 * The actions the user is authorised to perform
	 *
	 * @var    JObject
	 * @since  __DEPLOY_VERSION__
	 */
	protected $canDo;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');
		$this->canDo = JHelperContent::getActions('com_tags');
		$this->assoc = $this->get('Assoc');

		$input = JFactory::getApplication()->input;

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new JViewGenericdataexception(implode("\n", $errors), 500);
		}

		$input->set('hidemainmenu', true);
		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since  3.1
	 *
	 * @return void
	 */
	protected function addToolbar()
	{
		$user       = JFactory::getUser();
		$userId     = $user->get('id');
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Need to load the menu language file as mod_menu hasn't been loaded yet.
		$lang = JFactory::getLanguage();
		$lang->load('com_tags', JPATH_BASE, null, false, true)
		|| $lang->load('com_tags', JPATH_ADMINISTRATOR . '/components/com_tags', null, false, true);

		// Get the results for each action.
		$canDo = $this->canDo;
		$title = JText::_('COM_TAGS_BASE_' . ($isNew ? 'ADD' : 'EDIT') . '_TITLE');

		/**
		 * Prepare the toolbar.
		 * If it is new we get: `badge badge-add add`
		 * else we get `badge badge-edit edit`
		 */
		JToolbarHelper::title($title, 'badge badge-' . ($isNew ? 'add add' : 'edit edit'));

		// For new records, check the create permission.
		if ($isNew)
		{
			JToolbarHelper::saveGroup(
				[
					['apply', 'tag.apply'],
					['save', 'tag.save'],
					['save2new', 'tag.save2new']
				],
				'btn-success'
			);

			JToolbarHelper::cancel('tag.cancel');
		}

		// If not checked out, can save the item.
		else
		{
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_user_id == $userId);

			$toolbarButtons = [];

			// Can't save the record if it's checked out and editable
			if (!$checkedOut && $itemEditable)
			{
				$toolbarButtons[] = ['apply', 'tag.apply'];
				$toolbarButtons[] = ['save', 'tag.save'];
	
				if ($canDo->get('core.create'))
				{
					$toolbarButtons[] = ['save2new', 'tag.save2new'];
				}
			}

			// If an existing item, can save to a copy.
			if ($canDo->get('core.create'))
			{
				$toolbarButtons[] = ['save2copy', 'tag.save2copy'];
			}

			JToolbarHelper::saveGroup(
				$toolbarButtons,
				'btn-success'
			);

			if (JComponentHelper::isEnabled('com_contenthistory') && $this->state->params->get('save_history', 0) && $itemEditable)
			{
				JToolbarHelper::versions('com_tags.tag', $this->item->id);
			}

			JToolbarHelper::cancel('tag.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_COMPONENTS_TAGS_MANAGER_EDIT');
		JToolbarHelper::divider();
	}
}
