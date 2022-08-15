<!DOCTYPE html>
<html lang="en" data-theme="night">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backend | Cinda Logika Grafia</title>

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

    <!-- Custom styles for this template -->
    <link href="<?= base_url('css/app.css'); ?>" rel="stylesheet">

    <!-- Storing Utility Js Variables -->
    <script>
        let siteUrl = '<?= site_url(); ?>';
        let baseUrl = '<?= base_url(); ?>';
    </script>

    <!--Inline Custom Styling -->
    <style>
        ul.pagination {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            border: 1px solid;
            padding: 12px;
            border-radius: 8px;
        }

        ul.pagination>li:hover {
            color: #FBBD23;
        }

        input[type="search"] {
            padding: 12px;
            border: 1px solid #2A303C;
            border-radius: 8px;
        }
    </style>

    <?= $this->renderSection('css'); ?>
</head>

<body>
    <div class="overflow-y-hidden max-h-screen">

        <?= $this->renderSection('modal'); ?>

        <div class="drawer drawer-mobile">
            <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

            <!-- Page Content -->
            <div class="drawer-content flex flex-col items-center justify-start">
                <!-- Navbar -->
                <div class="navbar bg-base-100 justify-between pr-6 pt-4">
                    <!-- Mobile Navbar Button -->
                    <div class="flex-none">
                        <label for="my-drawer-2" class="btn drawer-button lg:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </label>
                    </div>
                    <!-- End of Mobile Navbar Button -->

                    <div class="flex-none gap-2">
                        <!-- Profile Dropdows -->
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                                <div class="w-10 rounded-full">
                                    <img src="https://placeimg.com/80/80/people" />
                                </div>
                            </label>

                            <ul tabindex="0" class="mt-3 p-2 shadow menu menu-compact dropdown-content bg-base-100 rounded-box w-52">
                                <li>
                                    <a class="justify-between">
                                        Profile
                                        <span class="badge">New</span>
                                    </a>
                                </li>
                                <li><a>Settings</a></li>
                                <li><a class="btn btn-error mt-4" href="<?= route_to('logout'); ?>">Logout</a></li>
                            </ul>
                        </div>
                        <!-- End of Profile Dropdows -->
                    </div>
                </div>
                <!-- End of Navbar -->

                <!-- Body -->
                <main class="w-full pt-8 sm:pt-4 p-4 pr-6 mb-8 overflow-x-hidden overflow-y-auto">
                    <div class="mb-10 flex justify-between items-center">
                        <h1 class="text-2xl sm:text-4xl font-bold"><?= $title ?? 'Untitled'; ?></h1>
                        <?= $this->renderSection('toolbar'); ?>
                    </div>
                    <?= $this->renderSection('content'); ?>
                </main>
                <!-- End of Body -->
            </div>
            <!-- End of Page Content -->

            <!-- Sidebar -->
            <div class="drawer-side">
                <label for="my-drawer-2" class="drawer-overlay"></label>
                <ul class="menu p-4 pl-6 overflow-y-auto w-80 bg-base-100 text-base-content">
                    <a class="btn btn-ghost normal-case text-xl justify-start mb-4 h-fit py-4" href="<?= route_to('backend.dashboard.index'); ?>">
                        <img src="<?= base_url('assets/images/logo.png') ?>" alt="">
                    </a>
                    <?= $this->include('layouts/backend/sidebar'); ?>
                    <div class="btn-group sm:hidden inline mt-auto mx-auto mb-2">
                        <a class="btn btn-active" href="<?= route_to('logout'); ?>">Logout</a>
                    </div>
                </ul>
            </div>
            <!-- End of Sidebar -->
        </div>
    </div>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <!-- Custom Script -->
    <script src="<?= base_url('js/datatableUtilities.js'); ?>"></script>
    <script src="<?= base_url('js/formUtilities.js'); ?>"></script>
    <?= $this->renderSection('script'); ?>
</body>

</html>