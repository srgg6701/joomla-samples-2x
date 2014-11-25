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
     * Обработать запрос изменения групп юзера
     */
    public function manageUserGroups($users_data){
        $users = json_decode($users_data);
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
        $errors=$results=array();
        $table_name = '#__user_usergroup_map';
        foreach($users as $user_id=>$user_groups){
            // получить колич. групп юзера
            $query = $db->getQuery(true);
            $query->select('COUNT(*)')
                ->from($table_name)
                ->where('user_id = ' . $user_id);
            $db->setQuery($query);
            $count_user_groups = $db->loadResult();
            $deleted=0; // счётчик удалённых групп юзера
            /*
                array(1) {
                  [0]=>
                  array(2) {
                    [0]=>
                    string(1) "2"
                    [1]=>
                    string(2) "-1"
                  }
                }
                array(2) {
                  [0]=>
                  array(2) {
                    [0]=>
                    string(1) "2"
                    [1]=>
                    string(1) "3"
                  }
                  [1]=>
                  array(2) {
                    [0]=>
                    string(1) "3"
                    [1]=>
                    string(1) "2"
                  }
                }
            */
            foreach($user_groups as $i=>$groups) {
                // [0] - id старой группы
                // [1] - id новой группы
                $condition = 'user_id = ' . $user_id . ' AND group_id = ' . $groups[0];
                // удалить группу
                if($groups[1]=='-1'){
                    //echo "\n".__LINE__."Delete the group\n";
                    if($count_user_groups>1){ // если групп более 1, тогда можно удалять
                        //echo "\n".__LINE__."User has more than 1 group";
                        // и если удалено меньше, чем всего групп юзера +1
                        if($deleted<$count_user_groups-1){
                            //echo "\n".__LINE__."deleting...";
                            $query = $db->getQuery(true);
                            $query->delete($table_name)->where($condition);
                            $db->setQuery($query);
                            if(!$result = $db->execute()){
                                //echo "\n".__LINE__."error while deleting...";
                                $errors[]=array($user_id,"delete");
                            }else{
                                //echo "\n".__LINE__."deleted. Increase the counter of deletions";
                                $deleted++;
                                $results[]=array('user_id'=>$user_id,'deleted'=>$groups[0]);
                            }
                        }else{
                            //echo "\n".__LINE__."Error: the groups deletion limit is exceeded...";
                            $errors[]=array($user_id,"l");
                        }
                    }else{
                        //echo "\n".__LINE__."Error: user has only one group";
                        $errors[]=array($user_id,"s");
                    }
                }else{ // обновить
                    //echo "\n".__LINE__."Change the group\n";
                    $query = $db->getQuery(true);
                    $query->select('COUNT(*)')
                        ->from($table_name)
                        ->where($condition);
                    $db->setQuery($query);
                    if($db->loadResult()){
                        //echo "\n".__LINE__."The record to change the group is found";
                        // проверить, не назначается ли группа повторно
                        $query = $db->getQuery(true);
                        $query->select('COUNT(*)')
                            ->from($table_name)
                            ->where('user_id = ' . $user_id . ' AND group_id = ' . $groups[1]);
                        $db->setQuery($query);
                        if($db->loadResult()){
                            //echo "\n".__LINE__."Error: can not apply the same group...";
                            $errors[]=array($user_id,"r");
                        }else{ // если нет - обновить # группы
                            //echo "\n".__LINE__."updating...";
                            $query = $db->getQuery(true);
                            $query->update($table_name)
                                ->set('group_id = ' . $groups[1])
                                ->where($condition);
                            $db->setQuery($query);
                            if(!$result = $db->execute()){
                                //echo "\n".__LINE__."error while updating...";
                                $errors[]=array($user_id,"update");
                            }
                            else{
                                //echo "\n".__LINE__."updated";
                                $results[]= array('user_id'=>$user_id,'updated'=>array($groups[0],$groups[1]));
                            }
                        }
                    }else{
                        //echo "\n".__LINE__."Error: no record to change the group";
                        $errors[]=array($user_id,"n");
                    }
                }
            }
        }
        return array('results'=>$results, 'errors'=>$errors);
    }

    /**
     * Получить юзеров форума и их данные как обычных юзеров Joomla
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
        /*$query->update('#__user_usergroup_map')
            ->set('group_id = ' . $user_group)
            ->where("SELECT
  #__users.id
FROM #__users
  INNER JOIN phpbb_users
    ON #__users.username = phpbb_users.username
  WHERE #__users.username = (
    SELECT username FROM phpbb_users WHERE user_id = " . $user_id . "
  )");
        $query = $db->getQuery(true);
        */
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
