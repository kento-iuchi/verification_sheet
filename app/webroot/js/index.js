$(function(){
    'use strict';

    var viewPartHeight = $('#view_part_header').outerHeight();
    $('#page_selecter').css('top', viewPartHeight + 'px');
    $('#page_selecter').css('display', 'block');
    synchronizeTwoTablesHeight();
    changePriorityHighlight();

    // ダブルクリックでその場変種
    var uneditableColumnNames =  ['id', 'elapsed', 'grace_days_of_verification_complete', 'created', 'modified', 'verification_history'];
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
        if(!$(editCellSelector).hasClass('editable-cell')){
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
                       "<option value='コードレビュー中'>コードレビュー中</option>" +
                       "<option value='改修中'>改修中</option>" +
                       "<option value='技術二重チェック中'>技術二重チェック中</option>" +
                       "<option value='サポート・営業確認中'>サポート・営業確認中</option>" +
                       "</select>";
            $(editCellSelector).html(form);
        } else if (columnName == 'confirm_priority') {
            var form = "<select id = '" + formId + "'>" +
                       "<option value='0'>不要</option>" +
                       "<option value='1'>低</option>" +
                       "<option value='2'>中</option>" +
                       "<option value='3'>高</option>" +
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

                $('#' + id + '-elapsed').text(Math.round((todayDate - pullrequestDate)/86400000));
                $('#' + id + '-grace_days_of_verification_complete').text(Math.round((scheduledReleaseDate - todayDate)/86400000));
            }
            if (columnName == 'confirm_priority') {
                var priority = ['不要', '低', '中', '高'];
                $(selectedTd).html(priority[textEdited]);
                if (textEdited == '3'){
                    $(selectedTd).addClass('high_priority');
                } else {
                    $(selectedTd).removeClass('high_priority');
                }
            }
            synchronizeTwoTablesHeight();
            changePriorityHighlight();
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
        var header_tr = $("#view_part_header tr.view_part_item");
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

        var tableHeight = $("#view_part_header").height();
        $("#page_selecter").offset({top : tableHeight + 60});
    };

    function changePriorityHighlight()
    {
        $('td.priority-row').each(function(){
            if($(this).hasClass('high_priority')){
                $(this).css({'color': 'red', 'font-weight': 'bold', 'font-size': '16px'});
            } else {
                $(this).css({'color': 'black', 'font-weight': 'normal', 'font-size': '12px'});
            }
        })
    }

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

    // 検証履歴新規作成
    $('.add-verification-history').click(function()
    {
        var button_id = $(this).attr('id');
        var item_id = button_id.split('-')[0];
        createAddVerifivationHistoryForm($(this).parent().attr('id'));
        $('#' + button_id).hide();
        synchronizeTwoTablesHeight();
    })

    // 検証履歴新規作成フォーム生成
    var verifier_list = ['A', 'B', 'C'];
    function createAddVerifivationHistoryForm(cell_id)
    {
        var options = $('th.verification-history-row').attr('data-options');
        options = JSON.parse(options);
        var item_id = cell_id.split('-')[0];
        var name_selector =  '<br><select id = "' + item_id + '-name-selector">';
        $.each(options, function(index, name){
            index += 1;
            name_selector += '<option value="' + index + '">' + name + '</option>';
        });
        name_selector += '</select>';
        $('#' + item_id + '-verification-history-input-area').append(name_selector);

        var comment_form = '<textarea rows = 5 id = "' + item_id + '-comment_form"></textarea>';
        $('#' + item_id + '-verification-history-input-area').append(comment_form);

        var submit_button = '<button type = "button" class = "add-history-button">保存</button>';
        $('#' + item_id + '-verification-history-input-area').append($(submit_button).click(function(){saveHistory(item_id);}));

        $('#' + name_selector).focus();
    }

    // 保存ボタンが押されたときの処理
    function saveHistory(itemId)
    {
        var verifierId = $('#' + itemId + '-name-selector').val();
        var comment = $('#' + itemId + '-comment_form').val();
        var editActionUrl = WEBROOT + 'items/save_verification_history/' + itemId + '/' + verifierId + '/' + comment;

        $.ajax({
        url: editActionUrl,
        type: "POST",
        data: { item_id : itemId, verifier_id: verifierId, comment: comment },
        dataType: "text",
        success : function(response)
        {
            if($('#' + itemId + '-verification_history table').length == 0){
                $('#' + itemId + '-verification_history').append('<table></table>');
            }
            var verifierName = $('#' + itemId + '-name-selector option:selected').text();
            var today = new Date();
            console.log(todayDate);
            var todayMonth = ('0' + (today.getMonth() + 1)).slice(-2);
            var todayDate = ('0' + today.getDate()).slice(-2);
            var newHistory =   '<tr>'
                             + '<td>' + verifierName + '</td>'
                             + '<td>' + today.getFullYear() + '-' + todayMonth + '-' + todayDate + '</td>'
                             + '<td>' + '<span class="verification-history-detail-link" data-comment = "' + comment + '" data-id = "' + response + '">詳細</span>' + '</td>'
                             + '</tr>';
            $('#' + itemId + '-verification_history table').append(newHistory);
            $('#' + itemId + '-add-verification-history').show();
            $('#' + itemId + '-verification-history-input-area').empty();
            synchronizeTwoTablesHeight();
        },
        error: function()
        {
            alert('通信失敗');
        }
        });
    }

    // 検証履歴詳細ボタンの処理
    var appearingHistoryIds = []
    $(document).on('click', '.verification-history-detail-link', function()
    {
        var verificationHistoryComment = $(this).attr('data-comment')
        var verificationHistoryId = $(this).attr('data-id');

        if ($.inArray(verificationHistoryId, appearingHistoryIds) == -1) {
            appearingHistoryIds.push(verificationHistoryId);
            var commentTd = '<tr><td id = "verification-history-comment_' + verificationHistoryId + '" colspan = "3">' +  verificationHistoryComment +'</td></tr>';
            $(this).parent().parent().after(commentTd);
        } else {
            appearingHistoryIds.some(function(v, i){
                if (v == verificationHistoryId) appearingHistoryIds.splice(i,1);
            });
            $('#verification-history-comment_' + verificationHistoryId).parent().remove();
            $('#verification-history-comment_' + verificationHistoryId).remove();

            // 非表示
        }
        synchronizeTwoTablesHeight();
        console.log(appearingHistoryIds);

    });

});
