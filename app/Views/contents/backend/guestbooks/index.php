<?= $this->extend('layouts/backend/template'); ?>

<?= $this->section('modal'); ?>
<input type="checkbox" class="modal-toggle" />
<div id="guestbookModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 id="guestbookDetailSubject" class="font-bold text-xl mb-4"></h3>
        <div class="flex justify-between mb-8">
            <div class="flex justify-between gap-x-4">
                From :
                <div class="flex flex-col">
                    <p id="guestbookDetailName" class="font-semibold"></p>
                    <p id="guestbookDetailEmail" class="text-sm"></p>
                </div>
            </div>
            <p id="guestbookDetailDate"></p>
        </div>
        <hr>
        <p id="guestbookDetailMessage" class="mt-6"></p>
        <label id="guestbookDetailCancelBtn" class="btn btn-error mt-8" onclick="closeModal()">Batal</label>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<table class="table table-zebra w-full" id="guestbookTable"></table>
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script>
    const tableId = 'guestbookTable';
    const modal = 'guestbookModal';
    const modalLabel = 'guestbookModalLabel';
    const guestbookDetailSubject = 'guestbookDetailSubject';
    const guestbookDetailMessage = 'guestbookDetailMessage';
    const guestbookDetailName = 'guestbookDetailName';
    const guestbookDetailEmail = 'guestbookDetailEmail';
    const guestbookDetailDate = 'guestbookDetailDate';

    const openModal = () => {
        $(`#${modal}`).addClass('modal-open');
    }

    const closeModal = () => {
        $(`#${modal}`).removeClass('modal-open');
    }

    const renderDetailGuestbook = (data) => {
        for (const key in data) {
            if (key == 'date') {
                data[key] = new Date(parseInt(data[key])).toLocaleString();
            }

            $(`#guestbookDetail${capitalizeFirstLetter(key)}`).text(data[key]);
        }
    }

    // CRUD
    const show = (id) => {
        const url = siteUrl + '<?= $showUrl ?>' + id;

        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    renderDetailGuestbook(response);
                    openModal();
                    reloadDatatable(tableId);
                } else {
                    closeModal();
                    alert(response.message);
                }
            }
        });
    }

    const destroy = (id) => {
        if (confirm('Apakah anda yakin?')) {
            const url = siteUrl + '<?= $destroyUrl ?>' + id;

            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                success: function(response) {
                    alert(response.message);
                    if (response.status) reloadDatatable(tableId);
                }
            });
        }
    }

    $(document).ready(function() {
        const table = createDataTable(tableId, siteUrl + '<?= $indexUrl ?>', [{
                title: 'Action',
                data: 'id',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return `
                        <button class="btn btn-sm btn-secondary" onclick="show('${data}')">Read</button>
                        <button class="btn btn-sm btn-error" onclick="destroy('${data}')">Delete</button>
                    `
                }
            },
            {
                title: 'Subject',
                name: 'subject',
                data: 'subject'
            },
            {
                title: 'From',
                name: 'name',
                data: 'name',
                render: function(data, type, row) {
                    return `
                        <div>
                            <p>${data}</p>
                            <p class="text-sm">${row.email}</p>
                        </div>
                    `
                }
            },
            {
                title: 'Phone',
                name: 'phone',
                data: 'phone',
            },
            {
                title: 'Date',
                name: 'created_at',
                data: 'created_at',
            },
            {
                title: 'Status',
                name: 'status',
                data: 'status',
                render: function(data) {
                    return `<div class="badge badge-${data == 'read' ? 'primary' : 'ghost'}">${data}</div>`;
                }
            }
        ]);
    });
</script>
<?= $this->endSection(); ?>