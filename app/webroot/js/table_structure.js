function syncCellsHeight()
{
    itemsTableTr = $('#items-table >thead > tr, #items-table >tbody > tr');
    for(var i=1, l=itemsTableTr.length; i<l;i++ ){
        item_id = itemsTableTr.eq(i).attr('data-id');
        itemRow = itemsTableTr.eq(i).height();
        var maxTdHeight = 0;
        $('#item_' + item_id + '-data > td').each(function(){
            if ($(this).height() > maxTdHeight) {
                maxTdHeight = $(this).height();
            }
        });
        maxTdHeight = maxTdHeight < 25 ? 25 : maxTdHeight;
        if ( i == l-1 ) { // 新規作成フォーム
            maxTdHeight = 45;
        }
        console.log(maxTdHeight);
        // $('#item_' + item_id + '-data > td, ' + '#item_' + item_id + '-data > th').each(function(){
        //     $(this).height(maxTdHeight);
        // });
        itemsTableTr.eq(i).children().each(function(){
            $(this).height(maxTdHeight);
        });
    }
}

$(function(){
    'use strict';

    syncCellsHeight();
    $()

    if (Cookies.get('hideColumnForDev') === 'true') {
        $('#hide-column-for-dev input').prop('checked', true);
        toggle_column_for_dev_show_or_hide('#hide-column-for-dev input');
    }
});
