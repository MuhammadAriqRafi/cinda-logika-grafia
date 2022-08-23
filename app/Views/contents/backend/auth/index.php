<!DOCTYPE html>
<html lang="en" data-theme="night">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Cinda Logika Grafia</title>

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Custom styles for this template -->
    <link href="<?= base_url('css/app.css'); ?>" rel="stylesheet">
</head>

<body class="static">
    <div class="container mx-auto flex justify-center items-center h-screen gap-x-56 flex-col gap-y-8 sm:flex-row">
        <img src="<?= base_url('assets/images/logo-small.png') ?>" alt="Cinda Logika Grafia">
        <div class="flex flex-col h-fit p-8 rounded-lg shadow-xl">
            <!-- <div class="alert alert-error shadow-lg mb-6">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Error! Task failed successfully.</span>
                </div>
            </div> -->

            <form id="loginForm"></form>
            <button class="btn btn-primary mt-4 text-base font-bold" onclick="authenticate()">Login</button>
        </div>
    </div>

    <div class="absolute bottom-0 left-0 -z-10 max-w-lg md: rotate-180 opacity-25">
        <img src="<?= base_url('assets/images/group.png') ?>" alt="Cinda Logika Grafia">
    </div>

    <script src="<?= base_url('js/datatableUtilities.js'); ?>"></script>
    <script src="<?= base_url('js/formUtilities.js'); ?>"></script>
    <script>
        const form = 'loginForm';

        const authenticate = () => {
            const url = '<?= site_url() . $authenticateUrl ?>';
            const loginForm = $(`#${form}`)[0];
            const clientCredential = new FormData(loginForm);

            $.ajax({
                type: "POST",
                url: url,
                data: clientCredential,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    console.log(response);
                }
            });
        }

        $(document).ready(function() {
            $(`#${form}`).append(textInputComponent('Username', 'username'));
            $(`#${form}`).append(textInputComponent('Password', 'password', 'password'));
        });
    </script>
</body>

</html>