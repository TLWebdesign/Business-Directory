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
namespace JCB\Component\Businessdirectory\Administrator\View\Country;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\User;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Document\Document;
use JCB\Component\Businessdirectory\Administrator\Helper\BusinessdirectoryHelper;
use JCB\Joomla\Utilities\StringHelper;
use Joomla\Input\Input;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Country Html View class
 *
 * @since  1.6
 */
#[\AllowDynamicProperties]
class HtmlView extends BaseHtmlView
{
	/**
	 * The item from the model
	 *
	 * @var    mixed
	 * @since  3.10.11
	 */
	public mixed $item;

	/**
	 * The input class
	 *
	 * @var    Input
	 * @since  5.2.1
	 */
	public Input $input;

	/**
	 * The state object
	 *
	 * @var    mixed
	 * @since  3.10.11
	 */
	public mixed $state;

	/**
	 * The form from the model
	 *
	 * @var    mixed
	 * @since  3.10.11
	 */
	public mixed $form;

	/**
	 * The toolbar object
	 *
	 * @var    Toolbar
	 * @since  3.10.11
	 */
	public Toolbar $toolbar;

	/**
	 * The styles url array
	 *
	 * @var    array
	 * @since  5.0.0
	 */
	protected array $styles;

	/**
	 * The scripts url array
	 *
	 * @var    array
	 * @since  5.0.0
	 */
	protected array $scripts;

	/**
	 * The actions object
	 *
	 * @var    object
	 * @since  3.10.11
	 */
	public object $canDo;

	/**
	 * The origin referral view name
	 *
	 * @var    string
	 * @since  3.10.11
	 */
	public string $ref;

	/**
	 * The origin referral item id
	 *
	 * @var    int
	 * @since  3.10.11
	 */
	public int $refid;

	/**
	 * The referral url suffix values
	 *
	 * @var    string
	 * @since  3.10.11
	 */
	public string $referral;

	/**
	 * The modal state
	 *
	 * @var    bool
	 * @since  5.2.1
	 */
	public bool $isModal;

	/**
	 * Country view display method
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 * @throws \Exception
	 * @since  1.6
	 */
	public function display($tpl = null): void
	{
		// set params
		$this->params = ComponentHelper::getParams('com_businessdirectory');
		$this->useCoreUI = true;
		// Assign the variables
		$this->form ??= $this->get('Form');
		$this->item = $this->get('Item');
		$this->styles = $this->get('Styles');
		$this->scripts = $this->get('Scripts');
		$this->state = $this->get('State');
		// get action permissions
		$this->canDo = BusinessdirectoryHelper::getActions('country', $this->item);
		// get input
		$this->input ??= Factory::getApplication()->input;
		$this->ref = $this->input->get('ref', 0, 'word');
		$this->refid = $this->input->get('refid', 0, 'int');
		$return = $this->input->get('return', null, 'base64');
		// set the referral string
		$this->referral = '';
		if ($this->refid && $this->ref)
		{
			// return to the item that referred to this item
			$this->referral = '&ref=' . (string) $this->ref . '&refid=' . (int) $this->refid;
		}
		elseif($this->ref)
		{
			// return to the list view that referred to this item
			$this->referral = '&ref=' . (string) $this->ref;
		}
		// check return value
		if (!is_null($return))
		{
			// add the return value
			$this->referral .= '&return=' . (string) $return;
		}

		// Set the toolbar
		if ($this->getLayout() !== 'modal')
		{
			$this->isModal = false;
			$this->addToolbar();
		}
		else
		{
			$this->isModal = true;
			$this->addModalToolbar();
		}

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
	 * @throws  \Exception
	 * @since   1.6
	 */
	protected function addToolbar(): void
	{
		Factory::getApplication()->input->set('hidemainmenu', true);
		$user = $this->getCurrentUser();
		$userId	= $user->id;
		$isNew = $this->item->id == 0;

		ToolbarHelper::title( Text::_($isNew ? 'COM_BUSINESSDIRECTORY_COUNTRY_NEW' : 'COM_BUSINESSDIRECTORY_COUNTRY_EDIT'), 'pencil-2 article-add');
		// Built the actions for new and existing records.
		if (StringHelper::check($this->referral))
		{
			if ($this->canDo->get('country.create') && $isNew)
			{
				// We can create the record.
				ToolbarHelper::save('country.save', 'JTOOLBAR_SAVE');
			}
			elseif ($this->canDo->get('country.edit'))
			{
				// We can save the record.
				ToolbarHelper::save('country.save', 'JTOOLBAR_SAVE');
			}
			if ($isNew)
			{
				// Do not creat but cancel.
				ToolbarHelper::cancel('country.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				// We can close it.
				ToolbarHelper::cancel('country.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		else
		{
			if ($isNew)
			{
				// For new records, check the create permission.
				if ($this->canDo->get('country.create'))
				{
					ToolbarHelper::apply('country.apply', 'JTOOLBAR_APPLY');
					ToolbarHelper::save('country.save', 'JTOOLBAR_SAVE');
					ToolbarHelper::custom('country.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				};
				ToolbarHelper::cancel('country.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				if ($this->canDo->get('country.edit'))
				{
					// We can save the new record
					ToolbarHelper::apply('country.apply', 'JTOOLBAR_APPLY');
					ToolbarHelper::save('country.save', 'JTOOLBAR_SAVE');
					// We can save this record, but check the create permission to see
					// if we can return to make a new one.
					if ($this->canDo->get('country.create'))
					{
						ToolbarHelper::custom('country.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
					}
				}
				$canVersion = ($this->canDo->get('core.version') && $this->canDo->get('country.version'));
				if ($this->state->params->get('save_history', 1) && $this->canDo->get('country.edit') && $canVersion)
				{
					ToolbarHelper::versions('com_businessdirectory.country', $this->item->id);
				}
				if ($this->canDo->get('country.create'))
				{
					ToolbarHelper::custom('country.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
				}
				ToolbarHelper::cancel('country.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		ToolbarHelper::divider();
		ToolbarHelper::inlinehelp();
		// set help url for this view if found
		$this->help_url = BusinessdirectoryHelper::getHelpUrl('country');
		if (StringHelper::check($this->help_url))
		{
			ToolbarHelper::help('COM_BUSINESSDIRECTORY_HELP_MANAGER', false, $this->help_url);
		}
	}

	/**
	 * Add the modal toolbar.
	 *
	 * @return  void
	 * @throws  \Exception
	 * @since   5.0.0
	 */
	protected function addModalToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);
		$user = $this->getCurrentUser();
		$userId	= $user->id;
		$isNew = $this->item->id == 0;

		ToolbarHelper::title( Text::_($isNew ? 'COM_BUSINESSDIRECTORY_COUNTRY_NEW' : 'COM_BUSINESSDIRECTORY_COUNTRY_EDIT'), 'pencil-2 article-add');
		// Built the actions for new and existing records.
		if (StringHelper::check($this->referral))
		{
			if ($this->canDo->get('country.create') && $isNew)
			{
				// We can create the record.
				ToolbarHelper::save('country.save', 'JTOOLBAR_SAVE');
			}
			elseif ($this->canDo->get('country.edit'))
			{
				// We can save the record.
				ToolbarHelper::save('country.save', 'JTOOLBAR_SAVE');
			}
			if ($isNew)
			{
				// Do not creat but cancel.
				ToolbarHelper::cancel('country.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				// We can close it.
				ToolbarHelper::cancel('country.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		else
		{
			if ($isNew)
			{
				// For new records, check the create permission.
				if ($this->canDo->get('country.create'))
				{
					ToolbarHelper::apply('country.apply', 'JTOOLBAR_APPLY');
					ToolbarHelper::save('country.save', 'JTOOLBAR_SAVE');
					ToolbarHelper::custom('country.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				};
				ToolbarHelper::cancel('country.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				if ($this->canDo->get('country.edit'))
				{
					// We can save the new record
					ToolbarHelper::apply('country.apply', 'JTOOLBAR_APPLY');
					ToolbarHelper::save('country.save', 'JTOOLBAR_SAVE');
				}
				ToolbarHelper::cancel('country.cancel', 'JTOOLBAR_CLOSE');
			}
		}
	}

	/**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed  $var     The output to escape.
	 * @param   bool   $shorten The switch to shorten.
	 * @param   int    $length  The shorting length.
	 *
	 * @return  mixed  The escaped value.
	 * @since   1.6
	 */
	public function escape($var, bool $shorten = true, int $length = 30)
	{
		if (!is_string($var))
		{
			return $var;
		}

		return StringHelper::html($var, $this->_charset ?? 'UTF-8', $shorten, $length);
	}

	/**
	 * Prepare some document related stuff.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function _prepareDocument(): void
	{
		// Load jQuery
		Html::_('jquery.framework');
		$isNew = ($this->item->id < 1);
		$this->getDocument()->setTitle(Text::_($isNew ? 'COM_BUSINESSDIRECTORY_COUNTRY_NEW' : 'COM_BUSINESSDIRECTORY_COUNTRY_EDIT'));
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
