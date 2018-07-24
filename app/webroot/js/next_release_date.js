$(function(){
    'use strict';

    var isEditingNextReleaseDate = false;
    $('#next-release-date').dblclick(function(){
        var currentReleaseDate = $(this).text();
        $(this).html(generateForm());
        $('#next-relase-date-form').datepicker({ dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, defaultDate: 0, minDate: 0});
        $('#next-relase-date-form').datepicker('setDate', currentReleaseDate);
        $('#next-relase-date-form').datepicker('show');
        isEditingNextReleaseDate = true;
    })

    function generateForm()
    {
        return '<input type="text" id="next-relase-date-form" size="10"' +
               'style="height:20px; width:160px; font-size:28px;' +
               'display: inline; position:relative;"/>';
    }

    $(window).keydown(function(e)
    {
        if (e.keyCode == 13) {
            if (isEditingNextReleaseDate) {
                $.ajax({
                    url: WEBROOT + '/system_variables/save_next_release_date',
                    type: "POST",
                    data: {next_release_date : $('#next-relase-date-form').val()},
                    dataType: "text",
                }).done(()=>{
                    alert('次回リリース日を変更しました。');
                    $('#next-release-date').html($('#next-relase-date-form').val());
                }).fail(()=>{
                    alert('リリース日の変更に失敗しました。');
                })
                isEditingNextReleaseDate = false;
            }
        }
    })
})
