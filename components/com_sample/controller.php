<?php 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
//jimport('joomla.application.component.controller');
 
/**
 * Sample Controller
 */
class SampleController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
	    // Get the document object.
		$document	= JFactory::getDocument();
		// Set the default view name and format from the Request.
		$vName	 = JRequest::getCmd('view', 'default'); // default
        $vFormat = $document->getType(); // html
        $lName	 = JRequest::getCmd('layout', 'default'); // default
        if ($view = $this->getView($vName, $vFormat)) {
            $model = $this->getModel('Sample');
			$view->setModel($model, true);
            $view->setLayout($lName);
            // если раздел по умолчанию, получим реальных юзеров:
            if($lName==='default'){
                $view->users=$this->getForumUsers();
                $view->usergroups=$this->getUsersGroup();
            }
			$view->assignRef('document', $document);
			$view->display();
		}
	}
    /**
     * Изменить группу юзера
     */
    public function change_user_group(){
        $post=JRequest::get('post');
        //echo
        $this->getModel()->manageUserGroups($post['users']);
        exit;
    }
    /**
     * Получить юзеров форума с данными обычных юзеров
     */
    private function getForumUsers(){
        // Get the counts from the database only for the users in the list.
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
     * Получим список групп юзеров
     * @return array
     */
    private function getUsersGroup(){
        $db =JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, title')->from('#__usergroups');
        $db->setQuery($query);
        return $db->loadObjectList();
    }
}
