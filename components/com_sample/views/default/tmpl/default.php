<?
defined('_JEXEC') or die('Restricted access');
	if ($this->params->get('show_page_heading')) : 
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif;
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<style>
    table.common{
        border: solid 2px #ccc;
    }
    div.close{
        color: red;
        cursor: pointer;
        display: inline-block;
        height: 16px;
        margin: 0 -6px -3px 10px;
        transform: scaleX(1.2);
        width: 16px;
    }
    th{
        white-space: nowrap;
    }
    .common th,
    .common td{
        padding: 2px 4px;
    }
    .common tr td:last-child span{
        color: navy;
        cursor: pointer;
    }
    .container-div{
        display: table;
        overflow: auto;
    }
    .groups_list{
        margin: -2px -5px;
    }
    #btn-apply{
        float: right;
        margin-top: 4px;
        padding: 4px 10px;
    }
    #result-message{
        background-color: lavender;
        clear: both;
        float: left;
        margin-top: 10px;
        padding: 10px;
    }
</style><?php  //var_dump($this->users); ?>
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
<?php require_once JPATH_SITE . DS . 'components' . DS. 'com_sample' . DS . 'assets' . DS . 'js.php';