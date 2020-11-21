/*eslint quotes: ["error", "single", { "avoidEscape": true }]*/

$(function () {
    //////////////////////////////////////////////////////////////////////////////////////////
    //                                  TOGGLE TASKS STATE 
    //////////////////////////////////////////////////////////////////////////////////////////

    const toggleLinks = $('.toggle-link');

    const deleteTask = function(data) {
        $('#tasks-list-' + data.taskId).remove();
    }

    toggleLinks.click(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $(this).attr('href'), 
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                '_token': $(this).data('token'),
                'taskId': $(this).data('taskid')
            })
        }).done(function(data) {
            deleteTask(data);
            alert(data.message);
        }).fail(function(data){
            alert(data.responseJSON.message);
        });
    });

});
