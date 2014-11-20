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
     * Обработать запрос изменения группы юзера
     */
    public function manageUserGroups($users_data){
        // {"584":["3"],"589":["5","6"]}
        $users = json_decode($users_data);
        /*object(stdClass)#157 (2) {
          ["584"]=>
          array(1) {
            [0]=>
            string(1) "2"
          }
          ["585"]=>
          array(2) {
            [0]=>
            string(1) "3"
            [1]=>
            string(1) "5"
          }
        }*/
        /*
        $query->select('ug.title,
  usrs_forum.id AS forum_user_id,
  users.id AS user_id,
  users.name,
  users.username,
  users.email')
            ->from('#__usergroups AS ug')
            ->innerJoin('#__user_usergroup_map AS ugmp ON ug.id = ugmp.group_id')
            ->innerJoin('#__users AS users ON ugmp.user_id = users.id')
            ->innerJoin('#__users_forum AS usrs_forum ON usrs_forum.user_id = users.id')
            ->order('ugmp.group_id');
        $db->setQuery($query);
        $users=$db->loadObjectList();*/
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        foreach($users as $user_id=>$user_groups){
            $query->select('group_id')
                ->from('#__usergroups')
                ->where('user_id = ' . $user_id);
        }
    }

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
}
