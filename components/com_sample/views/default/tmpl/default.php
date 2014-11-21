<?
defined('_JEXEC') or die('Restricted access');
$req_path=dirname(__FILE__).'/../../../assets/';
?>
<?php
	if ($this->params->get('show_page_heading')) :
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif;
    require_once $req_path . 'css.php';
    require_once $req_path . 'js.php';
?>
<h2>Юзеры форума</h2>
<div>(также отображены их данные как обычных пользователей системы)</div>
<p>Клацните по группе юзера, чтобы выбрать ей достойную замену.</p>
<div class="container-div" id="table-container">
    <table class="common">
        <tr>
            <th>user id</th>
            <th>name</th>
            <th>username</th>
            <th>email</th>
            <th>group_names</th>
        </tr>
    <?php   foreach($this->users as $forum_user_id=>$user):
    ?>
        <tr>
            <td><?php echo $user['user_id'];?></td>
            <td><?php echo $user['name'];?></td>
            <td><?php echo $user['username'];?></td>
            <td><?php echo $user['email'];?></td>
            <td><?php
                $groups_data = explode("\n",$user['group_names']);
                foreach($groups_data as $i=>$group_data){
                    if($i) echo ", ";
                    $gdata=explode(':',$group_data);
                    echo '<span data-group-id="' . $gdata[0] . '">' .
                        $gdata[1] . '</span>';
                }?>
            </td>
        </tr>
    <?php   endforeach;
    ?>
    </table>
    <button id="btn-apply">Подтвердить!</button>
</div>