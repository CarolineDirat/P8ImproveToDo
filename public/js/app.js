/*eslint quotes: ["error", "single", { "avoidEscape": true }]*/

$(function () {
    //////////////////////////////////////////////////////////////////////////////////////////
    //                                  TOGGLE TASKS STATE 
    //////////////////////////////////////////////////////////////////////////////////////////

    const toggleLinks = $('.toggle-link');

    const deleteTask = function(data) {
        $('#tasks-list-' + data.taskId).hide('slow');
    }

    toggleLinks.click(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $(this).attr('href'), 
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                '_token': $(this).data('token')
            })
        }).done(function(data) {
            deleteTask(data);
            console.log(data.message);
        }).fail(function(data){
            alert(data.responseJSON.message);
        });
    });

});
