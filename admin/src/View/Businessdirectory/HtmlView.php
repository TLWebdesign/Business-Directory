<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				TLWebdesign 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.2
	@build			3rd April, 2025
	@created		1st April, 2025
	@package		Business Directory
	@subpackage		HtmlView.php
	@author			Tom van der Laan <https://tlwebdesign.nl>	
	@copyright		Copyright (C) 2025. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/
namespace JCB\Component\Businessdirectory\Administrator\View\Businessdirectory;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Document\Document;
use JCB\Component\Businessdirectory\Administrator\Helper\BusinessdirectoryHelper;
use JCB\Joomla\Utilities\StringHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Businessdirectory View class
 *
 * @since  1.6
 */
#[\AllowDynamicProperties]
class HtmlView extends BaseHtmlView
{
	/**
	 * View display method
	 *
	 * @return void
	 * @throws \Exception
	 * @since   1.6
	 */
	function display($tpl = null): void
	{
		// Assign data to the view
		$this->icons          = $this->get('Icons');
		$this->styles         = $this->get('Styles');
		$this->scripts        = $this->get('Scripts');
		$this->contributors   = BusinessdirectoryHelper::getContributors();

		// get the manifest details of the component
		$this->manifest = BusinessdirectoryHelper::manifest();

		// Set the toolbar
		$this->addToolBar();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode("\n", $errors), 500);
		}

		// Set the html view document stuff
		$this->_prepareDocument();

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function addToolbar(): void
	{
		$canDo = BusinessdirectoryHelper::getActions('businessdirectory');
		ToolbarHelper::title(Text::_('COM_BUSINESSDIRECTORY_DASHBOARD'), 'grid-2');

		// set help url for this view if found
		$this->help_url = BusinessdirectoryHelper::getHelpUrl('businessdirectory');
		if (StringHelper::check($this->help_url))
		{
			ToolbarHelper::help('COM_BUSINESSDIRECTORY_HELP_MANAGER', false, $this->help_url);
		}

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			ToolbarHelper::preferences('com_businessdirectory');
		}
	}

	/**
	 * Prepare some document related stuff.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function _prepareDocument(): void
	{
		// set page title
		$this->getDocument()->setTitle(Text::_('COM_BUSINESSDIRECTORY_DASHBOARD'));
		// add manifest to page JavaScript
		$this->getDocument()->addScriptDeclaration("var manifest = JSON.parse(" . json_encode($this->manifest) . ");", "text/javascript");
		// add styles
		foreach ($this->styles as $style)
		{
			Html::_('stylesheet', $style, ['version' => 'auto']);
		}
		// add scripts
		foreach ($this->scripts as $script)
		{
			Html::_('script', $script, ['version' => 'auto']);
		}
	}
}
