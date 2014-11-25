<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_sample
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

/**
 * Content Component Model
 *
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since 1.5
 */
class SampleModelSample extends JModelLegacy
{
    /**
	 * Model context string.
	 *
	 * @var		string
	 */

	/**
	 * Get the data for a layout.
	 *
	 * @return	object
	 */

	function getItem()
	{
		if (!isset($this->_item))
		{
			if (!$this->_item) {
				$db		= $this->getDbo();
				$query	= $db->getQuery(true);
				$query->select('*');
				$query->from('#__menu');
				$query->where(' id = ' . (int)JRequest::getVar('Itemid'));
				$db->setQuery((string) $query);
				if (!$db->query()) {
					JError::raiseError(500, $db->getErrorMsg());
				}
				$this->_item = $db->loadObject();
			}
		}
		return $this->_item;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}
    /**
     *  Изменить группу юзера
     */
    public function changeUsersGroup($user_id){
        $user_group = '2';// присваеваемая группа
        // получить юзера joomla:
        $db =JFactory::getDbo();
        $query="UPDATE #__user_usergroup_map SET group_id = " . $user_group . "
  WHERE user_id =(
        SELECT #__users.id
        FROM #__users
          INNER JOIN phpbb_users
            ON #__users.username = phpbb_users.username
          WHERE #__users.username = (
            SELECT username FROM phpbb_users WHERE user_id = " . $user_id . "
          )
)";
        $db->setQuery($query);
        return $db->execute();
    }

    /**
     * Получить имя юзера по его id forum
     */
    public function getUserName($user_id){
        $db =JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('username')
            ->from('phpbb_users')
            ->where('user_id = ' . $user_id);
        $db->setQuery($query);
        return $db->loadResult();
    }
}
