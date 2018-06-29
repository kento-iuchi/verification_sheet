
function synchronizeTwoTablesHeight()
{
    var header_tr = $("#header_table tr.view_part_item");
    var data_tr = $("#data_table tr.view_part_item");

    for(var i=0, l=header_tr.length; i<l;i++ ){
        var header_cells = header_tr.eq(i);
        var data_cells = data_tr.eq(i);

        var header_cells_height = header_cells.height();
        var data_cells_height = data_cells.height();

        if(header_cells_height > data_cells_height){
            data_cells.height(header_cells_height);
        } else if(data_cells_height > header_cells_height){
            header_cells.height(data_cells_height);
        }
    }

    var tableHeight = $("#header_table").height();
};

function measureTableWidth(table_id)
{
    var table_width = 0;
    var data_th = $(table_id + ' th');

    for (i=0, l = data_th.length; i < l ; i++){
        if (data_th.eq(i).css('display') != 'none'){
            table_width += data_th.eq(i).width();
        }
    }

    return table_width;
};

function changeScrollTbodyHeight()
{
    var tbody_height = 0;
    var table_tr = $('#header_table tbody tr');

    for (i=0, l = table_tr.length; i < l ; i++){
        if (table_tr.eq(i).css('display') != 'none'){
            tbody_height += table_tr.eq(i).height();
        }
    }

    if (tbody_height < 500) {
        $('tbody.scrollBody').height(tbody_height + table_tr.eq(-1).height());
    } else {
        $('tbody.scrollBody').height(500);
    }
    $('view_part_data').height($('tbody.scrollBody').height());

    return tbody_height;
}

$(function(){
    'use strict';

    synchronizeTwoTablesHeight();
    var init_data_table_width = measureTableWidth('#data_table') + 120;// スクロールバーのぶん適当に伸ばす
    $('#data_table').width(init_data_table_width);
    var init_header_table_width = $('#header_table').width();
    var init_scroll_tbody_height = changeScrollTbodyHeight();
    if (Cookies.get('hideColumnForDev') === 'true') {
        $('#hide-column-for-dev input').prop('checked', true);
        toggle_column_for_dev_show_or_hide('#hide-column-for-dev input');
    }

    var interval = 10;
    var timer;
    $('#header_table tbody.scrollBody').on('scroll', function(){
        clearTimeout(timer);
        timer = setTimeout(function() {
            $('#data_table tbody.scrollBody').scrollTop($('#header_table tbody.scrollBody').scrollTop());
        }, interval);
    })

    $('#data_table tbody.scrollBody').on('scroll', function(){
        clearTimeout(timer);
        timer = setTimeout(function() {
            $('#header_table tbody.scrollBody').scrollTop($('#data_table tbody.scrollBody').scrollTop());
        }, interval);
    })

    $('.incomplete_button').click(function()
    {
        var button_id = $(this).attr('id');
        var item_id = button_id.split('-')[0];
        if(!confirm('id = ' + item_id + '未完了に戻しますか？')){
            return false;
        }else{
            turnItemIncompleted(item_id);
        }
    })

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
            alert("'未完了'に戻しました");
            var item_head_tr_id = '#item_' + item_id + '-head';
            var item_data_tr_id = '#item_' + item_id + '-data';
            $(item_head_tr_id).fadeOut(600).queue(function() {
                $(item_head_tr_id).remove();
            });
            $(item_data_tr_id).fadeOut(600).queue(function() {
                $(item_data_tr_id).remove();
            });
        },
        error: function(){
            //通信失敗時の処理
            alert('通信失敗');
        }
        });
    }

    // 検証履歴詳細ボタンの処理
    var appearingHistoryIds = {};
    $(document).on('click', '.verification-history-detail-link', function()
    {
        var verificationHistoryComment = $(this).attr('data-comment')
        var verificationHistoryId = $(this).attr('data-id');

        if (!appearingHistoryIds[verificationHistoryId]) {
            appearingHistoryIds[verificationHistoryId] = $(this).parent().parents('td').height();
            var commentTd = '<tr><td id = "verification-history-comment_' + verificationHistoryId + '" colspan = "3">' +  verificationHistoryComment +'</td></tr>';
            $(this).parent().parent().after(commentTd);
        } else {

            var originHeight;
            for (var key in appearingHistoryIds) {
                if (key == verificationHistoryId) {
                    originHeight = appearingHistoryIds[key];
                    delete appearingHistoryIds[key];
                    break;
                }
            }
            $('#verification-history-comment_' + verificationHistoryId).parent().remove();
            $('#verification-history-comment_' + verificationHistoryId).remove();

            var recordId = $(this).parent().parents('td').attr('id').split('-')[0];
            $('#item_' + recordId + '-head').height(originHeight);
            $('#item_' + recordId + '-data').height(originHeight);

        }
        synchronizeTwoTablesHeight();
    });

    function toggle_column_for_dev_show_or_hide(selector)
    {
        if ($(selector).prop('checked')) {
            $('td.column-for-dev, th.column-for-dev').each(function(){
                $(this).hide();
            })
            $('tr.needs-no-confirm').each(function(){
                $(this).hide();
            })
            $('')
            $('#data_table').width(measureTableWidth('#data_table') + 120);// スクロールバーのぶん適当に伸ばす
            $('#header_table').width(measureTableWidth('#header_table') + 25);
            changeScrollTbodyHeight();
        } else {
            $('td.column-for-dev, th.column-for-dev').each(function(){
                $(this).show();
            })
            $('tr.needs-no-confirm').each(function(){
                $(this).show();
            })
            $('#data_table').width(init_data_table_width);
            $('#header_table').width(590);
            changeScrollTbodyHeight();
        }
        Cookies.set('hideColumnForDev', $(selector).prop('checked'));
        var old_view_part_data_left = $('#view_part_data').position().left;
        $('#view_part_data').offset({left : $('#header_table').width() + 8});
        $('#view_part_data').width($('#view_part_data').width() + (old_view_part_data_left - $('#view_part_data').position().left));
        synchronizeTwoTablesHeight();
    }

    $('#hide-column-for-dev').click(function(){
        toggle_column_for_dev_show_or_hide('#' + $(this).attr('id') + ' input');
    });

})
