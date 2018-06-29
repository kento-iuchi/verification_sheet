$(function(){
    'use strict';

    updateStyles();
    synchronizeTwoTablesHeight();

    // ダブルクリックでその場変種
    var uneditableColumnNames =  ['id', 'elapsed', 'grace_days_of_verification_complete', 'created', 'modified', 'verification_history', 'author_id'];
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

    var editStartingTime;
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

        var today = new Date();
        editStartingTime = Math.floor(today.getTime() / 1000);

        initialText = initialText.replace(/<br>|<\/br>/g, '&&NEWLINE&&');
        initialText = initialText.replace(/<.+?>/g, '');
        initialText = initialText.replace(/&&NEWLINE&&/g, '\n');
        initialText = initialText.trim();

        formId = editCellId + '_form';
        if (columnName == 'needs_supp_confirm') {
            var form = "<select id = '" + formId + "'>" +
                       "<option value='1'>いいえ</option>" +
                       "<option value='0'>はい</option>" +
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
        synchronizeTwoTablesHeight();
        $('#'+formId).focus();
        // セレクトボックスの初期値設定
        if ($.inArray(initialText, ['改善',　'機能追加', 'バグ',　'コードレビュー中',
                                    '改修中', '技術二重チェック中', 'サポート・営業確認中',]) != -1){
            $('#' + formId).val(initialText);
        }

        return formId;
    }

    function fetchLastUpdatedTime(recordId)
    {
        return $.ajax({
            url: WEBROOT + 'items/fetch_last_updated_time',
            type: "POST",
            data: {id : recordId},
            dataType: "text",
        })
    }

    function postToEdit(selectedTd, id, columnName, currentText)
    {
        var lastUpdatedTime
        fetchLastUpdatedTime(id).done(function(response){
            console.log(response);
            lastUpdatedTime = response;
            /*
            最終更新時間　> 編集開始時間の場合、
            ユーザが編集している最中にレコードが更新されたとみなす。
            最終更新時間 - 編集開始時間が1000以上という条件にしているのは、１ユーザーが高速で編集セルを切り替えた際に
            誤反応させないため
            */
            if ((lastUpdatedTime - editStartingTime) > 1){
                if(confirm('他のユーザーによってレコードが更新されたため、リロードします。入力中の内容をクリップボードにコピーしますか？')){

                    $('body').append('<textarea id="temp-clipboard-field"></textarea>');
                    $('#temp-clipboard-field').val(currentText);
                    $('#temp-clipboard-field').select();

                    document.execCommand('copy');
                }
                location.reload();
                finishEdit();
                return;
            }

            var editActionUrl = WEBROOT + 'items/edit/';
            currentText = replaceSlashAndColon(currentText);
            currentText = currentText.replace(/\r\n/g, '&&NEWLINE&&');
            currentText = currentText.replace(/\r/g, '&&NEWLINE&&');
            currentText = currentText.replace(/\n/g, '&&NEWLINE&&');
            if(currentText.length == 0){
                currentText = '*EMPTY*';
            }

            var today = new Date();
            lastUpdatedTime = Math.floor(today.getTime() / 1000);
            console.log(currentText);
            console.log(columnName);
            $.ajax({
            url: editActionUrl,
            type: "POST",
            data: { id : id, column_name: columnName, content: currentText, last_updated_time : lastUpdatedTime },
            dataType: "text",
            success : function(response){
                console.log(response);
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

                $(selectedTd).html(recordtext(textEdited));
                if (columnName == 'pullrequest_update' || columnName == 'due_date_for_release'){
                    var pullrequestDate = new Date($('#' + id + '-pullrequest_update').text());
                    var dueDateForRelease = new Date($('#' + id + '-due_date_for_release').text());
                    var todayDate = new Date();

                    $('#' + id + '-elapsed').text(recordtext(Math.round((todayDate - pullrequestDate)/86400000)));

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
                    $(selectedTd).html(recordtext(authorNames[currentText]));
                }
                if (columnName == "verifier_id") {
                    var verifierNames = $('th.verifier-column').attr('data-verifier-options');
                    verifierNames = JSON.parse(verifierNames);
                    $(selectedTd).html(recordtext(verifierNames[currentText]));
                }
                if (columnName == "manual_exists") {
                    var manual_exists_char = currentText == 1 ? '◯' : '✕';
                    $(selectedTd).html(recordtext(manual_exists_char));
                }
                if (columnName == "needs_supp_confirm") {
                    if (currentText == 1) {
                        var needs_supp_confirm_char = 'いいえ';
                        $('#item_' + id + '-head').removeClass('needs-no-confirm');
                        $('#item_' + id + '-data').removeClass('needs-no-confirm');
                    } else {
                        var needs_supp_confirm_char = 'はい';
                        $('#item_' + id + '-head').addClass('needs-no-confirm');
                        $('#item_' + id + '-data').addClass('needs-no-confirm');
                    }
                    $(selectedTd).html(recordtext(needs_supp_confirm_char));
                }
                synchronizeTwoTablesHeight();
                updateStyles(id);
            },
            error: function(){
                //通信失敗時の処理
                alert('通信失敗');
            }
            });
        }).fail(function(response){
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
        var tdColor = $('#item_' + itemId + '-head').css('background');
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

        $('#item_' + itemId + '-head td').each(function(){
            $(this).css('background', tdColor);
            $(this).children('span').css('color', fontColor);
        });
        $('#item_' + itemId + '-data td').each(function(){
            $(this).css('background', tdColor);
            $(this).children('span').css('color', fontColor);
        });


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
        var options = $('th.verifier-column').attr('data-verifier-options');
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

    function recordtext(text){
        return '<span class="record_text">' + text + '</span>'
    }

});
