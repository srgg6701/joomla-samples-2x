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
	    //var_dump(debug_print_backtrace()); die();
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
                require_once JPATH_ADMINISTRATOR .DS. 'components' .DS.'com_users'.DS.'models'.DS.'users.php';
                $model_users=new UsersModelUsers();
                $view->users=$model_users->getItems();
                $view->usergroups=$this->getUsersGroup();
            }
			$view->assignRef('document', $document);
			$view->display();
		}
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
