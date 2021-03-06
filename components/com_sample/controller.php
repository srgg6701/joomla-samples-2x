﻿<?php 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
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
                $view->users=$model->getForumUsers();
                $view->usergroups=$model->getUsersGroup();
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
        echo json_encode($this->getModel()->manageUserGroups($post['users']));
        exit;
    }
    /**
     * Обработать запрос на изменение групп юзера
     */
    public function handle_user_groups(){
        $user_id=JRequest::getVar('user_id');
        if($this->getModel()->changeUsersGroup($user_id))
            echo '<div>Изменена группа для user_id: ' . $user_id . ', username: ' . $this->getModel()->getUserName($user_id) . '.</div>';
        else
            echo "<div style='color:red'>Ошибка во время обновления данных...</div>";
        exit;
    }
}
