function gettrueurl() {
    return $("#module_dir").val() + "ajax/ajaxCheckCompability.php";
}

function ajax_data(data){
    return  $.ajax({
            type: "POST",
            url: gettrueurl(),
            data: JSON.stringify(data, null, 2),
            success: function(msg) {
                if(msg == 'OK') {
                    showSuccessMessage("Успешно обновлено");
                }else{
					var message;
					//console.log(data.val_name)
					if ((data.val_name != 'phonelogin') && (data.val_name != 'on_stock'))
					message = "Модуль "+ data.val_name +"  не установлен в магазине";
					else
					message = "Доработка "+ data.val_name +"  не установлена в магазине";
					$('#'+data.val_name).attr('checked', false);

					showErrorMessage(message);
				}
            },
        });
}

$( function(){
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
                ajax_data(data);
            })
    });
