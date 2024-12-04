<?php
session_start();
include_once __DIR__ . "/db.php";

if (!isset($_SESSION['user'])) {
    header('location:index.php');
    exit();
}

$title;
$description;
$start_date;
$end_date;
$newEvent;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = ($_POST['title']);
    $description = ($_POST['description']);
    $start_date = ($_POST['start_date']);
    $end_date = ($_POST['end_date']);

    if (empty($title) || empty($start_date) || empty($end_date)) {
        $errors[] = 'Necesitas rellenar nombre, fecha de inicio y fecha fin del evento para guardarlo.';
    } else if (strtotime($start_date) == false) {
        $errors[] = 'La fecha de inicio del evento debe ser una fecha válida';
    } elseif (strtotime($end_date) == false || strtotime($end_date) < strtotime($start_date)) {
        $errors[] = 'La fecha final del evento debe ser válida y después de la fecha de inicio.';
    }

    if (count($errors) === 0) {
        $newEvent = new Event($_SESSION['user'],  $title, $description, $start_date, $end_date);
        $created = $calendarDataAccess->createEvent($newEvent);
        if (!$created) {
            echo 'No se ha creado';
            exit();
        }
        header('location:events.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Event</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header class="p-3 text-bg-dark">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <?php
                $user = $calendarDataAccess->getUserById($_SESSION['user']);
                $name = ($user->getFirstName());
                $lastname = ($user->getLastName());
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

    <main class="container mt-5">
        <div class="card p-4 shadow-sm mx-auto" style="max-width: 600px;">
            <h2 class="text-center mb-4">Nuevo Evento</h2>

            <?php
            if (!empty($errors)) {
                echo '<div class="alert alert-danger"><ul>';
                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }
                echo '</ul></div>';
            }
            ?>

            <form method="post">
                <div class="mb-3">
                    <label for="title" class="form-label">Título:</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción:</label>
                    <textarea name="description" id="description" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Comienza:</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="end_date" class="form-label">Termina:</label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Aceptar</button>
            </form>
        </div>
    </main>
</body>

</html>