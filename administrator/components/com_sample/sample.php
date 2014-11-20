<?php
defined('_JEXEC') or die;
// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_sample')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Execute the task.
$controller	= JControllerLegacy::getInstance('Sample');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
