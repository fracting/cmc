<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

/**
 * Class CmcViewUser
 *
 * @since  1.0
 */
class CmcViewUser extends JViewLegacy
{
	/**
	 * Displays the view
	 *
	 * @param   string  $tpl  - custom template
	 *
	 * @return mixed|void
	 */
	public function display($tpl = null)
	{
		$this->user = $this->get('Item');

		if (!$this->user->get('id'))
		{
			// Create new empty list item
			$this->user = JTable::getInstance('users', 'CmcTable');
		}

		$this->form = $this->get('Form');

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Ads the toolbar buttons
	 *
	 * @return void
	 */
	public function addToolbar()
	{
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_CMC_EDIT_USER'), 'users');
		JToolBarHelper::save('user.save');
		JToolBarHelper::apply('user.apply');
		JToolBarHelper::cancel('user.cancel');
		JToolBarHelper::help('screen.users', true);
	}
}
