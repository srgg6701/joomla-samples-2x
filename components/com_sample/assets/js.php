<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    $(function(){
        var rmess_id = 'result-message',
            // удаление контейнера с сообщениями об ошибках:
            removeMessContainer = function(){
                $('#'+rmess_id).remove();
            },
            user_groups='<select class="groups_list">';
        <?php
        foreach($this->usergroups as $i=>$group_data):?>
        user_groups+='<option value="<?php echo $group_data->id;?>">';
        user_groups+='<?php echo $group_data->title;?>';
        user_groups+='</option>';
        <?php
        endforeach; echo "\n";?>
        user_groups+='<option value="-1" style="color:red">Удалить</option>';
        user_groups+='</select><div class="close">x</div>';
        // отобразить список групп для замены и выделить текущую группу
        $('td span').on('click', function(){
            $(this).after(user_groups).hide();
            var tSelect = $(this).next()[0],
                opt = $('option:contains('+$(this).text()+')', tSelect);
            tSelect.options[$(opt).index()].selected=true
        });
        // подтвердить смену группы юзера
        $('#btn-apply').on('click', function(){
            var usersData={};
            $('select.groups_list option:selected')
                .each(function(index,element){
                    var tr=$(element).parents('tr'),
                        user_id=$('td:first-child', tr).text(),
                        groups = [  $(element).parent().prev().attr('data-group-id'),
                            $(element).val()
                        ];

                    if(!usersData[user_id]){
                        usersData[user_id]=[groups];
                    }else{
                        usersData[user_id].push(groups);
                    }
                }); //console.dir(usersData);
            //---------------------------------------------------------------
            // отправить данные PHP-обработчику
            $.post(
                "index.php?option=com_sample",
                {
                    task:"change_user_group",
                    users:JSON.stringify(usersData)
                },
                function(data){
                    var rslts = JSON.parse(data),
                        results = rslts['results'],
                        errors = rslts['errors'];
                    //console.group();console.dir(rslts);console.log(results.length,errors.length);console.groupEnd();
                    // удалить контейнер с сообщением
                    removeMessContainer();
                    // если получили результаты
                    if(results.length){
                        for(var i in results){
                            var obj = results[i];
                            if(typeof(obj)==='object' && obj!==null){
                                // получить активные списки выбора
                                $('td:contains('+obj['user_id']+')').parent('tr').find('select')
                                    .each(function(index,element){
                                        var sGr = $('option:selected',element),
                                            gText = $(sGr).text(),
                                            gId = $(sGr).val(); //console.log(gId,gText);
                                        // обработать span перед списком
                                        $(element).prev()
                                            .attr('data-group-id',gId)
                                            .text(gText)
                                            .css('color','#E500D1')
                                            .show();
                                        $(element).next().remove(); // div.close
                                        $(element).remove(); // удалить сам список
                                    });
                            }
                        }
                    }
                    // если есть ошибки
                    if(errors.length){
                        // создать новый контейнер для сообщений
                        var rmess = $('<div/>',{
                            id:rmess_id
                        });
                        $('#table-container').append(rmess);

                        var usrid = ' юзера id ',
                            str_end = ' группу' + usrid,
                            error_message;
                        //
                        for(var i in errors){
                            var obj = errors[i];
                            if(typeof(obj)==='object' && obj!==null){
                                //console.dir(obj);
                                switch (obj[1]){
                                    case 'l': // L
                                        error_message='Нельзя удалить последнюю'+str_end+obj[0];
                                        break;
                                    case 's':
                                        error_message='Нельзя удалить единственную'+str_end+obj[0];
                                        break;
                                    case 'r':
                                        error_message='Нельзя дважды назначить одну и ту же группу для '+usrid+obj[0];
                                        break;
                                    case 'delete':
                                        error_message='Ошибка удаления группы '+usrid+obj[0];
                                        break;
                                    case 'update':
                                        error_message='Ошибка обновления группы '+usrid+obj[0];
                                        break;
                                }
                                $(rmess).append('<div class="mess-err">'+error_message+'</div><div class="close parent">x</div>');
                            }
                        }
                    }
                });
        });
        // отменить выбор группы, убрать сообщение
        $('.container-div').on('click', '.close',function(event){
            if(!$(event.currentTarget).hasClass('parent'))
                $(event.currentTarget).prev().prev().show();
            $(event.currentTarget).prev().remove();
            $(event.currentTarget).remove();
            if($(event.currentTarget).hasClass('parent')){
                if(!$('.mess-err').size())
                    removeMessContainer();
            }
        });
    });
</script>