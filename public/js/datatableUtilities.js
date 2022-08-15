const reload = (formId) => {
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
        dom: '<"overflow-x-hidden"<"flex flex-wrap gap-4 justify-between items-center mb-5"lf><t><"flex justify-between items-center mt-5"ip>>',
        columns: options
    });
}