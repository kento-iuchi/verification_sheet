$(function(){
    'use strict';

    var viewPartHeight = $('#view_part_header').outerHeight();
    $('#page_selecter').css('top', viewPartHeight + 'px');
    $('#page_selecter').css('display', 'block');
    synchronizeTwoTablesHeight();

    // ダブルクリックでその場変種
    var uneditableColumnNames =  ['id', 'elapsed', 'grace_days_of_verification_complete', 'created', 'modified'];
    var editCellId;
    var selectedTd;
    var recordId;
    var columnName;
    var formId;
    var currentText;
    var isEditing = false;
    $('#view_part td.record').dblclick(function()
    {
        if (isEditing){
            var recordId   = editCellId.split('-')[0];
            var columnName = editCellId.split('-')[1];
            currentText = $('#' + formId).val();
            postToEdit('#' + editCellId, recordId, columnName, currentText);
        }

        if($(this).attr('id') == editCellId){// 編集中のセルをもう一度ダブルクリックしたなら、何も編集していない状態に
            finishEdit();
            return;
        }
        editCellId = $(this).attr('id');

        formId = createEditForm(editCellId);
        if (formId == 'uneditable_cell'){
            finishEdit();
            return;
        }

        isEditing = true;
    });

    // tabキーでその場編集
    $(window).keydown(function(e)
    {
        if(e.keyCode == 9){
            if(isEditing){
                var recordId   = editCellId.split('-')[0];
                var columnName = editCellId.split('-')[1];
                currentText = $('#' + formId).val();
                postToEdit('#' + editCellId, recordId, columnName, currentText);

                if (columnName == 'status' && !event.shiftKey){
                    var nextId = recordId + '-' + 'category';
                } else if (columnName == 'category' && event.shiftKey){
                    var nextId = recordId + '-' + 'status';
                } else {
                    var nextId = event.shiftKey ? $('#' + editCellId).prev().attr('id') : $('#' + editCellId).next().attr('id');
                }
                var nextColumnName = nextId.split('-')[1];
                if ($.inArray(nextColumnName, uneditableColumnNames) != -1){
                    if (nextColumnName == 'created' || nextColumnName == 'id'){
                        finishEdit();
                        return;
                    }
                    var nextId = event.shiftKey ? $('#' + nextId).prev().attr('id') : $('#' + nextId).next().attr('id');
                    nextColumnName = nextId.split('-')[1];
                }
                formId = createEditForm(nextId);
                editCellId = nextId;
                return false;
            } else if ($(':focus').attr('id') == 'ItemStatus'){
                $('#ItemCategory').focus();
                return false;
            }
        }
    })

    function createEditForm(editCellId)
    {
        var editCellSelector  = '#' + editCellId;
        var recordId    = editCellId.split('-')[0]// 要素idは 'レコードid_カラム名'という形式
        var columnName  = editCellId.split('-')[1];
        var initialText = String($(editCellSelector).html());
        // いくつかの項目を編集できないようにする
        if($.inArray(columnName, uneditableColumnNames) != -1){
            return 'uneditable_cell';
        }

        initialText = initialText.replace(/<br>|<\/br>/g, '&&NEWLINE&&');
        initialText = initialText.replace(/<.+?>/g, '');
        initialText = initialText.replace(/&&NEWLINE&&/g, '\n');
        initialText = initialText.trim();

        formId = editCellId + '_form';
        if (columnName == 'division') {
            var form = "<select id = '" + formId + "'>" +
                       "<option value='改善' id='improvement'>改善</option>" +
                       "<option value='機能追加' id='adding_function'>機能追加</option>" +
                       "<option value='バグ' id = 'debug'>バグ</option>" +
                       "</select>";
            $(editCellSelector).html(form);
        } else if (columnName == 'status') {
            var form = "<select id = '" + formId + "'>" +
                       "<option value='コードレビュー中' id='improvement'>コードレビュー中</option>" +
                       "<option value='改修中' id='adding function'>改修中</option>" +
                       "<option value='技術二重チェック中' id = 'debug'>技術二重チェック中</option>" +
                       "<option value='サポート・営業確認中' id = 'debug'>サポート・営業確認中</option>" +
                       "</select>";
            $(editCellSelector).html(form);
        } else if (columnName == 'confirm_priority') {
            var form = "<select id = '" + formId + "'>" +
                       "<option value='0' id='improvement'>不要</option>" +
                       "<option value='1' id='adding function'>低</option>" +
                       "<option value='2' id = 'debug'>中</option>" +
                       "<option value='3' id = 'debug'>高</option>" +
                       "</select>";
            var priority = {'不要':0, '低':1, '中':2, '高':3};
            $(editCellSelector).html(form);
            $('#' + formId).val(priority[initialText]);
        } else if (columnName == "pullrequest"
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
        } else {
            var fontsize_div = $('<div style="display:none;font-size:1em;margin:0;padding:0;height:auto;line-height:1;border:0;">&nbsp;</div>');
            var fontsize = fontsize_div.appendTo(editCellSelector).height();
            var folm_cols = Math.floor(0.012 * fontsize * $(editCellSelector).width());
            fontsize_div.remove();
            var form = "<div style='text-align: center;'><textarea rows= '3' cols='" + folm_cols + "' " + "id ='" + formId + "'>" + initialText + "</textarea></div>";
            $(editCellSelector).html(form);
        }
        synchronizeTwoTablesHeight();
        $('#'+formId).focus();
        // セレクトボックスの初期値設定
        if ($.inArray(initialText, ['改善',　'機能追加', 'バグ',　'コードレビュー中',
                                    '改修中', '技術二重チェック中', 'サポート・営業確認中',]) != -1){
            $('#' + formId).val(initialText);
        }

        return formId;
    }

    function postToEdit(selectedTd, id, columnName, currentText)
    {
        currentText = replaceSlashAndColon(currentText);
        currentText = currentText.replace(/\r\n/g, '&&NEWLINE&&');
        currentText = currentText.replace(/\r/g, '&&NEWLINE&&');
        currentText = currentText.replace(/\n/g, '&&NEWLINE&&');
        if(currentText.length == 0){
            currentText = '*EMPTY*';
        }
        var editActionUrl = WEBROOT + 'items/edit/' + id + '/' + columnName + '/' + currentText;

        $.ajax({
        url: editActionUrl,
        type: "POST",
        data: { id : id, columnName: columnName, content: currentText },
        dataType: "text",
        success : function(response){
            //通信成功時
            var textEdited = currentText;
            if(textEdited == '*EMPTY*'){
                textEdited = '';
            }
            textEdited = restoreSlashAndColon(textEdited);
            textEdited = textEdited.replace(/&&NEWLINE&&/g, '</br>');
            if(columnName == 'chatwork_url'
            　　|| columnName == 'github_url'
            　　|| columnName == 'verification_enviroment_url'){
                textEdited = '<a href="' + textEdited + '">' + textEdited + '</a>';
            }

            $(selectedTd).html(textEdited);
            if (columnName == 'pullrequest_update' || columnName == 'scheduled_release_date'){
                var pullrequestDate = new Date($('#' + id + '-pullrequest_update').text());
                var scheduledReleaseDate = new Date($('#' + id + '-scheduled_release_date').text());
                var todayDate = new Date();
                // console.log(Math.round((todayDate - pullrequestDate)/86400000));
                $('#' + id + '-elapsed').text(Math.round((todayDate - pullrequestDate)/86400000));
                $('#' + id + '-grace_days_of_verification_complete').text(Math.round((scheduledReleaseDate - todayDate)/86400000));
            }
            if (columnName == 'confirm_priority') {
                var priority = ['不要', '低', '中', '高'];
                $(selectedTd).html(priority[textEdited]);
            }
            synchronizeTwoTablesHeight();
        },
        error: function(){
            //通信失敗時の処理
            alert('通信失敗');
        }
        });
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
        return;
    }

    $('div.input').click(function()
    {
        if (isEditing) {
            var recordId   = editCellId.split('-')[0];
            var columnName = editCellId.split('-')[1];
            currentText = $('#' + formId).val();
            postToEdit('#' + editCellId, recordId, columnName, currentText);
            finishEdit();
        }
    })

    function synchronizeTwoTablesHeight()
    {
        var header_tr = $("#view_part_header tr");
        var data_tr = $("#data_table tr");
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

        var tableHeight = $("#view_part_header").height();
        $("#page_selecter").offset({top : tableHeight + 20});
    };

    // 完了ボタンを押した際の処理
    $('.complete_button').click(function()
    {
        var button_id = $(this).attr('id');
        var item_id = button_id.split('-')[0];
        if(!confirm('id = ' + item_id + ' 完了してよろしいですか？')){
            return false;
        }else{
            turnItemCompleted(item_id);
        }
    })

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

});
