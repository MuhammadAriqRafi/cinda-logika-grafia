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
            <form id="loginForm" style="max-width: 270px;"></form>
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

        const displayError = (errorInput) => {
            errorInput.forEach(error => {
                $(`input[name="${error.input_name}"]`).addClass('input-error');
                $(`#error-${error.input_name}`).text(error.error_message);
                $(`#error-${error.input_name}`).removeClass('hidden');
            });
        }

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
                    if (response.status) {
                        alert(response.message);
                        window.location.replace(response.data);
                    } else {
                        if (response.error_input) displayError(response.error_input);
                    }
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