<?
defined('_JEXEC') or die('Restricted access');
	if ($this->params->get('show_page_heading')) : 
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif;

var_dump($this->users);?>
<h4>Юзеры</h4>
