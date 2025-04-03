<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				TLWebdesign 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.2
	@build			3rd April, 2025
	@created		1st April, 2025
	@package		Business Directory
	@subpackage		Dispatcher.php
	@author			Tom van der Laan <https://tlwebdesign.nl>	
	@copyright		Copyright (C) 2025. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/
namespace JCB\Component\Businessdirectory\Site\Dispatcher;

use Joomla\CMS\Dispatcher\ComponentDispatcher;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * ComponentDispatcher class for com_businessdirectory
 *
 * @since  4.0.0
 */
class Dispatcher extends ComponentDispatcher
{
	/**
	 * Dispatch a controller task. Redirecting the user if appropriate.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	public function dispatch()
	{
		parent::dispatch();
	}
}
