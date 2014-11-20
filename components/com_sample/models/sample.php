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
        $users = json_decode($users_data);
        var_dump('<pre>',$users,'</pre>');
        //object(stdClass)#157 (2)
          /*["586"]=>
          array(2) {
            [0]=>
            array(2) {
              [0]=>
              string(1) "4"
              [1]=>
              string(1) "7"
            }
            [1]=>
            array(2) {
              [0]=>
              string(1) "5"
              [1]=>
              string(1) "3"
            }
          }*/
          /*
          ["589"]=>
          array(1) {
            [0]=>
            array(2) {
              [0]=>
              string(1) "2"
              [1]=>
              string(1) "3"
            }
          }*/

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        foreach($users as $user_id=>$user_groups){
            foreach($user_groups as $i=>$groups) {
                // 0 - id старой группы
                // 1 - id новой группы
                $query->select('COUNT(*)')
                    ->from('#__usergroups')
                    ->where('user_id = ' . $user_id . ' AND group_id = ' . $groups[0]);

            }
        }
    }

    /**
     * Получить юзеров форума
     */
    public function getForumUsers(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('ug.title,
  usrs_forum.id AS forum_user_id,
  users.id AS user_id,
  users.name,
  users.username,
  users.email,
  ugmp.group_id')
            ->from('#__usergroups AS ug')
            ->innerJoin('#__user_usergroup_map AS ugmp ON ug.id = ugmp.group_id')
            ->innerJoin('#__users AS users ON ugmp.user_id = users.id')
            ->innerJoin('#__users_forum AS usrs_forum ON usrs_forum.user_id = users.id')
            ->order('ugmp.group_id');
        $db->setQuery($query);
        $users=$db->loadObjectList();
        $users_data=array();
        foreach($users as $user_data){
            $forum_user_id=$user_data->forum_user_id;
            if(!$users_data[$forum_user_id]){
                $users_data[$forum_user_id]['user_id']=$user_data->user_id;
                $users_data[$forum_user_id]['name']=$user_data->name;
                $users_data[$forum_user_id]['username']=$user_data->username;
                $users_data[$forum_user_id]['email']=$user_data->email;
                $users_data[$forum_user_id]['group_names']=$user_data->group_id.':'.$user_data->title;
            }else{
                $users_data[$forum_user_id]['group_names'].="\n".$user_data->group_id.':'.$user_data->title;
            }
        }
        return $users_data;
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

    /**
     * Получим список групп юзеров
     * @return array
     */
    public function getUsersGroup(){
        $db =JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, title')->from('#__usergroups');
        $db->setQuery($query);
        return $db->loadObjectList();
    }
}
