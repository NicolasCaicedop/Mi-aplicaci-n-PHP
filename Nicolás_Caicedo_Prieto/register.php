<?php

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: events.php');
    exit();
}
include_once __DIR__ . "/db.php";

$errors = [];
$success;
$email;
$nombre;
$apellidos;
$fecha_nacimiento;
$password;
$confirm_password;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $nombre = ($_POST['nombre']);
    $apellidos = ($_POST['apellidos']);
    $fecha_nacimiento = ($_POST['fecha_nacimiento']);
    $password = ($_POST['password']);
    $confirm_password = ($_POST['confirm_password']);


    if (empty($email) || empty($nombre) || empty($apellidos) || empty($fecha_nacimiento) || empty($password) || empty($confirm_password)) {
        $errors[] = 'Necesitas rellenar todos los espacios.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Debe ser un correo valido.';
    } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        $errors[] = 'La contraseña debe tener al menos 8 caracteres, incluyendo una letra y un número.';
    } else if ($confirm_password != $password) {
        $errors[] = 'La contraseña no es la misma.';
    }


    if (count($errors) === 0) {
        $existingUser = $calendarDataAccess->getUserByEmail($email);
        if (isset($existingUser)) {
            $errors[] = 'Usuario ya existente ';
        } else {

            $newUser = new User($email,  password_hash($password, PASSWORD_DEFAULT), $nombre, $apellidos, $fecha_nacimiento, 'Un nuevo usuario.');
            $created = $calendarDataAccess->createUser($newUser);
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light">

    <main class="bg-white p-5 rounded shadow-lg" style="max-width: 500px; width: 100%;">
        <h1 class="h3 mb-3 fw-normal text-center">Registro</h1>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && (count($errors) == 0)) {
            echo "<div class='alert alert-success'>Usuario creado.</div>";
        ?>
            <p class="text-center">¿Quieres loguearte? <a href="index.php">Login</a></p>
        <?php
        } else {
            if (!empty($errors)) {
                echo "<div class='alert alert-danger'><ul class='mb-0'>";
                foreach ($errors as $error) {
                    echo "<li>" . htmlspecialchars($error) . "</li>";
                }
                echo "</ul></div>";
            }
        ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" required>
                </div>

                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="apellidos" id="apellidos" required>
                </div>

                <div class="mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirma la Contraseña</label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                <p class="text-center mt-3">¿Ya tienes una cuenta? <a href="index.php">Login</a></p>
            </form>
        <?php
        }
        ?>
    </main>
</body>

</html>