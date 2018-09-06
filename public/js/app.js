$(document).ready(function () {
    $('.add-project').click(function () {
        $.ajax({
            url: "create-project",
            type: "POST",
            data: new FormData($("#projectForm")[0]),
            contentType: false,
            cache: false,
            processData: false,
            dataType : 'json'
        }).done(function(data) {
            window.location.href = '/tasks?project_id=' + data.id
        });
    });

    $('.add-task').click(function () {
        $.ajax({
            url: "create-task",
            type: "POST",
            data: new FormData($("#taskForm")[0]),
            contentType: false,
            cache: false,
            processData: false,
            dataType : 'json'
        }).done(function(data) {
            window.location.href = '/tasks?project_id=' + data.project_id
        });
    });

    $('.task-completed').click(function () {
        if($(this).prop('checked')) {
            var data = new FormData();
            data.append('completed', '1');
            data.append('id', $(this).attr('data-id'));
            $.ajax({
                url: "update-task",
                type: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                dataType : 'json'
            });
            location.reload();
        }
    });

    $('.delete').click(function () {
        location.reload()
    });
});
