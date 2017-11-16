$(function(){
    'use strict';

    var viewPartHeight = $('#view_part_header').outerHeight();
    console.log(viewPartHeight);
    var inputPartTop = viewPartHeight + 30;
    $('#input_part').css('top', inputPartTop + 'px');


    var selectedTd;
    $('#view_part td').dblclick(function(){
        $(selectedTd).css('color', '#000000');
        selectedTd = '#' + $(this).attr('id')
        console.log(selectedTd);
        $(selectedTd).css('color', '#013ADF');
    });
});
