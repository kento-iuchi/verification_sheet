var editing_items = [];
var editorToken;

function detectEditingItem(){
    $.ajax({
        url: WEBROOT + 'items/fetch_items_list_somebody_editing',
        type: "POST",
        data: {my_editor_token : editorToken},
        dataType: "text",
    }).done(function(response){
        var editing_items_latest = []
        if (response) {
            console.log(response);
            var somebody_editing_items = JSON.parse(response);
            for (var i = 0, l=somebody_editing_items.length; i < l ; i++){
                editing_items_latest.push(somebody_editing_items[i].item_id);
            }
            editing_items_latest.forEach(function(id){
                inhibitToEditItem(id);
            })
            editing_items = editing_items_latest;
        }
        var no_longer_editing_items = getArrayDiff(editing_items, editing_items_latest);
        no_longer_editing_items.forEach(function(id){
            permitToEditItem(id);
        })
    })
}

function inhibitToEditItem(item_id){
    $('#item_' + item_id + '-head td, #item_' + item_id + '-data td').addClass('somebody-editing');
}

function permitToEditItem(item_id){
    $('#item_' + item_id + '-head td, #item_' + item_id + '-data td').removeClass('somebody-editing');
}

function generateToken(token_length){
    var characters =
        "1234567890" +
        "abcdefghijkmlnopqrstuvwxyz" +
        "ABCDEFGHIJKMLNOPQRSTUVWXYZ";

    var token = "";

    cl= characters.length;
    for(var i=0; i<token_length; i++){
      token += characters[Math.floor(Math.random()*cl)];
    }
    return token;
}

function registerItemEditing(itemId)
{
    return $.ajax({
        url: WEBROOT + 'items/register_item_editing',
        type: "POST",
        data: {item_id : itemId, editor_token: editorToken},
        dataType: "text",
    })
}

function unRegisterItemEditing(itemId)
{
    return $.ajax({
        url: WEBROOT + 'items/unregister_item_editing',
        type: "POST",
        data: {item_id : itemId},
        dataType: "text",
    })
}

$(function(){
    'use strict';

    // リロードしたとき、自分が編集している項目の情報を
    //　editing_itemsケーブルから消去する
    if (Cookies.get('my_editing_item_id')) {
        unRegisterItemEditing(Cookies.get('my_editing_item_id')).done(function(response){
            console.log('delete editing item ' + Cookies.get('my_editing_item_id'));
            Cookies.remove('my_editing_item_id');
        }).fail(function(response){
            console.log('failed to delete editing item');
        })
    } else {
        console.log('failed to editing_item_id from cookie');
    }

    editorToken = Cookies.get('my_editor_token');
    //　cookieにトークンがなければ新しく生成して取得する
    if (!editorToken) {
        editorToken = generateToken(8);
        Cookies.set('my_editor_token', editorToken, { expires: 7 });
    }
    console.log(editorToken)

    detectEditingItem(editorToken);
    setInterval(function(){detectEditingItem(editorToken)}, 3000);

})
