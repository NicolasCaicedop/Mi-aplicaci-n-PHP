<?php
session_start();
include_once __DIR__ . "/db.php";

if (!isset($_SESSION['user'])) {
    header('location:index.php');
    exit();
}

if ((isset($_POST['opciones'])) && ($_POST['opciones']) == 'si') {
    session_destroy();
    header('location:index.php');
    session_regenerate_id();
    exit();
} else if ((isset($_POST['opciones'])) && ($_POST['opciones']) == 'no') {
    header('location:events.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header class="p-3 text-bg-dark">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <?php
                $user = $calendarDataAccess->getUserById($_SESSION['user']);
                $name = htmlspecialchars($user->getFirstName());
                $lastname = htmlspecialchars($user->getLastName());
                ?>
                <h1 class="h3 mb-0"><?php echo "$name $lastname"; ?></h1>
                <nav>
                    <a href="logout.php" class="text-white me-3">Salir de sesión</a>
                    <a href="#!" class="text-white me-3">Cambiar contraseña</a>
                    <a href="#!" class="text-white">Perfil</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="container text-center mt-5">
        <div class="card shadow-sm p-4 mx-auto" style="max-width: 400px;">
            <h2 class="mb-4">¿Seguro que desea desconectar?</h2>
            <form method="post">
                <button name="opciones" value="si" type="submit" class="btn btn-danger w-100 mb-3">Sí, desconectar</button>
                <button name="opciones" value="no" type="submit" class="btn btn-secondary w-100">No, volver al listado de eventos</button>
            </form>
        </div>
    </main>
</body>

</html>