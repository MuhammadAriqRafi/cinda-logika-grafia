<?= $this->extend('layouts/backend/template'); ?>

<?= $this->section('modal'); ?>
<!-- Put this part before </body> tag -->
<input type="checkbox" id="postModal" class="modal-toggle" />
<div class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 id="postModalLabel" class="font-bold text-2xl mb-10"></h3>

        <form id="postForm"></form>

        <div class="modal-action">
            <label for="postModal" class="btn">Batal</label>
            <button id="postFormActionBtn" class="btn btn-primary" onclick="save()"></button>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('toolbar'); ?>
<!-- The button to open modal -->
<label for="postModal" class="btn modal-button" onclick="create()">Tambah Data</label>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<table class="table table-zebra w-full" id="postTable">
    <thead>
        <tr>
            <td class="w-5/12">Title</td>
            <td class="w-2/12">Category</td>
            <td class="w-5/12">Excerpt</td>
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

    // Helper
    const setModalLabelAndModalActionBtnText = (context) => {
        $(`#${modalLabel}`).text(`${context} Post`);
        $(`#${formActionBtn}`).text(context);
    }

    const setFormAction = (url) => {
        $(`#${form}`).attr('action', url);
    }

    const getFormAction = () => {
        return $(`#${form}`).attr('action');
    }

    const closeModal = () => {
        $(`#${modal}`).prop('checked', false);
    }

    const openModal = () => {
        $(`#${modal}`).prop('checked');
    }

    // CRUD
    const create = () => {
        const url = siteUrl + '<?= $storeUrl ?>';

        setModalLabelAndModalActionBtnText('Tambah');
        setFormAction(url);
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
                console.log(response);
            }
        });
    }

    const save = () => {
        const originalForm = $(`#${form}`)[0];
        const data = new FormData(originalForm);

        store(data);
    }

    $(document).ready(function() {
        const table = createDataTable(tableId, siteUrl + '<?= $indexUrl ?>', [{
                name: 'title',
                data: 'title'
            },
            {
                name: 'category',
                data: 'category'
            },
            {
                name: 'excerpt',
                data: 'excerpt',
                render: function(data) {
                    return `<textarea disable class="w-full text-base bg-transparent border-0 outline-0">${data}</textarea>`;
                }
            }
        ]);

        // Filling the form with inputs
        $(`#${form}`).append(textInputComponent('Title', 'title'));
        $(`#${form}`).append(textInputComponent('Category', 'category'));
    });
</script>
<?= $this->endSection(); ?>