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
            complete: function() {
                stylingDatatable(id);
            }
        },
        serverSide: true,
        deferRender: true,
        dom: '<"overflow-x-hidden"<"flex flex-wrap gap-4 justify-center sm:justify-between items-center mb-6"lf><t><"flex justify-between items-center mt-10"ip>>',
        columns: options
    });
}

const editDeleteBtn = (id = null) => {
    return `
        <button class="btn btn-sm btn-secondary" onclick="edit('${id}')">Edit</button>
        <button class="btn btn-sm btn-error" onclick="destroy('${id}')">Delete</button>
    `;
}

const stylingDatatable = (tableId) => {
    $(`#${tableId}_paginate ul`).addClass('btn-group');
    $(`#${tableId}_paginate ul li`).addClass('btn');

    $(`#${tableId}_length label select[name="${tableId}_length"]`).removeClass('form-select form-select-sm');
    $(`#${tableId}_length label select[name="${tableId}_length"]`).addClass('select select-bordered');

    $(`#${tableId}_filter label input[type="search"]`).removeClass('form-control form-control-sm');
    $(`#${tableId}_filter label input[type="search"]`).addClass('input input-bordered p-2');
    $(`#${tableId}_filter label input[type="search"]`).addClass('input input-bordered p-2');
    $(`#${tableId}_filter`).addClass('p-2');

    let textareas = $('td textarea');

    for (let index = 0; index < textareas.length; index++) {
        textareas[index].style.height = `${textareas[index].scrollHeight}px`;
    }
}