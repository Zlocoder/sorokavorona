function gettrueurl() {
    return $("#module_dir").val() + "ajax/ajaxPriceType.php";
}
function show_hide(id,data){
    if ($(id).is(':checked'))
    $(data).hide();
    else
    $(data).show();
}
function ajax_data(data){
    return  $.ajax({
            type: "POST",
            url: $("#module_dir").val()+"ajax/ajaxForm.php",
            data: JSON.stringify(data, null, 2),
            success: function(msg) {
                if(msg == 'OK') {

                    if(data.val_name == 'spec_price_off')
					clearedit();
					showSuccessMessage("Успешно обновлено");
                }
            },
        });
}
function ajax(data) {
    $.ajax({
            type: "POST",
            async: true,
            data: data,
            url: gettrueurl(),
            success: function(vhtml) {
                var separator = vhtml.indexOf("!");
                $("#prices_tabl").html(vhtml.substring(0,separator));
                $("#groups").html(vhtml.substring(separator+1));
            }
        });
}

function delgroup(id, main) {
    if (main == 1) {
        alertify.alert(
            "Нельзя удалить группу цен установленную по умолчанию!!!\r\n\r\nПерепроверьте настройки!!!"
        );
        showErrorMessage("Отмена");
        return;
    }
    var priseArray = [];
    $("select[name~='prise']  option:selected").each(function() {
            priseArray.push($(this).val());
        });
    alertify.set({
            labels: {
                ok     : "Отмена"
            }
        });
    if($.inArray($('#guid'+id).text(), priseArray)!=-1){
        alertify.alert(
            "Нельзя удалить данную группу цен , так как она связана с группой пользователей!!!\r\n\r\nПерепроверьте настройки!!!"
        );
        showErrorMessage("Отмена");
        return;
    }
    else{
        ajax("id=" + id + "&mode=delgroup");
        showSuccessMessage("Успешно обновлено");
    }

}

function clearedit() {
    ajax("mode=clear");
}
function reset() {
	var isGood = "Вы уверены, что хотите сбросить установки цен?";
    alertify.set({
            labels: {
                ok     : "Да",
                cancel : "Нет"
            },buttonReverse: true
        });
    alertify.confirm(isGood, function (e) {
            if (e) {
                ajax("mode=reset");
                showSuccessMessage("Успешно обновлено");
            } else {
                return;
            }
        });

}
function editgroup(id) {
    ajax("id=" + id + "&mode=editgroup");
}

function addgroup(id) {
    name = $("#price_name").val();
    guid = $("#price_guid").val();
    ajax("id=" + id + "&mode=addgroup&name=" + name + "&guid=" + guid)
    showSuccessMessage("Успешно обновлено");
}

function setmain(prev) {
    guid = $("input[name='main']:checked").val();
    name = $("input[name='main']:checked").attr('class');
    var isGood = "Вы уверены, что хотите установить тип цены <b>" + name + "</b> в качестве основной цены по умолчанию?";
    alertify.set({
            labels: {
                ok     : "Да",
                cancel : "Нет"
            },buttonReverse: true
        });
    alertify.confirm(isGood, function (e) {
            if (e) {
                ajax("&mode=setmain&guid=" + guid);
                $("#sale" + prev).attr('disabled', 'disabled');
                showSuccessMessage("Успешно обновлено");
            } else {
                $("#main" + prev).prop('checked', true);
                if(prev == undefined)
				$('input:radio[name=main]:checked').prop('checked', false);
            }
        });
}
function setsale(sale) {
    guid = $("input[name='sale']:checked").val();
    name = $("input[name='sale']:checked").attr('class');
    var priseArray = [];
    $("select[name~='prise']  option:selected").each(function() {
            priseArray.push($(this).val());
        });
    if($.inArray(guid, priseArray)!=-1){
        alertify.alert(
            "Нельзя установить данный тип цен в качестве акционной, так как она связана с группой пользователей!!!\r\n\r\nПерепроверьте настройки!!!"
        );
        $("#sale" + sale).prop('checked', true);
        console.log(sale)
        if(sale == undefined)
        $('input:radio[name=sale]:checked').prop('checked', false);
        showErrorMessage("Отмена");
        return;
    }
//console.log(sale)
    isGood = "Вы уверены, что хотите установить тип цены <b>" + name + "</b> в качестве акционной цены?";
    alertify.set({
            labels: {
                ok     : "Да",
                cancel : "Нет"
            },buttonReverse: true
        } );
    alertify.confirm(isGood, function (e) {
            if (e) {
                ajax("&mode=setsale&guid=" + guid);
                showSuccessMessage("Успешно обновлено");
            } else {

                $("#sale" + sale).prop('checked', true);
                if(sale == undefined)
				$('input:radio[name=sale]:checked').prop('checked', false);
            }
        });

}
var selected;
$(document).ready(function() {
        show_hide('#price_off','#show_prises_settings_blocks');
        show_hide('#price_off','#show_prises_settings');
        show_hide('#spec_price_off','#prise_groups');


    });
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
                show_hide('#price_off','#show_prises_settings_blocks');
                show_hide('#price_off','#show_prises_settings');
                show_hide('#spec_price_off','#prise_groups');
            })
    });

$(document ).on( "change","select", function() {
        previos = selected;
        var isGood =
        "Вы уверены, что хотите связать тип цены  <b>" +
        $('#' + $(this).attr('id') + ' option:selected').text() + "</b>  с группой  <b>" + ($('#group-name-' +$(this).attr('class')).text()) + "</b> ?";
        alertify.set({
                labels: {
                    ok     : "Да",
                    cancel : "Нет"
                },buttonReverse: true
            } );
        var id = $(this).attr('id');
        var val = $(this).val();
        alertify.confirm(isGood, function (e) {
                if (e) {
                    $.ajax({
                            type: "POST",
                            async: true,
                            data: "mode=setgroup&id_group=" + id + "&guid=" + val,
                            url: gettrueurl(),
                            success: showSuccessMessage(
                                "Успешно обновлено")
                        });
                } else {
                    $('#' + previos).prop('selected', true);
                    selected = previos;
                }
            });
    });

if ( $.browser.webkit ) {
    $(document ).on( "focus","select", function() {
            selected = $(this).children(":selected").attr("id");
            return selected;
        });
}else{
    $(document ).on( "click","select", function() {
            selected = $(this).children(":selected").attr("id");
            return selected;
        });

}
