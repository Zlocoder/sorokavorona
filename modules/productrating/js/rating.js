$(document).ready(function () {
    var vote_upd = '<div class="alert alert-success"><p>Вы успешно обновили свой голос</p></div>';
    var html = $('.product-rating');
    $(".rating-btn-design").live('click' , function (e) {
        e.preventDefault();
        var method = 'add';
        var design_rate = $('.rating-form-design').val();
        var query = $.ajax({
            type: 'POST',
            url: '/modules/productrating/productrating-ajax.php',
            data: {id_product: id_product,method:method,design_rate:design_rate},
            dataType: 'json',
            success: function (result) {
                updateRating(result);
            }
        });
    });
    $(".rating-btn-functionality").live('click' ,function (e) {
        e.preventDefault();
        var html = $('.product-rating');
        var method = 'add';
        var functionality_rate = $('.rating-form-functionality').val();
        var query = $.ajax({
            type: 'POST',
            url: '/modules/productrating/productrating-ajax.php',
            data: {id_product: id_product,method:method,functionality_rate:functionality_rate},
            dataType: 'json',
            success: function (result) {
                updateRating(result);
            }
        });
    });
    $(".rating-btn-quality").live('click' ,function (e) {
        e.preventDefault();
        var html = $('.product-rating');
        var method = 'add';
        var quality_rate = $('.rating-form-quality').val();
        var query = $.ajax({
            type: 'POST',
            url: '/modules/productrating/productrating-ajax.php',
            data: {id_product: id_product,method:method,quality_rate:quality_rate},
            dataType: 'json',
            success: function (result) {
                updateRating(result);
            }
        });
    });
    function updateRating(result) {
        $('.product-rating').replaceWith(result);
        $(html).prepend($(vote_upd).hide().fadeIn('slow'));
        setTimeout(function(){$('.alert').first().fadeOut('slow',function () {
            $(this).remove();
        })},3000);
    }
});