<?
defined('_JEXEC') or die('Restricted access');
	if ($this->params->get('show_page_heading')) : 
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif;
?>
<style>
    .common th,
    .common td{
        padding: 2px 4px;
    }
</style>
<?php var_dump($this->usergroups);
?>
<h2>Юзеры</h2>
<table class="common">
    <tr>
        <th>id</th>
        <th>name</th>
        <th>username</th>
        <th>email</th>
        <th>group_names</th>
    </tr>
<?php   foreach($this->users as $i=>$user):
            // id, name, username, email, group_count, group_names
?>
    <tr>
        <td><?php echo $user->id;?></td>
        <td><?php echo $user->name;?></td>
        <td><?php echo $user->username;?></td>
        <td><?php echo $user->email;?></td>
        <td><?php
            echo str_replace("\n",", ",$user->group_names);?></td>
    </tr>
<?php   endforeach;
?>
</table>