
function turnItemIncompleted(item_id)
{
    var inCompleteActionURL = WEBROOT + 'items/toggle_complete_state/' + item_id;
    $.ajax({
    url: inCompleteActionURL,
    type: "POST",
    data: { id : item_id },
    dataType: "text",
    success : function(response){
        //通信成功時
        var item_head_tr_id = '#item_' + item_id + '-head';
        var item_data_tr_id = '#item_' + item_id + '-data';
        $(item_head_tr_id).fadeOut(600).queue(function() {
            $(item_head_tr_id).remove();
        });
        $(item_data_tr_id).fadeOut(600).queue(function() {
            $(item_data_tr_id).remove();
        });
        return true;
    },
    error: function(){
        //通信失敗時の処理
        alert('通信失敗');
        return false;
    }
    });
}

var originHeaderLefts = [];
function toggle_column_for_dev_show_or_hide(selector)
{
    // 20180807時点ではheader-column-2が消える
    if ($(selector).prop('checked')) {
        $('td.column-for-dev, th.column-for-dev').each(function(){
            $(this).hide();
        })
        $('tr.needs-no-confirm').each(function(){
            $(this).hide();
        })

        $('.header-column-3').each(function(){
            $(this).offset({left: $('.header-column-1').offset().left + $('.header-column-1').outerWidth()});
        });
        $('.header-column-4').each(function(){
            $(this).offset({left: $('.header-column-3').offset().left + $('.header-column-3').outerWidth()});
        });
        $('.header-column-5').each(function(){
            $(this).offset({left: $('.header-column-4').offset().left + $('.header-column-4').outerWidth()});
        });
        $('.header-column-6').each(function(){
            $(this).offset({left: $('.header-column-5').offset().left + $('.header-column-5').outerWidth()});
        });
    } else {
        $('td.column-for-dev, th.column-for-dev').each(function(){
            $(this).show();
        })
        $('tr.needs-no-confirm').each(function(){
            $(this).show();
        })
        // 上記のif節と同じようにleft + widthで良いような気がするが
        // なぜかうまくいかないので最初のleftを記録しておき、復元する方式を採る
        // あと上のやつoffsetでleftを変えられるのにこっちはcssじゃないと全く効いてくれない。ちょっと意味がわからない
        $('.header-column-3').each(function(){
            $(this).css('left', originHeaderLefts[2]);
        });
        $('.header-column-4').each(function(){
            $(this).css('left', originHeaderLefts[3]);
        });
        $('.header-column-5').each(function(){
            $(this).css('left', originHeaderLefts[4]);
        });
        $('.header-column-6').each(function(){
            $(this).css('left', originHeaderLefts[5]);
        });
    }
    Cookies.set('hideColumnForDev', $(selector).prop('checked'));
}

$(function(){
    'use strict';

    for (var i = 1; i <= 6; i++) {
        originHeaderLefts.push($('.header-column-' + i).position().left);
    }
    $('.complete_button, .incomplete_button').click(function()
    {
        if ($(this).parents('td').hasClass('somebody-editing')) {
            return;
        }
        var button_id = $(this).attr('id');
        var item_id = button_id.split('-')[0];
        var message = $(this).hasClass('complete_button') ? '　検証完了状態にしますか？' : '　未完了に戻しますか？'
        if(!confirm('id = ' + item_id + message)){
            return false;
        }else{
            if (turnItemIncompleted(item_id)){
                var message = $(this).hasClass('complete_button') ? "'完了'状態にしました" : "'未完了'に戻しました"
                alert(message);
            }
        }
    })

    $('#hide-column-for-dev').click(function(){
        toggle_column_for_dev_show_or_hide('#' + $(this).attr('id') + ' input');
    })
})
