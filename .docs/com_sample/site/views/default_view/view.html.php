<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
	 
/**
 * HTML View class for the Component
 */
class SampleViewSample extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{	// Get the view data FROM model.
		//$this->user		= JFactory::getUser();
		//$this->form		= $this->get('Form');
		$this->state	= $this->get('State');
		$this->params	= $this->state->get('params');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// ���������� ������� ��������. 
		// ��������� �� ������, - ��������� ����������: 
		$clear_params=str_replace('"','',$this->item->params);
		$pg_params=explode(",",$clear_params);
		foreach($pg_params as $couple){
			$arrPar=explode(":",$couple);
			if($arrPar[0]=='pageclass_sfx'){
				$this->params->pageclass_sfx=$arrPar[1];
				break;
			}
		}
		
		// Assign data to the view
		$this->prepareDocument();

		// Display the view
		parent::display($tpl);
	}
	protected function prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$user		= JFactory::getUser();
		$login		= $user->get('guest') ? true : false;
		$title 		= null;
		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', $login ? JText::_('JLOGIN') : JText::_('JLOGOUT'));
		}

		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
	
}
