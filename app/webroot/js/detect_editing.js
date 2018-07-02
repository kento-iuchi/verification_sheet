function detectEditingItem(){
    $.ajax({
        url: WEBROOT + 'items/fetch_items_list_somebody_editing',
        type: "POST",
        // data: {id : recordId}
        // dataType: "text",
    }).done(function(response){
        // console.log(response);
        // console.log(typeof(response));
        // console.log(JSON.parse(response)[0]);
        // console.log(typeof(JSON.parse(response)));
        // JSON.parse(response).each(function(){
        //     console.log($(this)['id'])
        //     inhibitToEditItem($(this)['id']);
        // })
        var somebody_editing_items = JSON.parse(response);
        for (var i = 0, l=somebody_editing_items.length; i < l ; i++){
            inhibitToEditItem(somebody_editing_items[i].item_id);
            console.log(somebody_editing_items[i].item_id);
        }
        // for (var item_data in JSON.parse(response)) {
        //     console.log(item_data.id);
        // }
    })
}

function inhibitToEditItem(item_id){
    $('#item_' + item_id + '-head td, #item_' + item_id + '-data td').addClass('somebody-editing');
}

$(function(){
    'use strict';

    detectEditingItem();
    setInterval('detectEditingItem()', 3000);

})
