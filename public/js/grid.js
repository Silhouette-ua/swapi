function showPeopleInfo(url) {
    var $modal = $('#people-info-modal');

    loader.show();

    $.ajax({
        url: url,
        dataType: 'html',
        success: function (data) {
            loader.hide();
            $modal.find('.modal-body').html(data);
            $modal.modal('show');
        }
    });
}

function editPeople(url) {
    if (typeof url === 'undefined') {
        return false;
    }

    window.location.href = url;
}

function deletePeople(url) {
    if (typeof url === 'undefined') {
        return false;
    }

    console.log(url);
    $('#people-delete-form').attr('action', url)
    $('#people-delete-form').submit();
}

$(document).ready(function () {
    var $modal = $('#people-info-modal');

    $('button.people-info').on('click', function () {
        showPeopleInfo($(this).attr('data-url'));
    });

    $('button.people-edit').on('click', function () {
        editPeople($(this).attr('data-url'));
    });

    $('button.people-delete').on('click', function () {
        deletePeople($(this).attr('data-url'));
    });

    $(document).keydown(function (event) {
        if (event.keyCode === 27) {
            $modal.modal('hide');
        }
    })
});
