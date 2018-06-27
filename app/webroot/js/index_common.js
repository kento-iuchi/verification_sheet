
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
        // var headerTableTrHeight = header_cells[0].height();
    }

    var tableHeight = $("#header_table").height();
    $("#page_selecter").offset({top : tableHeight + 60});
};

$(function(){
    'use strict';

    var viewPartHeight = $('#header_table').outerHeight();
    $('#page_selecter').css('top', viewPartHeight + 'px');
    $('#page_selecter').css('display', 'block');
    synchronizeTwoTablesHeight();

    var interval = 1;
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
})
