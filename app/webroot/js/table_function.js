const axios = require('axios');
var uneditableColumnNames = ['id', 'elapsed', 'grace_days_of_verification_complete', 'created', 'modified', 'confirm_comment', 'author_id'];
var controller_name;
var editCellId; //
var selectedTd; //　editCellIdのセレクタ　上と何が違うのかよくわからん 実際に使われてる行がずっと下にある…
var recordId; // data-idをセルごとに与えれば（もしくは親のtrからとってくる）すれば不要な気がする…
var columnName; // data-column-nameなどをtdに与えて処理するようにしたい　毎度splitするのはパフォーマンス的によくなさそう
var formId; // その場編集で生成するフォームのid
var postValue; // DBに保存する値　postingValueとかのほうがいいかも
var isEditing = false;

var editStartingTime;
function createEditForm(editCellId)
{
    var editCellSelector  = '#' + editCellId;
    var recordId   = $('#' + editCellId).parent().attr('data-id');
    var columnName = $('#' + editCellId).attr('data-column');
    var initialText = String($(editCellSelector).html());
    // いくつかの項目を編集できないようにする
    if(!$(editCellSelector).hasClass('editable-cell')){
        return 'uneditable_cell';
    }

    var today = new Date();
    getEditStartingTime().done(function(response){
        editStartingTime = response;
        console.log('edit starting time: ' + editStartingTime);

        initialText = initialText.replace(/<br>|<\/br>/g, '&&NEWLINE&&');
        initialText = initialText.replace(/<.+?>/g, '');
        initialText = initialText.replace(/&&NEWLINE&&/g, '\n');
        initialText = initialText.trim();

        formId = editCellId + '_form';
        if (columnName == 'needs_supp_confirm') {
            var form = "<select id = '" + formId + "'>" +
                       "<option value='1'>必要</option>" +
                       "<option value='0'>不要</option>" +
                       "</select>";
            $(editCellSelector).html(form);
        } else if (columnName == 'division') {
            var form = "<select id = '" + formId + "'>" +
                       "<option value='改善' id='improvement'>改善</option>" +
                       "<option value='機能追加' id='adding_function'>機能追加</option>" +
                       "<option value='バグ' id = 'debug'>バグ</option>" +
                       "</select>";
            $(editCellSelector).html(form);
        } else if (columnName == 'status') {
            var form = "<select id = '" + formId + "'>" +
                       "<option value='差し戻し'>差し戻し</option>" +
                       "<option value='コードレビュー中'>コードレビュー中</option>" +
                       "<option value='改修中'>改修中</option>" +
                       "<option value='技術二重チェック中'>技術二重チェック中</option>" +
                       "<option value='サポート・営業確認中'>サポート・営業確認中</option>" +
                       "</select>";
            $(editCellSelector).html(form);
        } else if (columnName == "due_date_for_release"
                 ||columnName == "pullrequest"
                 ||columnName == "pullrequest_update"
                 ||columnName == "tech_release_judgement"
                 ||columnName == "supp_release_judgement"
                 ||columnName == "sale_release_judgement"
                 ||columnName == "scheduled_release_date"
                 ||columnName == "merge_finish_date_to_master"
        ){
            var form = '<input type="text" id="' + formId + '" size="10" />';// textareaではダメっぽい
            $(editCellSelector).html(form);
            $('#'+formId).datepicker({ dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, defaultDate: 0});
            $('#'+formId).datepicker('setDate', initialText);
            $('#'+formId).datepicker('show');
        } else if (columnName == "author_id"){
            var authorNames = $('th.author-column').attr('data-author-options');
            authorNames = JSON.parse(authorNames);
            var form = "<select id = '" + formId + "'>";
            var defaultValue = 5
            $.each(authorNames, function(index, name){
                if(name == initialText && initialText){
                    defaultValue = index;
                }
                var option = '<option value="' + (index) + '">' + name + '</option>';
                form += option
            });
            form += '</select>';
            $(editCellSelector).html(form);
            $('#' + formId).val(defaultValue);
        } else if (columnName == "verifier_id"){
            var verifierNames = $('th.verifier-column').attr('data-verifier-options');
            verifierNames = JSON.parse(verifierNames);
            var form = "<select id = '" + formId + "'>";
            var defaultValue = 5
            $.each(verifierNames, function(index, name){
                if(name == initialText && initialText != ''){
                    defaultValue = index;
                }
                var option = '<option value="' + (index) + '">' + name + '</option>';
                form += option
            });
            form += '</select>';
            $(editCellSelector).html(form);
            $('#' + formId).val(defaultValue);
        } else if (columnName == 'manual_exists') {
            var form = "<select id = '" + formId + "'>"
                     + '<option value="1">◯</option>'
                     + '<option value="0">✕</option>'
                     + "</select>";
            $(editCellSelector).html(form);
            $('#' + formId).val(initialText == '◯' ? 1 : 0);
        } else if (columnName == 'pivotal_point') {
            var form = '<input type="number" id="' + formId + '">';
            $(editCellSelector).html(form);
            $('#' + formId).val(parseInt(initialText, 10));
        } else {
            var fontsize_div = $('<div style="display:none;font-size:1em;margin:0;padding:0;height:auto;line-height:1;border:0;">&nbsp;</div>');
            var fontsize = fontsize_div.appendTo(editCellSelector).height();
            var folm_cols = Math.floor(0.012 * fontsize * $(editCellSelector).width());
            fontsize_div.remove();
            var form = "<div style='text-align: center;'><textarea rows= '3' cols='" + folm_cols + "' " + "id ='" + formId + "'>" + initialText + "</textarea></div>";
            $(editCellSelector).html(form);
        }
        $('#'+formId).css('width', '90%');
        $('#'+formId).focus();
        // セレクトボックスの初期値設定
        if ($.inArray(initialText, ['改善',　'機能追加', 'バグ',　'差し戻し', 'コードレビュー中',
                                    '改修中', '技術二重チェック中', 'サポート・営業確認中',]) != -1){
            $('#' + formId).val(initialText);
        }

        return formId;
    }).fail(function(response){
        console.log('failer to get edit starting time');
        return null;
    });
}

function getEditStartingTime(){
    return $.ajax({
        type: 'GET',
        url: WEBROOT + 'items/get_time',
    })
}

function fetchLastUpdatedTime(recordId, controllerName)
{
    return $.ajax({
        url: WEBROOT + controllerName + '/fetch_last_updated_time',
        type: "POST",
        data: {id : recordId},
        dataType: "text",
    })
}

function postToEditAction(controller_name, selectedTd, postValue, __editStartingTime)
{
    var id   = $(selectedTd).parent().attr('data-id');
    var columnName = $(selectedTd).attr('data-column');

    var lastUpdatedTime
    let params = new URLSearchParams();
    params.append('id', id);
    axios.post(
        WEBROOT + controller_name + '/fetch_last_updated_time',
        params,
    ).then((response) => {
        lastUpdatedTime = response.data;
        console.log('[check]last update time: ' + lastUpdatedTime);
        console.log('[check]edit starting time: ' + __editStartingTime);
        /*
        最終更新時間　> 編集開始時間の場合、
        ユーザが編集している最中にレコードが更新されたとみなす。
        最終更新時間 - 編集開始時間が1000以上という条件にしているのは、１ユーザーが高速で編集セルを切り替えた際に
        誤反応させないため
        */
        if ((lastUpdatedTime - __editStartingTime) > 1){
            if(confirm('他のユーザーによってレコードが更新されたため、リロードします。入力中の内容をクリップボードにコピーしますか？')){

                $('body').append('<textarea id="temp-clipboard-field"></textarea>');
                $('#temp-clipboard-field').val(postValue);
                $('#temp-clipboard-field').select();

                document.execCommand('copy');
            }
            location.reload();
            finishEdit();
            return;
        }

        var editActionUrl = WEBROOT + controller_name + '/edit/';
        // postValue = replaceSlashAndColon(postValue);
        postValue = postValue.replace(/\r\n/g, '&&NEWLINE&&');
        postValue = postValue.replace(/\r/g, '&&NEWLINE&&');
        postValue = postValue.replace(/\n/g, '&&NEWLINE&&');
        if(postValue.length == 0){
            postValue = '*EMPTY*';
        }
        var today = new Date();
        lastUpdatedTime = Math.floor(today.getTime() / 1000);
        $.ajax({
            url: editActionUrl,
            type: "POST",
            data: { id : id, column_name: columnName, content: postValue},
            dataType: "text",
        }).done(function(response){
            //通信成功時
            var textEdited = postValue;
            if(textEdited == '*EMPTY*'){
                textEdited = '';
            }
            // textEdited = restoreSlashAndColon(textEdited);
            textEdited = textEdited.replace(/&&NEWLINE&&/g, '</br>');
            if(columnName == 'chatwork_url'
            　　|| columnName == 'github_url'
            　　|| columnName == 'verification_enviroment_url'){
                textEdited = '<a href="' + textEdited + '">' + textEdited + '</a>';
            }

            $(selectedTd).html(recordtext(textEdited));
            if (columnName == 'pullrequest') {
                var pullrequestDate = new Date($('#' + id + '-pullrequest').text());
                var todayDate = new Date();

                $('#' + id + '-elapsed').html(recordtext(Math.round((todayDate - pullrequestDate)/86400000)));
            }
            if (columnName == 'pullrequest_update' || columnName == 'due_date_for_release') {
                var dueDateForRelease = new Date($('#' + id + '-due_date_for_release').text());
                var todayDate = new Date();

                var graceDaysOfVerificationComplete = Math.round((dueDateForRelease - todayDate)/86400000);
                graceDaysOfVerificationComplete = isNaN(graceDaysOfVerificationComplete) ? '' : graceDaysOfVerificationComplete;
                $('#' + id + '-grace_days_of_verification_complete').html(recordtext(graceDaysOfVerificationComplete));
            }
            if (columnName == 'due_date_for_release') {
                var priority = ['不要', '低', '中', '高'];
                $(selectedTd).html(priority[textEdited]);
                if (textEdited == '3'){
                    $(selectedTd).addClass('high_priority');
                } else {
                    $(selectedTd).removeClass('high_priority');
                }
            }
            if (columnName == "author_id") {
                var authorNames = $('th.author-column').attr('data-author-options');
                authorNames = JSON.parse(authorNames);
                $(selectedTd).html(recordtext(authorNames[postValue]));
            }
            if (columnName == "verifier_id") {
                var verifierNames = $('th.verifier-column').attr('data-verifier-options');
                verifierNames = JSON.parse(verifierNames);
                $(selectedTd).html(recordtext(verifierNames[postValue]));
            }
            if (columnName == "manual_exists") {
                var manual_exists_char = postValue == 1 ? '◯' : '✕';
                $(selectedTd).html(recordtext(manual_exists_char));
            }
            if (columnName == "needs_supp_confirm") {
                if (postValue == 1) {
                    var needs_supp_confirm_char = '必要';
                    $('#item_' + id + '-data').removeClass('needs-no-confirm');
                } else {
                    var needs_supp_confirm_char = '不要';
                    $('#item_' + id + '-data').addClass('needs-no-confirm');
                }
                $(selectedTd).html(recordtext(needs_supp_confirm_char));
            }
            if (columnName = 'merge_finish_date_to_master') {
                if (textEdited.length != 0){
                    $('#' + id + '-scheduled_release_date').html(recordtext($('#next-release-date').text()));
                }
            }
            var table_id = $(selectedTd).parents('table').attr('id');
            // syncTwoTablesHeight(table_id);
            updateStyles(id);
        }).fail(function(){
            //通信失敗時の処理
            alert('通信失敗');
        })
    }).catch(function(response){
        alert('最終編集時間の取得に失敗しました');
        return;
    })
};

function replaceSlashAndColon(originText)
{
    var textAfterReplacement = originText;
    textAfterReplacement = textAfterReplacement.replace(/\//g, "&&SLASH&&");
    textAfterReplacement = textAfterReplacement.replace(/:/g, "&&COLON&&");
    return textAfterReplacement;
};

function restoreSlashAndColon(textAfterReplacement)
{
    var originText = textAfterReplacement;
    originText = originText.replace(/&&SLASH&&/g, "/");
    originText = originText.replace(/&&COLON&&/g, ":");
    return originText;
}

function finishEdit(){
    editCellId = '';
    isEditing = false;
    controller_name = null;
    return;
}

function recordtext(text){
    return '<span class="record_text">' + text + '</span>'
}

function updateStyles(itemId = null)
{
    if (!itemId){
        $('td.record.id-column').each(function(){
            var targetItemId = $(this).attr('data-id');
            updateItemLineStyle(targetItemId);
        });
    } else {
        updateItemLineStyle(itemId);
    }
}

function updateItemLineStyle(itemId = null)
{
    var dueDateTdSelector = '#' + itemId + '-due_date_for_release';
    if($(dueDateTdSelector).hasClass('high_priority')){
        $(dueDateTdSelector).css({'color': 'red', 'font-weight': 'bold', 'font-size': '16px'});
    } else {
        $(dueDateTdSelector).css({'color': 'black', 'font-weight': 'normal', 'font-size': '12px'});
    }

    var graceDays = parseInt($('#' + itemId + '-grace_days_of_verification_complete').text(), 10);
    var tdColor = $('#item_' + itemId + '-data').css('background');
    var fontColor = '#000000';
    if(graceDays <= -1){
        tdColor = '#000000';
        fontColor = '#FFFFFF';
    } else if(graceDays <= 1){
        tdColor = '#ba2636';
        fontColor = '#FFFFFF';
    } else if(graceDays <= 3){
        tdColor = '#f08300';
    } else if(graceDays <= 5){
        tdColor = '#f5e56b';
    }

    $('#item_' + itemId + '-data > td').each(function(){
        $(this).css('background', tdColor);
        $(this).children('span').css('color', fontColor);
    });

}

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
    $('#item_' + item_id + '-data > td').addClass('somebody-editing');
}

function permitToEditItem(item_id){
    $('#item_' + item_id + '-data > td').removeClass('somebody-editing');
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

function turnItemCompleted(item_id)
{
    var completeActionURL = WEBROOT + 'items/toggle_complete_state/' + item_id;
    $.ajax({
    url: completeActionURL,
    type: "POST",
    data: { id : item_id },
    dataType: "text",
    success : function(response){
        //通信成功時
        alert("'完了'にしました");
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

// 検証履歴(確認コメント)新規作成フォーム生成
function createAddVerifivationHistoryForm(item_id, dataColumn)
{
    if (dataColumn == 'verification_history') {
        var controllerName = 'verification_histories';
        var options = $('th.verifier-column').attr('data-verifier-options');
    } else if (dataColumn == 'response_to_confirm_comment') {
        var controllerName = 'confirm_comment_responses';
        var options = $('th.author-column').attr('data-author-options');
    }
    options = JSON.parse(options);
    var name_selector =  '<div><select id = "' + item_id + '-' + dataColumn + '-name-selector">';
    $.each(options, function(index, name){
        index = parseInt(index);
        name_selector += '<option value="' + index + '">' + name + '</option>';
    });
    name_selector += '</select></div>';
    $('#' + item_id + '-' + dataColumn + ' div.comment-input-area').append(name_selector);

    var comment_form = '<div><textarea rows = 5 id = "' + item_id + '-' + dataColumn + '-comment_form"></textarea></div>';
    $('#' + item_id + '-' + dataColumn + ' div.comment-input-area').append(comment_form);

    var submit_button = '<div><button type = "button" class = "add-comment-button">保存</button></div>';
    $('#' + item_id + '-' + dataColumn + ' div.comment-input-area').append($(submit_button).click(() => {saveConfirmComment(item_id, dataColumn, controllerName)}));

    $('#' + item_id + '-' + dataColumn + '-name-selector').focus();
}

// 検証履歴(確認コメント)保存ボタンが押されたときの処理
function saveConfirmComment(itemId, dataColumn, controllerName)
{
    var commenterId = $('#' + itemId + '-' + dataColumn + '-name-selector').val();
    var comment = $('#' + itemId + '-' + dataColumn + '-comment_form').val();
    var editActionUrl = WEBROOT + controllerName + '/save';

    var targetTdId = itemId + '-' + dataColumn
    // tdのidがitemId とdata-columnでできていることが暗黙のルールになってしまっているが、他にいいやり方を思いつかなかた…
    $.ajax({
    url: editActionUrl,
    type: "POST",
    data: { item_id : itemId, commenter_id: commenterId, comment: comment },
    dataType: "text",
    success : function(response)
    {
        if($('#' + targetTdId + ' table').length == 0){
            $('#' + targetTdId).append('<table class="comment-table"><tbody></tbody></table>');
        }
        var verifierName = $('#' + itemId + '-' + dataColumn  + '-name-selector option:selected').text();
        var today = new Date();
        console.log(today);
        var todayMonth = ('0' + (today.getMonth() + 1)).slice(-2);
        var todayDate = ('0' + today.getDate()).slice(-2);
        var newHistory =   '<tr class="comment-table-header">'
                         + '<td class="comment-table-header" style="background:#838b0d; color:white">' + verifierName + '</td>'
                         + '<td class="comment-table-header" style="background:#838b0d; color:white">'
                         + today.getFullYear() + '-' + todayMonth + '-' + todayDate + ' ' + today.toLocaleTimeString() //' ' + + ':' + + ':' +
                         + '</td>'
                         + '</tr>'
                         + '<tr class="comment-content" data-id="' + response+ '" data-controller="' + controllerName + '">'
                         + '<td class="editable-comment td-of-table-in-row record editable-cell" colspan="2" data-column="' + dataColumn + '"'
                         + 'id="' + response + '-' + dataColumn + '-comment">' + recordtext(comment) + '</td>'
                         + '</tr>';
        console.log(newHistory);
        $('#' + targetTdId + ' table').append(newHistory).trigger('create');
        // $('#verification-history-table td').css('background', '#838b0d');
        $('#' + itemId + '-add-comment').show();
        $('#' + itemId + '-' + dataColumn + ' div.comment-input-area').empty();
        syncCellsHeight();
        // syncTwoTablesHeight();
    },
    error: function()
    {
        alert('通信失敗');
    }
    });
}

$(function(){
    'use strict';

    if (Cookies.get('hideColumnForDev') === 'true') {
        $('#hide-column-for-dev input').prop('checked', true);
        toggle_column_for_dev_show_or_hide('#hide-column-for-dev input');
    }
    // updateStyles(); // データに応じて見た目を初期化する（ハイライトなど）
    updateStyles();
    // レコードのセルをダブルクリックした際の処理
    $(document).on('dblclick', 'td.record', function()
    // $('td.record').dblclick(function()
    {
        event.stopPropagation();
        if (isEditing){
            var recordId   = $(this).parent().attr('data-id');
            var columnName = $(this).attr('data-column');
            postValue = $('#' + formId).val();
            console.log(postValue);
            postToEditAction(controller_name, '#' + editCellId, postValue, editStartingTime);
            if (!$('#' + editCellId).hasClass('td-of-table-in-row')) {
                unRegisterItemEditing($('#' + editCellId).parent().attr('data-id')).done(function(response){
                    console.log('delete editing item ' + Cookies.get('my_editing_item_record_id'));
                    Cookies.remove('my_editing_item_record_id');
                }).fail(function(response){
                    console.log('failed to delete editing item');
                });
            }
        }

        if($(this).attr('id') == editCellId){// 編集中のセルをもう一度ダブルクリックしたなら、何も編集していない状態に
            finishEdit();
            return;
        }

        if ($(this).hasClass('somebody-editing')) {
            finishEdit();
            return;
        }
console.log('hoge');
        editCellId = $(this).attr('id');
        console.log(editCellId);
        formId = createEditForm(editCellId); // 編集可能かどうかのチェックとフォームの生成を分けるべき
        console.log(formId);
        if (formId == 'uneditable_cell'){
            finishEdit();
            return;
        }
        if (!$('#' + editCellId).hasClass('td-of-table-in-row')) {
            registerItemEditing($(this).parent().attr('data-id')).done(function(response){
                if (response){
                    var editing_item_id = response;
                    Cookies.set('my_editing_item_id', $('#' + editCellId).parent().attr('data-id'));
                    console.log('registered editing item [item_id]:' + $('#' + editCellId).parent().attr('data-id'));
                }
            }).fail(function(response){
                console.log('failed to register item editing');
            });
        }

        controller_name = $(this).parent().attr('data-controller');

        isEditing = true;
    });

    // 検証履歴新規作成
    $('.add-comment').click(function(object)
    {
        createAddVerifivationHistoryForm($(this).parents('tr').attr('data-id'), $(this).parent().attr('data-column'));
        var button_id = $(this).attr('id');
        $('#' + button_id).hide();
        $(this).parent('td').height($(this).parent('td').height() + 120);
        syncCellsHeight();
        // syncTwoTablesHeight();
    })

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
});
