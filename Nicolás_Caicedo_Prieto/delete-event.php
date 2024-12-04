<?php
session_start();
include_once __DIR__ . "/db.php";

if (!isset($_SESSION['user'])) {
    header('location:index.php');
    exit();
}

$idEvent;
$eleccion;
$errors = [];
$eventId;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $eventId = $_GET['id'];
        $existingEvent = $calendarDataAccess->getEventById($eventId);
        if (!isset($existingEvent)) {
            $errors[] = 'No se puede acceder al evento porque no existe o porque no tiene permisos para verlo';
?>
            <a href="events.php" class="btn btn-secondary mt-3">Volver al listado de eventos</a>
            <?php
        } else {
            if ($existingEvent->getUserId() != $_SESSION['user']) {
                $errors[] = 'No se puede acceder al evento porque no existe o porque no tiene permisos para verlo';
            ?>
                <a href="events.php" class="btn btn-secondary mt-3">Volver al listado de eventos</a>
<?php
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (count($errors) == 0)) {
    $eleccion = $_POST['select'];
    $eventId = $_GET['id'];

    if ($eleccion === "yes") {
        $eventDeleted = $calendarDataAccess->deleteEvent($eventId);
        header('location:events.php');
        echo $eventDeleted ? "Evento eliminado con éxito." : "Error al eliminar evento.";
    } else if ($eleccion === "no") {
        header('location:events.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Event</title>
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
            <h2 class="text-center mb-4">Eliminar Evento</h2>

            <?php
            if (!empty($errors)) {
                echo '<div class="alert alert-danger"><ul>';
                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }
                echo '</ul></div>';
            } else {
            ?>

                <form method="post">
                    <h3 class="text-center mb-3">¿Seguro que desea eliminar el evento <strong>
                            <título>
                        </strong>?</h3>

                    <div class="d-grid gap-2">
                        <button name="select" value="yes" type="submit" class="btn btn-danger">Sí, eliminar el evento</button>
                        <button name="select" value="no" type="submit" class="btn btn-secondary">No, volver al listado de eventos</button>
                    </div>
                </form>

            <?php } ?>
        </div>
    </main>
</body>

</html>