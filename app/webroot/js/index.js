$(function(){
    'use strict';

    var viewPartHeight = $('#view_part_header').height()
    console.log(viewPartHeight);
    $('#input_part').css('top', viewPartHeight + 'px');
});
