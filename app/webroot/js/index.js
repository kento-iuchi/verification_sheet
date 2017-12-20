$(function(){
    'use strict';

    var viewPartHeight = $('#view_part_header').outerHeight();
    console.log(viewPartHeight);
    $('#page_selecter').css('top', viewPartHeight + 'px');
    $('#page_selecter').css('display', 'block');

    var selectedTd;
    var initialText;
    var isFirstClick = true;
    $('#view_part td').dblclick(function(){
        console.log('1', selectedTd, initialText, isFirstClick);
        // もう一度おした時の処理
        $(selectedTd).html(initialText);

        //
        selectedTd = '#' + $(this).attr('id')
        initialText = $(selectedTd).text();
        console.log(selectedTd, initialText);
        var formId = $(this).attr('id') + '_form'
        var form = "<form action='edit2'" + " id ='" + formId + "'><textarea rows= '3'>" + initialText + "</textarea><input type='submit' value='決定'></form>";
        $(selectedTd).html(form);
        isFirstClick = false;
    });
});
