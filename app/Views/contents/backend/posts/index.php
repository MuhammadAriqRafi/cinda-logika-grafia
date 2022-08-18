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
        </div>

        <form id="postForm" enctype="multipart/form-data"></form>
        <div id="categoryContainer"></div>

        <div class="modal-action">
            <label class="btn" onclick="closeModal()">Batal</label>
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
    const categoryContainer = 'categoryContainer';

    // Helper
    const setModalLabelAndModalActionBtnText = (state, context = 'Post') => {
        $(`#${modalLabel}`).text(`${state} ${context}`);
        $(`#${formActionBtn}`).text(state);
    }

    const getFormCurrentActionState = () => {
        return $(`#${formActionBtn}`).text();
    }

    const setFormAction = (url) => {
        $(`#${form}`).attr('action', url);
    }

    const getFormAction = () => {
        return $(`#${form}`).attr('action');
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

    const renderForm = () => {
        $(`#${form}`).append(textInputComponent('Title', 'title', 'text', 'autofocus'));
        $(`#${form}`).append(fileInputComponent('Cover', 'cover'));
        $(`#${form}`).append(textInputComponent('Category', 'category'));
        $(`#${form}`).append(textareaComponent('Content', 'content', true));
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

    // CRUD Post
    const create = () => {
        const url = siteUrl + '<?= $storeUrl ?>';

        setModalLabelAndModalActionBtnText('Tambah');
        setFormAction(url);
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
                populateForm(response);
                setModalLabelAndModalActionBtnText('Ubah');
                setFormAction(siteUrl + '<?= $updateUrl ?>' + id);
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

    // Category Helper
    // TODO: Create Category management
    const getCategoryData = () => {
        const url = siteUrl + '<?= $indexCategory ?>';
        return fetch(url).then(response => response.json());
    }

    const renderAddCategoryBtn = () => {
        $('#modalHeader').append(`<button class="btn" onclick="createCategory()">Tambah</button>`);
    }

    const hideFormActionBtn = () => {
        $(`#${formActionBtn}`).hide();
    }

    async function renderCategory() {
        let posts = await getCategoryData();
        let categories = '';

        posts.forEach(post => {
            categories += `
                <div class="flex justify-between items-center w-full">
                    <p class="text-2xl">${post.name}</p>
                    <button class="btn btn-error">Delete</button>
                </div>
            `
        });

        $(`#${categoryContainer}`).append(categories);
    }

    // CRUD Category
    const indexCategory = () => {
        hideForm();
        hideFormActionBtn();
        setModalLabelAndModalActionBtnText('Tambah', 'Category');
        renderCategory();
        renderAddCategoryBtn();
        openModal();
    }

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
        renderSummernote();
    });
</script>
<?= $this->endSection(); ?>