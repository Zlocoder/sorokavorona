$(document).ready(function () {
    $('.ajax_add_to_compare_button').live('click', function (e){
        e.preventDefault();
        var url = window.location.pathname;
        var id_category = parseInt(url.substring(url.lastIndexOf('/') + 1));
        var id_product = parseInt($(this).data('id-product'));
        var method = 'add';
        var button = $(this);
        if ($(this).hasClass('added'))
            method = 'delete';
        var query = $.ajax({
            type: 'POST',
            url: '/modules/productcompare/productcompare-ajax.php',
            data: {id_product: id_product, method: method},
            dataType: 'json',
            success: function (result) {
                if (method == 'delete') {
                    button.removeClass('added');
                }
                else {
                    button.addClass('added');
                }
                // result.forEach(function(item) {
                //     console.log(item);
                // });
                updateCount(result);
            }
        });
    });
    $('.delete_cat_compare').live('click', function (e) {
        e.preventDefault();
        var method = 'deleteCat';
        var category = $(this);
        var url = window.location.pathname;
        var id_category_current = parseInt(url.substring(url.lastIndexOf('/') + 1));
        var id_category = $(this).attr('data-id-category');
        console.log($(this).attr('data-id-category'));
        var query = $.ajax({
            type: 'POST',
            url: '/modules/productcompare/productcompare-ajax.php',
            data: {method: method, id_category: id_category},
            dataType: 'json',
            success: function (result) {
                 category.parent().parent().replaceWith('');
                updateCount(result);
                if(id_category == id_category_current){
                    $('.ajax_add_to_compare_button.added').removeClass('added');
                }
            }
        });
    });
    $('.delete_prod_compare').live('click', function (e) {
        var method = 'delete';
        var id_product = $(this).attr('data-id-product');
        var product = $(this);
        console.log($(this).attr('data-id-category'));
        var query = $.ajax({
            type: 'POST',
            url:'/modules/productcompare/productcompare-ajax.php',
            data: {method: method, id_product: id_product},
            dataType: 'json',
            success: function (result) {
                product.parent().replaceWith('');
                updateCount(result);
            }
        });
    });
    $('.delete_prod_compare_cat').live('body','click',function (e) {
        e.preventDefault();
        var method = 'delete';
        var id_product = $(this).attr('data-id-product');
        var product = $(this);
        var query = $.ajax({
            type: 'POST',
            url:  '/modules/productcompare/productcompare-ajax.php',
            data: {method: method, id_product: id_product},
            dataType: 'json',
            success: function (result) {
                $('th[data-id-product='+id_product+']').replaceWith('');
                $('td[data-id-product='+id_product+']').replaceWith('');
                updateCount(result);
            }
        });
    });
});
function updateCount(result) {

    // $('.total_compare').text(result['total']);
    // for (var key in result) {
    //     $('.count_compare[data-id-category='+key+']').text('('+result[key]+')');
    // }
    $('.compare_product').replaceWith(result);
}