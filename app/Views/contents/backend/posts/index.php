<?= $this->extend('layouts/backend/template'); ?>

<?= $this->section('css'); ?>
<!-- Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<?= $this->endSection(); ?>

<?= $this->section('modal'); ?>
<input type="checkbox" class="modal-toggle" />
<div id="postModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <div id="modalHeader" class="flex justify-between items-center mb-10">
            <h3 id="postModalLabel" class="font-bold text-2xl"></h3>
            <button id="categoryFormCreateBtn" class="btn btn-primary" onclick="createCategory()">Tambah</button>
            <button id="categoryFormBackBtn" class="btn btn-error" onclick="indexCategory()">Kembali</button>
        </div>

        <form id="postForm" enctype="multipart/form-data"></form>
        <table class="table table-zebra w-full" id="categoryTable"></table>

        <div class="modal-action">
            <label id="postFormCancelBtn" class="btn" onclick="closeModal()">Batal</label>
            <button id="postFormActionBtn" class="btn btn-primary" onclick="save()"></button>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('toolbar'); ?>
<div class="btn-group">
    <label for="postModal" class="btn btn-active modal-button rounded-lg" onclick="create()">Tambah Data</label>
    <label for="postModal" class="btn modal-button rounded-lg" onclick="indexCategory()">Categories</label>
</div>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<table class="table table-zebra w-full" id="postTable">
    <thead>
        <tr>
            <td>Cover</td>
            <td>Title</td>
            <td>Category</td>
            <td>Excerpt</td>
            <td>Action</td>
        </tr>
    </thead>
</table>
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script>
    const tableId = 'postTable';
    const form = 'postForm';
    const modal = 'postModal';
    const modalLabel = 'postModalLabel';
    const formActionBtn = 'postFormActionBtn';
    const formCancelBtn = 'postFormCancelBtn';
    const categoryTable = 'categoryTable';
    const categoryFormCreateBtn = 'categoryFormCreateBtn';
    const categoryFormBackBtn = 'categoryFormBackBtn';

    // Post Helper
    const setModalLabelAndModalActionBtnText = (state, context = 'Post') => {
        $(`#${modalLabel}`).text(`${state} ${context}`);
        $(`#${formActionBtn}`).text(state);
    }

    const setFormAction = (url) => {
        $(`#${form}`).attr('action', url);
    }

    const setSelectedCategoryDropdown = (categoryId) => {
        $(`option[value="${categoryId}"]`).prop('selected', true);
    }

    const getFormCurrentActionState = () => {
        return $(`#${formActionBtn}`).text();
    }

    const getFormAction = () => {
        return $(`#${form}`).attr('action');
    }

    const getCategoryData = () => {
        const url = siteUrl + '<?= $ajaxIndexCategoryUrl ?>';
        return fetch(url).then(response => response.json());
    }

    const closeModal = () => {
        $(`#${modal}`).removeClass('modal-open');
    }

    const openModal = () => {
        $(`#${modal}`).addClass('modal-open');
    }

    const previewImage = (fileInput) => {
        const image = $(`input[name="${fileInput.name}"]`)[0];
        const imagePreviewContainer = $('.img-preview')[0];
        const fileReader = new FileReader();

        if (image.files[0]) {
            fileReader.readAsDataURL(image.files[0]);
            fileReader.onload = function(e) {
                imagePreviewContainer.src = e.target.result;
            }
        } else {
            imagePreviewContainer.src = '#';
        }
    }

    const displayError = (errorInput) => {
        errorInput.forEach(error => {
            $(`#error-${error.input_name}`).text(error.error_message);
            $(`#error-${error.input_name}`).removeClass('hidden');

            switch (error.input_name) {
                case 'cover':
                    $(`[name="${error.input_name}"]`).prev().attr('src', '');
                    $(`[name="${error.input_name}"]`).addClass('input-error');
                    break;
                case 'content':
                    $(`[name="${error.input_name}"]`).addClass('textarea-error');
                    break;
                default:
                    $(`[name="${error.input_name}"]`).addClass('input-error');
                    break;
            }
        });
    }

    async function renderForm() {
        const categories = await getCategoryData();

        $(`#${form}`).append(textInputComponent('Title', 'title', 'text', 'autofocus'));
        $(`#${form}`).append(fileInputComponent('Cover', 'cover'));
        $(`#${form}`).append(dropdownComponent('Category', 'category', categories));
        $(`#${form}`).append(textareaComponent('Content', 'content', true));

        // FIXME: If user wants to add image in summernote, the default modal covers the entire screen, need fixing
        await renderSummernote();
    }

    const renderSummernote = () => {
        $('#summernote').summernote({
            tabsize: 2,
            height: 240,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('.note-editable').addClass('text-neutral-content');
    }

    const populateForm = (data) => {
        for (const inputName in data) {
            switch (inputName) {
                case 'excerpt':
                    $('#summernote').summernote('code', data[inputName]);
                    break;
                case 'cover':
                    $('.img-preview').attr('src', baseUrl + `/media/article/${data[inputName]}`);
                    break;
                default:
                    $(`input[name="${inputName}"]`).val(data[inputName])
                    break;
            }
        }
    }

    const hideForm = () => {
        $(`#${form}`).hide();
    }

    const showForm = () => {
        $(`#${form}`).show();
    }
    // End of Post Helper

    // CRUD Post
    const create = () => {
        const url = siteUrl + '<?= $storeUrl ?>';

        setModalLabelAndModalActionBtnText('Tambah');
        setFormCancelBtnAction('closeModal()');
        hideCategoryFormCreateBtn();
        hideCategoryFormBackBtn();
        setFormAction(url);
        resetForm(form);
        showForm();
        openModal();
    }

    const store = (data) => {
        const url = getFormAction();

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    reloadDatatable(tableId);
                    resetForm(form);
                    alert(response.message);
                } else if (response.error_input) displayError(response.error_input);
            }
        });
    }

    const save = () => {
        const originalForm = $(`#${form}`)[0];
        const data = new FormData(originalForm);

        switch (getFormCurrentActionState()) {
            case 'Tambah':
                store(data);
                break;
            case 'Ubah':
                update(data);
                break;
        }
    }

    const edit = (id) => {
        const url = siteUrl + '<?= $editUrl ?>' + id;

        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            success: function(response) {
                setModalLabelAndModalActionBtnText('Ubah');
                setFormCancelBtnAction('closeModal()');
                hideCategoryFormCreateBtn();
                hideCategoryFormBackBtn();
                setFormAction(siteUrl + '<?= $updateUrl ?>' + id);
                populateForm(response);
                setSelectedCategoryDropdown(response.category);
                showForm();
                openModal();
            }
        });
    }

    const update = (data) => {
        const url = getFormAction();
        data.append('_method', 'PATCH');

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    reloadDatatable(tableId);
                    alert(response.message);
                } else if (response.error_input) displayError(response.error_input);
            }
        });
    }

    const destroy = (id) => {
        if (confirm('Apakah anda yakin?')) {
            const url = siteUrl + '<?= $destroyUrl ?>' + id;

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    _method: 'DELETE'
                },
                dataType: "json",
                success: function(response) {
                    alert(response.message);
                    if (response.status) reloadDatatable(tableId);
                }
            });
        }
    }
    // End of CRUD Post

    // Category Helper
    const setFormActionBtnAction = (action) => {
        $(`#${formActionBtn}`).attr('onclick', action);
    }

    const setFormCancelBtnAction = (action) => {
        $(`#${formCancelBtn}`).attr('onclick', action);
    }

    const hideCategoryTable = () => {
        $(`#${categoryTable}_wrapper`).hide();
    }

    const hideCategoryFormCreateBtn = () => {
        $(`#${categoryFormCreateBtn}`).hide();
    }

    const hideFormActionBtn = () => {
        $(`#${formActionBtn}`).hide();
    }

    const hideCategoryFormBackBtn = () => {
        $(`#${categoryFormBackBtn}`).hide();
    }

    const showCategoryTable = () => {
        $(`#${categoryTable}_wrapper`).show();
    }

    const showCategoryFormCreateBtn = () => {
        $(`#${categoryFormCreateBtn}`).show();
    }

    const showFormActionBtn = () => {
        $(`#${formActionBtn}`).show();
    }

    const showCategoryFormBackBtn = () => {
        $(`#${categoryFormBackBtn}`).show();
    }

    const isCategoryDatatableRendered = () => {
        if ($(`#${categoryTable}_wrapper`).length > 0) return true;
        else false;
    }

    async function renderCategoryDatatable() {
        if (!isCategoryDatatableRendered()) {
            createDataTable(categoryTable, siteUrl + '<?= $indexCategoryUrl ?>', [{
                    title: 'name',
                    name: 'name',
                    data: 'name'
                },
                {
                    title: 'action',
                    data: 'id',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        return `
                            <button class="btn btn-sm btn-secondary" onclick="editCategory(${data})">Edit</button>
                            <button class="btn btn-sm btn-error" onclick="destroyCategory(${data})">Delete</button>
                        `;
                    }
                }
            ]);
        }
    }

    const renderCategoryForm = () => {
        $(`#${form}`).append(textInputComponent('Name', 'name'));
    }

    const closeCategory = () => {
        hideCategoryTable();
        showFormActionBtn();
        hideCategoryFormCreateBtn();
        switchFormTo('post');
        renderSummernote();
        setFormActionBtnAction('save()');
        closeModal();
    }

    const switchFormTo = (type) => {
        $(`#${form}`).html('');

        switch (type.toLowerCase()) {
            case 'post':
                renderForm();
                break
            case 'category':
                renderCategoryForm();
                break
            default:
                console.log('Form type is not exist');
                break;
        }
    }

    const populateCategoryForm = (data) => {
        const inputName = Object.keys(data)[0];
        $(`input[name="${inputName}"]`).val(data.name);
    }
    // End of Category Helper

    // CRUD Category
    const indexCategory = () => {
        setModalLabelAndModalActionBtnText('Index', 'Category');
        setFormCancelBtnAction('closeCategory()');
        hideFormActionBtn();
        hideForm();
        hideCategoryFormBackBtn();
        renderCategoryDatatable();
        showCategoryTable();
        showCategoryFormCreateBtn();
        openModal();
    }

    const destroyCategory = (id) => {
        if (confirm('Apakah anda yakin?')) {
            const url = siteUrl + '<?= $destroyCategoryUrl ?>' + id;

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    _method: "DELETE"
                },
                dataType: "json",
                success: function(response) {
                    alert(response.message);
                    if (response.status) reloadDatatable(categoryTable);
                }
            });
        }
    }

    const createCategory = () => {
        hideCategoryTable();
        hideCategoryFormCreateBtn();
        showForm();
        showFormActionBtn();
        showCategoryFormBackBtn();
        setFormAction(siteUrl + '<?= $storeCategoryUrl ?>');
        setFormActionBtnAction('saveCategory()');
        setModalLabelAndModalActionBtnText('Tambah', 'Category');
        switchFormTo('category');
        // TODO: Create back button to indexCategory
    }

    const storeCategory = (data) => {
        const url = siteUrl + '<?= $storeCategoryUrl ?>';

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    alert(response.message);
                    reloadDatatable(categoryTable);
                    showCategoryTable();
                    showCategoryFormCreateBtn();
                    hideForm();
                }
            }
        });
    }

    const editCategory = (id) => {
        const url = siteUrl + '<?= $editCategoryUrl ?>' + id;

        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            success: function(response) {
                hideCategoryTable();
                hideCategoryFormCreateBtn();
                showForm();
                showFormActionBtn();
                showCategoryFormBackBtn();
                setModalLabelAndModalActionBtnText('Ubah', 'Category');
                setFormAction(siteUrl + '<?= $updateCategoryUrl ?>' + id);
                setFormActionBtnAction('saveCategory()');
                switchFormTo('category');
                populateCategoryForm(response);
            }
        });
    }

    const updateCategory = (data) => {
        const url = getFormAction();
        data.append('_method', 'PATCH');

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    alert(response.message);
                    reloadDatatable(categoryTable);
                    showCategoryTable();
                    showCategoryFormCreateBtn();
                    hideForm();
                }
            }
        });
    }

    const saveCategory = () => {
        const categoryForm = $(`#${form}`)[0];
        const data = new FormData(categoryForm);

        switch (getFormCurrentActionState()) {
            case 'Tambah':
                storeCategory(data);
                break;
            case 'Ubah':
                updateCategory(data);
                break;
        }
    }
    // End of CRUD Category

    $(document).ready(function() {
        const table = createDataTable(tableId, siteUrl + '<?= $indexUrl ?>', [{
                data: 'cover',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<img src="${data}" class="rounded-lg" width="150" alt="${row.title}">`;
                }
            },
            {
                name: 'title',
                data: 'title',

                render: function(data) {
                    return `<textarea disabled class="w-full text-base bg-transparent border-0 outline-0 resize-none">${data}</textarea>`;
                }
            },
            {
                name: 'category',
                data: 'category'
            },
            {
                name: 'excerpt',
                data: 'excerpt',

                render: function(data) {
                    return `<textarea disabled class="w-full text-base bg-transparent border-0 outline-0 resize-none">${data}</textarea>`;
                }
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return editDeleteBtn(data);
                }
            }
        ]);

        renderForm();
    });
</script>
<?= $this->endSection(); ?>