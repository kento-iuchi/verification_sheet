function syncCellsHeight()
{
    itemsTableTr = $('#items-table >thead > tr, #items-table >tbody > tr');
    console.log(itemsTableTr);
    for(var i=0, l=itemsTableTr.length; i<l;i++ ){
        item_id = itemsTableTr.eq(i).attr('data-id');
        console.log(itemsTableTr.eq(i).attr('data-id'));
        console.log(itemsTableTr.eq(i).height());
        itemRow = itemsTableTr.eq(i).height();
        var maxTdHeight = 0;
        $('#item_' + item_id + '-data > td').each(function(){
            if ($(this).height() > maxTdHeight) {
                maxTdHeight = $(this).height();
            }
        });
        $('#item_' + item_id + '-data > td, ' + '#item_' + item_id + '-data > th').each(function(){
            $(this).height(maxTdHeight);
        });
        // itemRowsTd = itemsTableTr.eq(i).child('td').each(function(){
        //     console.log($(this).outerHeight())
        // })
    }
}

$(function(){
    'use strict';
    syncCellsHeight();
});
