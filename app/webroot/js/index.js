$(function(){
    'use strict';

    var selectedTd;
    $('#view_part td').dblclick(function(){
        $(selectedTd).css('color', '#000000');
        selectedTd = '#' + $(this).attr('id')
        var initialText = $(selectedTd).text();
        console.log(selectedTd, initialText);
        $(selectedTd).css('color', '#013ADF');
    });
});
