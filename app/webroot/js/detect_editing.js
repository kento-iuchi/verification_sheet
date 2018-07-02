var editing_items = [];
function detectEditingItem(){
    $.ajax({
        url: WEBROOT + 'items/fetch_items_list_somebody_editing',
        type: "POST",
    }).done(function(response){
        var editing_items_latest = []
        if (response) {
            var somebody_editing_items = JSON.parse(response);
            for (var i = 0, l=somebody_editing_items.length; i < l ; i++){
                editing_items_latest.push(somebody_editing_items[i].item_id);
            }
            editing_items_latest.forEach(function(id){
                inhibitToEditItem(id);
            })
            var no_longer_editing_items = getArrayDiff(editing_items, editing_items_latest);
            no_longer_editing_items.forEach(function(id){
                permitToEditItem(id);
            })
            editing_items = editing_items_latest;
        }
    })
}

function inhibitToEditItem(item_id){
    $('#item_' + item_id + '-head td, #item_' + item_id + '-data td').addClass('somebody-editing');
}

function permitToEditItem(item_id){
    $('#item_' + item_id + '-head td, #item_' + item_id + '-data td').removeClass('somebody-editing');
}

$(function(){
    'use strict';

    detectEditingItem();
    setInterval('detectEditingItem()', 3000);

})
