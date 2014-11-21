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
        user_groups+='<option value="-1" style="color:red">Удалить</option>';
        user_groups+='</select><div class="close">x</div>';
        // отобразить список групп для замены и выделить текущую группу
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
                    var rmess_id = 'result-message';
                    $('#'+rmess_id).remove();
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

                        var str_end = ' группу юзера id ';
                        //
                        for(var i in errors){
                            var obj = errors[i];
                            if(typeof(obj)==='object' && obj!==null){
                                //console.dir(obj);
                                if(obj[1]==='l'){
                                    $(rmess).append('<div class="mess-err">Нельзя удалить последнюю'+str_end+obj[0]+'</div>');
                                    //console.log('Нельзя удалить последнюю'+str_end+obj[0]);
                                }
                                if(obj[1]==='s'){
                                    $(rmess).append('<div class="mess-err">Нельзя удалить единственную'+str_end+obj[0]+'</div>');
                                    //console.log('Нельзя удалить единственную'+str_end+obj[0]);
                                }
                                if(obj[1]==='r'){
                                    $(rmess).append('<div class="mess-err">Нельзя дважды назначить одну и ту же'+str_end+obj[0]+'</div>');
                                    //console.log('Нельзя удалить единственную'+str_end+obj[0]);
                                }

                            }
                        }
                    }
                });
        });
    });
</script>