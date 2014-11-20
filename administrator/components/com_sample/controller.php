<?php	
/**
 * @version     2.1.0
 * @package     com_sample
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sample'.DS."tables".DS."users_forum.php";

require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sample'.DS."helpers".DS."default.php";
// No direct access
defined('_JEXEC') or die;

class SampleController extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			$cachable	If true, the view output will be cached
	 * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{	
		$view=$this->getView('sample', 'html' ); // по умолчанию
		
		$model=$this->getModel('sample'); // по умолчанию
		$view->setModel($model,true);
		
		// ЕСЛИ УСТАНОВЛЕН layout
		// $view->setLayout('layout_name'); 
		
		// Use the View display method 
		$view->display(); 
	}
	/**
 * Описание
 * @package
 * @subpackage
 */
	public function edit(){
		$pk=JRequest::getVar('id');
		$model=$this->getModel('Item');
		$model->getItem($pk);
		$this->display();
	}
}
