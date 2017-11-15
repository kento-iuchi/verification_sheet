$(function(){
    'use strict';

    var viewPartHeight = $('#view_part_header').outerHeight();
    console.log(viewPartHeight);
    var inputPartTop = viewPartHeight + 30;
    $('#input_part').css('top', inputPartTop + 'px');
});
