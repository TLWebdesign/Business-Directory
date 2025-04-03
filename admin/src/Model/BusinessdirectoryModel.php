<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				TLWebdesign 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.2
	@build			3rd April, 2025
	@created		1st April, 2025
	@package		Business Directory
	@subpackage		BusinessdirectoryModel.php
	@author			Tom van der Laan <https://tlwebdesign.nl>	
	@copyright		Copyright (C) 2025. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/
namespace JCB\Component\Businessdirectory\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\User\User;
use Joomla\Utilities\ArrayHelper;
use Joomla\Input\Input;
use JCB\Component\Businessdirectory\Administrator\Helper\BusinessdirectoryHelper;
use JCB\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use JCB\Joomla\Utilities\StringHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Businessdirectory List Model
 *
 * @since  1.6
 */
class BusinessdirectoryModel extends ListModel
{
	/**
	 * The styles array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $styles = [
		'administrator/components/com_businessdirectory/assets/css/admin.css',
		'administrator/components/com_businessdirectory/assets/css/dashboard.css'
 	];

	/**
	 * The scripts array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $scripts = [
		'administrator/components/com_businessdirectory/assets/js/admin.js'
 	];

	public function getIcons()
	{
		// load user for access menus
		$user = Factory::getApplication()->getIdentity();
		// reset icon array
		$icons  = [];
		// view groups array
		$viewGroups = array(
			'main' => array('png.company.add', 'png.companies', 'png.countries', 'png.states')
		);
		// view access array
		$viewAccess = [
			'companies.access' => 'company.access',
			'company.access' => 'company.access',
			'companies.dashboard_list' => 'company.dashboard_list',
			'company.dashboard_add' => 'company.dashboard_add',
			'country.create' => 'country.create',
			'countries.access' => 'country.access',
			'country.access' => 'country.access',
			'countries.dashboard_list' => 'country.dashboard_list',
			'state.create' => 'state.create',
			'states.access' => 'state.access',
			'state.access' => 'state.access',
			'states.dashboard_list' => 'state.dashboard_list',
		];
		// loop over the $views
		foreach($viewGroups as $group => $views)
		{
			$i = 0;
			if (UtilitiesArrayHelper::check($views))
			{
				foreach($views as $view)
				{
					$add = false;
					// external views (links)
					if (strpos($view,'||') !== false)
					{
						$dwd = explode('||', $view);
						if (count($dwd) == 3)
						{
							list($type, $name, $url) = $dwd;
							$viewName = $name;
							$alt      = $name;
							$url      = $url;
							$image    = $name . '.' . $type;
							$name     = 'COM_BUSINESSDIRECTORY_DASHBOARD_' . StringHelper::safe($name,'U');
						}
					}
					// internal views
					elseif (strpos($view,'.') !== false)
					{
						$dwd = explode('.', $view);
						if (count($dwd) == 3)
						{
							list($type, $name, $action) = $dwd;
						}
						elseif (count($dwd) == 2)
						{
							list($type, $name) = $dwd;
							$action = false;
						}
						if ($action)
						{
							$viewName = $name;
							switch($action)
							{
								case 'add':
									$url   = 'index.php?option=com_businessdirectory&view=' . $name . '&layout=edit';
									$image = $name . '_' . $action.  '.' . $type;
									$alt   = $name . '&nbsp;' . $action;
									$name  = 'COM_BUSINESSDIRECTORY_DASHBOARD_'.StringHelper::safe($name,'U').'_ADD';
									$add   = true;
								break;
								default:
									// check for new convention (more stable)
									if (strpos($action, '_qpo0O0oqp_') !== false)
									{
										list($action, $extension) = (array) explode('_qpo0O0oqp_', $action);
										$extension = str_replace('_po0O0oq_', '.', $extension);
									}
									else
									{
										$extension = 'com_businessdirectory.' . $name;
									}
									$url   = 'index.php?option=com_categories&view=categories&extension=' . $extension;
									$image = $name . '_' . $action . '.' . $type;
									$alt   = $viewName . '&nbsp;' . $action;
									$name  = 'COM_BUSINESSDIRECTORY_DASHBOARD_' . StringHelper::safe($name,'U') . '_' . StringHelper::safe($action,'U');
								break;
							}
						}
						else
						{
							$viewName = $name;
							$alt      = $name;
							$url      = 'index.php?option=com_businessdirectory&view=' . $name;
							$image    = $name . '.' . $type;
							$name     = 'COM_BUSINESSDIRECTORY_DASHBOARD_' . StringHelper::safe($name,'U');
							$hover    = false;
						}
					}
					else
					{
						$viewName = $view;
						$alt      = $view;
						$url      = 'index.php?option=com_businessdirectory&view=' . $view;
						$image    = $view . '.png';
						$name     = ucwords($view).'<br /><br />';
						$hover    = false;
					}
					// first make sure the view access is set
					if (UtilitiesArrayHelper::check($viewAccess))
					{
						// setup some defaults
						$dashboard_add = false;
						$dashboard_list = false;
						$accessTo = '';
						$accessAdd = '';
						// access checking start
						$accessCreate = (isset($viewAccess[$viewName.'.create'])) ? StringHelper::check($viewAccess[$viewName.'.create']):false;
						$accessAccess = (isset($viewAccess[$viewName.'.access'])) ? StringHelper::check($viewAccess[$viewName.'.access']):false;
						// set main controllers
						$accessDashboard_add = (isset($viewAccess[$viewName.'.dashboard_add'])) ? StringHelper::check($viewAccess[$viewName.'.dashboard_add']):false;
						$accessDashboard_list = (isset($viewAccess[$viewName.'.dashboard_list'])) ? StringHelper::check($viewAccess[$viewName.'.dashboard_list']):false;
						// check for adding access
						if ($add && $accessCreate)
						{
							$accessAdd = $viewAccess[$viewName.'.create'];
						}
						elseif ($add)
						{
							$accessAdd = 'core.create';
						}
						// check if access to view is set
						if ($accessAccess)
						{
							$accessTo = $viewAccess[$viewName.'.access'];
						}
						// set main access controllers
						if ($accessDashboard_add)
						{
							$dashboard_add    = $user->authorise($viewAccess[$viewName.'.dashboard_add'], 'com_businessdirectory');
						}
						if ($accessDashboard_list)
						{
							$dashboard_list = $user->authorise($viewAccess[$viewName.'.dashboard_list'], 'com_businessdirectory');
						}
						if (StringHelper::check($accessAdd) && StringHelper::check($accessTo))
						{
							// check access
							if($user->authorise($accessAdd, 'com_businessdirectory') && $user->authorise($accessTo, 'com_businessdirectory') && $dashboard_add)
							{
								$icons[$group][$i]        = new \StdClass;
								$icons[$group][$i]->url   = $url;
								$icons[$group][$i]->name  = $name;
								$icons[$group][$i]->image = $image;
								$icons[$group][$i]->alt   = $alt;
							}
						}
						elseif (StringHelper::check($accessTo))
						{
							// check access
							if($user->authorise($accessTo, 'com_businessdirectory') && $dashboard_list)
							{
								$icons[$group][$i]        = new \StdClass;
								$icons[$group][$i]->url   = $url;
								$icons[$group][$i]->name  = $name;
								$icons[$group][$i]->image = $image;
								$icons[$group][$i]->alt   = $alt;
							}
						}
						elseif (StringHelper::check($accessAdd))
						{
							// check access
							if($user->authorise($accessAdd, 'com_businessdirectory') && $dashboard_add)
							{
								$icons[$group][$i]        = new \StdClass;
								$icons[$group][$i]->url   = $url;
								$icons[$group][$i]->name  = $name;
								$icons[$group][$i]->image = $image;
								$icons[$group][$i]->alt   = $alt;
							}
						}
						else
						{
							$icons[$group][$i]        = new \StdClass;
							$icons[$group][$i]->url   = $url;
							$icons[$group][$i]->name  = $name;
							$icons[$group][$i]->image = $image;
							$icons[$group][$i]->alt   = $alt;
						}
					}
					else
					{
						$icons[$group][$i]        = new \StdClass;
						$icons[$group][$i]->url   = $url;
						$icons[$group][$i]->name  = $name;
						$icons[$group][$i]->image = $image;
						$icons[$group][$i]->alt   = $alt;
					}
					$i++;
				}
			}
			else
			{
					$icons[$group][$i] = false;
			}
		}
		return $icons;
	}

	/**
	 * Method to get the styles that have to be included on the view
	 *
	 * @return  array    styles files
	 * @since   4.3
	 */
	public function getStyles(): array
	{
		return $this->styles;
	}

	/**
	 * Method to set the styles that have to be included on the view
	 *
	 * @return  void
	 * @since   4.3
	 */
	public function setStyles(string $path): void
	{
		$this->styles[] = $path;
	}

	/**
	 * Method to get the script that have to be included on the view
	 *
	 * @return  array    script files
	 * @since   4.3
	 */
	public function getScripts(): array
	{
		return $this->scripts;
	}

	/**
	 * Method to set the script that have to be included on the view
	 *
	 * @return  void
	 * @since   4.3
	 */
	public function setScript(string $path): void
	{
		$this->scripts[] = $path;
	}
}
