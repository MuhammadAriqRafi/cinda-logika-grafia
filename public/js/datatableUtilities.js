const reloadDatatable = (formId) => {
    $(`#${formId}`).DataTable().ajax.reload(null, false);
}

const createDataTable = (id, url, options) => {
    $(`#${id}`).DataTable({
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 99999],
            [10, 25, 50, 'All'],
        ],
        ajax: {
            url: url,
            beforeSend: function() {
                // ? Reset select option for Popup Active in popup management
                if ($('select[name="id"]').length > 0) $('select[name="id"]').html('');
            }
        },
        serverSide: true,
        deferRender: true,
        dom: '<"overflow-x-hidden"<"flex flex-wrap gap-4 justify-between items-center mb-6"lf><t><"flex justify-between items-center mt-10"ip>>',
        columns: options,
        initComplete: function() {
            $('#postTable_paginate ul').addClass('btn-group');
            $('#postTable_paginate ul li').addClass('btn');

            $('#postTable_length label select[name="postTable_length"]').removeClass('form-select form-select-sm');
            $('#postTable_length label select[name="postTable_length"]').addClass('select select-bordered');

            $('#postTable_filter label input[type="search"]').removeClass('form-control form-control-sm');
            $('#postTable_filter label input[type="search"]').addClass('input input-bordered p-2');
            $('#postTable_filter label input[type="search"]').addClass('input input-bordered p-2');
            $('#postTable_filter').addClass('p-2');

            let textareas = $('td textarea');

            for (let index = 0; index < textareas.length; index++) {
                textareas[index].style.height = `${textareas[index].scrollHeight}px`;
            }
        },
    });
}

const editDeleteBtn = (id = null) => {
    return `
        <button class="btn btn-sm btn-secondary" onclick="edit('${id}')">Edit</button>
        <button class="btn btn-sm btn-error" onclick="destroy('${id}')">Delete</button>
    `;
}