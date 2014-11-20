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
	{	// Get the document object.
		$document	= JFactory::getDocument();
		// Set the default view name and format from the Request.
		$vName	 = JRequest::getCmd('view', 'default'); // default
        $vFormat = $document->getType(); // html
        $lName	 = JRequest::getCmd('layout', 'default'); // default
        if ($view = $this->getView($vName, $vFormat)) {
            $model = $this->getModel('Sample');
			$view->setModel($model, true);
			$view->setLayout($lName);
			$view->assignRef('document', $document);
			$view->display();
		}
	}	
}
