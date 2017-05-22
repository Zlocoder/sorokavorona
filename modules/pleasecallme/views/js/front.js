/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    Goryachev Dmitry    <dariusakafest@gmail.com>
 * @copyright 2007-2015 Goryachev Dmitry
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

$(function () {

    $.fn.setCenterPosAbsBlockPCM = function ()
    {
        var offsetElemTop = 20;
        var scrollTop = $(document).scrollTop();
        var elemWidth = $(this).width();
        var windowWidth = $(window).width();
        $(this).css({
            top: ($(this).height() > $(window).height() ? scrollTop + offsetElemTop : scrollTop + (($(window).height()-$(this).height())/2)),
            left: ((windowWidth-elemWidth)/2)
        });
    };

    var stage = $('.stage_please_call_me');
    var form = $('.please_call_me');

    $('.submitPleaseCallMe').live('click', function (e) {
        e.preventDefault();
        if (form.is('.loading'))
            return false;
        var data = {};
        data['ajax_pcm'] = true;
        data['method'] = 'submit';
        form.find(':input:not(button)').each(function () {
            data[$(this).attr('name')] = $(this).val();
        });
        form.find('.form_body').addClass('loading');
        var url = document.location.href.replace(document.location.hash, '');
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (r) {
                form.find('.form_body').removeClass('loading');
                form.find('.form_errors').remove();
                form.find('.form_body').addClass('form_success').html(r);
            },
            error: function (r)
            {
                form.find('.form_body').removeClass('loading');
                form.find('.form_errors').remove();
                if (typeof r.responseJSON != 'undefined')
                    form.find('.form_body').before('<div class="form_errors"><ul><li>'+ r.responseJSON.join('</li><li>') +'</li></ul></div>');
                else if (typeof r.responseText != 'undefined')
                    form.find('.form_body').before('<div class="form_errors"><ul><li>'+ $.parseJSON(r.responseText).join('</li><li>') +'</li></ul></div>');
            }
        });
    });

    $('.link_show_please_call_me').live('click', function (e) {
        e.preventDefault();
        stage.stop(true, true).fadeIn(500);
        form.stop(true, true).fadeIn(500).setCenterPosAbsBlockPCM();
        form.find('.time_slider').rangeSlider({
            range: {min: 0, max: 24},
            bounds: {min: 0, max: 24},
            defaultValues:{min: 8, max: 20},
            arrows:false,
            formatter:function(val){
                return parseInt(val) + hourText;
            }
        });
        form.find('.time_slider').bind("valuesChanged", function(e, data){
            form.find('[name=time_from]').val(parseInt(data.values.min));
            form.find('[name=time_to]').val(parseInt(data.values.max));
        });
    });
    stage.live('click', function (e) {
        e.preventDefault();
       stage.stop(true, true).fadeOut(500);
       form.stop(true, true).fadeOut(500);
    });

    form.find('.close_form').live('click', function (e) {
        e.preventDefault();
        stage.stop(true, true).fadeOut(500);
        form.stop(true, true).fadeOut(500);
    });

    $(window).resize(function () {
        form.setCenterPosAbsBlockPCM();
    });
});