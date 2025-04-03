<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				TLWebdesign 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.2
	@build			3rd April, 2025
	@created		1st April, 2025
	@package		Business Directory
	@subpackage		ModalSelectField.php
	@author			Tom van der Laan <https://tlwebdesign.nl>	
	@copyright		Copyright (C) 2025. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/
namespace JCB\Component\Businessdirectory\Site\Field;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ModalSelectField as ModalSelectFieldCore;
use Joomla\Database\ParameterType;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * A modal content selection field, now with the radical ability to handle string-based GUIDs.
 * Because, apparently, assuming everything is an integer was the hill to die on.
 *
 * This override exists solely to bypass a hardcoded constraint that shouldn't have been there in the first place.
 * But hey, at least we get another class extension to maintain!
 *
 * @since  5.0.0
 */
class ModalSelectField extends ModalSelectFieldCore
{
	/**
	 * Method to retrieve the title of selected item.
	 *
	 * @return string
	 *
	 * @since   5.0.0
	 */
	protected function getValueTitle()
	{
		// Selecting the title for the field value, when required info were given
		if ($this->value && $this->sql_title_table && $this->sql_title_column && $this->sql_title_key) {
			try {
				$db	= $this->getDatabase();
				$query = $db->getQuery(true)
					->select($db->quoteName($this->sql_title_column))
					->from($db->quoteName($this->sql_title_table))
					->where($db->quoteName($this->sql_title_key) . ' = :value')
					->bind(':value', $this->value, is_numeric($this->value) ? ParameterType::INTEGER : ParameterType::STRING);

				/**
				 *   All this—just because someone decided to hardcode [ParameterType::INTEGER] in the core.
				 *   We could have just handle it dynamically, but no...
				 *
				 *   Polymorphic behavior isn't the enemy. Hardcoded constraints that force class extensions?
				 *   Now *that's* the real problem. But sure, let's keep pretending that integers are the
				 *   only valid identifiers in a database.
				 */

				$db->setQuery($query);

				return $db->loadResult() ?: $this->value;
			} catch (\Throwable $e) {
				Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			}
		}

		return $this->value;
	}
}