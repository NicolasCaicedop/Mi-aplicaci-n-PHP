<?php
session_start();

include_once __DIR__ . "/db.php";

$errorMsg = [];
$email = "";
$password = "";

if (isset($_SESSION['user'])) {
    header('location:events.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['email']) || strlen($_POST['email']) == 0 || empty($_POST['password']) || strlen($_POST['password']) == 0) {
        $errorMsg[] = "Debes rellenar ambos datos";
    } else {

        $email = ($_POST['email']);
        $password = ($_POST['password']);


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg[] = "Por favor ingresa un correo valido";
        } else {


            if (count($errorMsg) === 0) {
                $users = $calendarDataAccess->getAllUsers();
                foreach ($users as $user) {
                    $email = $user->getEmail();
                    $password = $user->getPassword();

                    if ((isset($_POST['email'])) && $email == $_POST['email']
                        && (isset($_POST['password'])) && password_verify($_POST['password'], $password)
                    ) {
                        $_SESSION['user'] = $user->getId();
                        session_regenerate_id();
                        header('location:events.php');
                        exit();
                    }
                }
            }
        }
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <main class="form-signin w-100 m-auto bg-white p-4 rounded shadow-lg" style="max-width: 400px;">
        <h1 class="h3 mb-3 fw-normal text-center">Iniciar Sesión</h1>

        <?php if (count($errorMsg) > 0): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errorMsg as $msg) : ?>
                        <li><?= $msg ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" value="<?= htmlspecialchars($email) ?>" required>
                <label for="email">Correo Electrónico</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
                <label for="password">Contraseña</label>
            </div>
            <button type="submit" class="w-100 btn btn-lg btn-primary">Aceptar</button>
        </form>

        <div class="mt-3 text-center">
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate</a></p>
        </div>
    </main>
</body>

</html>