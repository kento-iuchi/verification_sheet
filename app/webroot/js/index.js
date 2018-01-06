$(function(){
    'use strict';

    var viewPartHeight = $('#view_part_header').outerHeight();
    console.log(viewPartHeight);
    $('#page_selecter').css('top', viewPartHeight + 'px');
    $('#page_selecter').css('display', 'block');

    var selectedTd;
    var initialText;
    var recordId;
    var formId
    var content;
    var isFirstClick = true;
    $('#view_part td').dblclick(function(){
        var edit_url = "<?=$this->Html->url(array('controller' => 'items', 'action' => 'edit2'))?>"  + '/' + recordId
        if (!isFirstClick){
            var edit_url = '/verification_sheet/items/edit2/'// + recordI
            console.log(formId);
            content = $('#' + formId).val()
            postToEdit(edit_url, recordId, content);
        }


        $(selectedTd).html(initialText);
        selectedTd = '#' + $(this).attr('id')
        recordId = $(this).attr('id').split('_')[0]
        initialText = $(selectedTd).text();
        console.log('編集中:', selectedTd, initialText, recordId);

        formId = $(this).attr('id') + '_form'
        var form = "<textarea rows= '3' " + "id ='" + formId + "'>" + initialText + "</textarea>"
        $(selectedTd).html(form);
        isFirstClick = false;
    });


    function postToEdit(edit_url, id, content){
        console.log('postToEdit: ', edit_url, id, content)
        $.ajax({
        url: edit_url,
        type: "POST",
        data: { id : id, content: content },
        dataType: "text",
        success : function(response){
            //通信成功時の処理
            alert(response);
        },
        error: function(){
            //通信失敗時の処理
            alert('通信失敗');
        }
    });
    };
});
