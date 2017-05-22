var selectedItem;
function ajax(data){
    return  $.ajax({
            type: "POST",
            url: $("#module_dir").val()+"ajax/ajaxForm.php",
            data: JSON.stringify(data, null, 2),
            success: function(msg) {
                if(msg == 'OK') {
                    showSuccessMessage("Успешно обновлено");
                }  else {
                    $(function () {
                            $('#prefix').val(msg);
                        });
                    showSuccessMessage("Успешно обновлено");
                }
            },
        });
}
function show_hide(id,data){
    if ($(id).is(':checked'))
    $(data).hide();
    else
    $(data).show();
}
function hide_show(id,data){
    if ($(id).is(':checked'))
    $(data).show();
    else
    $(data).hide();
}
function show_hide_select(id,eqval,data){
	select = "input[name='"+id+"']:checked";
	console.log($("input[name='"+id+"']:checked").val())
    if ($("input[name='"+id+"']:checked").val() == eqval)
    $(data).show();
    else
    $(data).hide();
}
$(document).ready(function () {
        selectedItem = $('#final_orders_states').val();
        href_exp_orders =  $('#exp').attr("href");
        show_hide('#curr_1C','#currensy');
        show_hide('#features','#show_feat');
        show_hide('#status_export','#select_status_state');
        show_hide('#col','#stocks');
        show_hide('#sd1c','#shot_desk');
        show_hide('#d1c','#full_desk');
        show_hide('#not_man','#feature_man_block');
        hide_show('#vid','#video_sel_block');
        show_hide_select('zero',1,'#redirect_block');
        show_hide_select('zero',2,'#visibility_block');
        show_hide_select('zero_del',1,'#redirect_block_del');
        show_hide_select('zero_del',2,'#visibility_block_del');

        $("input:checkbox").change(function() {
                if ($(this).is(":checked")) {
                    flag =1;
                } else {
                    flag =0;
                }
                var data = {
                    val_name          :$(this).attr("id"),
                    val_flag         :flag
                };
                console.warn(data)
                ajax(data);
                show_hide('#curr_1C','#currensy');
                show_hide('#features','#show_feat');
                show_hide('#status_export','#select_status_state');
                show_hide('#col','#stocks');
                show_hide('#sd1c','#shot_desk');
                show_hide('#d1c','#full_desk');
                show_hide('#not_man','#feature_man_block');
                hide_show('#vid','#video_sel_block');


            });
        $("input:radio").change(function() {
        	show_hide_select('zero',1,'#redirect_block');
        	show_hide_select('zero',2,'#visibility_block');
        	show_hide_select('zero_del',1,'#redirect_block_del');
        	show_hide_select('zero_del',2,'#visibility_block_del');
                if ($(this).is(":checked"))
                flag =$(this).val();

                var data = {
                    val_name          :$(this).attr("id"),
                    val_flag         :flag
                };
                console.warn(data)
                ajax(data);

            });

        $("#final_orders_states").focusout(function() {
                if ($("#final_orders_states").focusout) {
                    var data = {
                        val_name:'final_orders_states',
                        val_flag         :$("#final_orders_states").val()
                    };
                    var isGood = "Вы уверены, что хотите сохранить выбранные статусы?";
                    alertify.set({
                            labels: {
                                ok     : "Да",
                                cancel : "Нет"
                            },buttonReverse: true
                        });
                    alertify.confirm(isGood, function (e) {
                            if (e) {
                                selectedItem =     $("#final_orders_states").val();
                                ajax(data);
                            } else {
                                $('#final_orders_states').val(selectedItem);
                                showErrorMessage("Отмена");
                            }
                        });
                    /*                        if (r == true) {
                    selectedItem =     $("#final_orders_states").val();
                    ajax(data);
                    } else {
                    $('#final_orders_states').val(selectedItem);
                    showErrorMessage("Отмена");
                    }*/
                }

            });
        $("select").change(function() {

                // if ($(this).focusout) {
                var data = {
                    val_name:$(this).attr("class"),
                    val_flag         :$(this).val()
                };
                //console.log(selectedItem)
                if(data.val_name !="final_orders_states"){
                    if (data.val_name != "ansfile")
                    ajax(data);
                }

            });
        $("input:text").change(function() {
                if($(this).attr("id")!= 'datepicker' && $(this).attr("id")!= 'username_addons'){

                    if ($(this).change) {
                        var data = {
                            val_name:$(this).attr("name"),
                            val_flag         :$(this).val()
                        };
                        console.log(data)
                        ajax(data);
                    }
                }

            });
        $("input[type='number']").focusout(function() {


                if ($(this).focusout) {
                    var data = {
                        val_name:$(this).attr("name"),
                        val_flag         :$(this).val()
                    };
                    ajax(data);
                }


            });
        $("input[type='password']").change(function() {

				if($(this).attr("id")!= 'password_addons'){
                if ($(this).change) {
                    var data = {
                        val_name:$(this).attr("name"),
                        val_flag         :$(this).val()
                    };
                    ajax(data);
                }
			}

            });

        $('input:submit').click( function() {
                shure("Вы уверены, что хотите " + $(this).val() +"?");
                //console.log($(this).attr("name"),$(this).attr("type"));

            });

        $( function() {
                $( "#datepicker" ).datepicker({
                        onClose: function(){
                            var data = {
                                val_name          :$(this).attr("name"),
                                val_flag         :$(this).val()
                            };
                            //console.log(data);
                            ajax(data);
                        }
                    });
            } );

        $('#exp').click(function(){
                if ($('#status_export').is(':checked')){
                    alertify.alert('Сначала снимите чекбокс "Удалять файл ответа со статусами, пришедший с 1C"');
                    return false;
                }
                else{
                    console.log($('#ansfile').val())
                    $('#exp').attr("href", href_exp_orders + $.trim($('#ansfile option:selected').text()))
                }

            });

    });

