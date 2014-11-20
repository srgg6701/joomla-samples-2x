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
    .common th,
    .common td{
        padding: 2px 4px;
    }
    .common tr td:last-child span{
        color: navy;
        cursor: pointer;
    }
    .groups_list{
        margin: -2px -5px;
    }
</style>
<h2>Юзеры</h2>
<p>Клацните по группе юзера, чтобы выбрать ей достойную замену.</p>
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
        <td><span><?php
            echo str_replace("\n","</span>, <span>",$user->group_names);
                ?></span>
        </td>
    </tr>
<?php   endforeach;
?>
</table>
<button id="btn-apply">Подтвердить!</button>
<script>
$(function(){
    var user_groups='<select class="groups_list">';
    <?php
    foreach($this->usergroups as $i=>$group_data):?>
        user_groups+='<option value="<?php echo $group_data->id;?>">';
        user_groups+='<?php echo $group_data->title;?>';
        user_groups+='</option>';
    <?php
    endforeach; echo "\n";?>
    user_groups+='</select><div class="close">x</div>';

    // отобразить список групп для замены
    $('td span').on('click', function(){
        $(this).after(user_groups).hide();
        var tSelect = $(this).next()[0],
            opt = $('option:contains('+$(this).text()+')', tSelect);
        tSelect.options[$(opt).index()].selected=true
    });
    // отменить выбор группы
    $('.common').on('click', '.close',function(event){
        $(event.currentTarget).prev().prev().show();
        $(event.currentTarget).prev().remove();
        $(event.currentTarget).remove();
    });
    // подтвердить смену группы юзера
    $('#btn-apply').on('click', function(){

    });
});
</script>