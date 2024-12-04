<?php
session_start();
include_once __DIR__ . "/db.php";

if (!isset($_SESSION['user'])) {
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-light">
    <header class="p-3 bg-dark text-white mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <?php
                $user = $calendarDataAccess->getUserById($_SESSION['user']);
                $name = htmlspecialchars($user->getFirstName());
                $lastname = htmlspecialchars($user->getLastName());
                ?>
                <h1 class="h3 mb-0"><?php echo "$name $lastname"; ?></h1>
            </div>
            <nav>
                <a href="logout.php" class="text-white me-3">Salir de sesión</a>
                <a href="#!" class="text-white me-3">Cambiar contraseña</a>
                <a href="#!" class="text-white">Perfil</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <h1 class="mb-4 text-center">Eventos</h1>
        <div class="d-flex justify-content-end mb-3">
            <a href="new-event.php" class="btn btn-primary">Crear nuevo evento</a>
        </div>

        <?php
        $events = $calendarDataAccess->getEventsByUserId($_SESSION['user']);
        if (count($events) > 0):
        ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Comienza</th>
                            <th>Termina</th>
                            <th>ID</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($events as $event):
                            $titulo = htmlspecialchars($event->getTitle());
                            $descrip = htmlspecialchars($event->getDescription());
                            $comienza = htmlspecialchars($event->getStartDate());
                            $termina = htmlspecialchars($event->getEndDate());
                            $id = htmlspecialchars($event->getId());
                        ?>
                            <tr>
                                <td><?php echo $titulo; ?></td>
                                <td><?php echo $descrip; ?></td>
                                <td><?php echo $comienza; ?></td>
                                <td><?php echo $termina; ?></td>
                                <td><?php echo $id; ?></td>
                                <td>
                                    <a href="#!" class="btn btn-sm btn-warning">Editar</a>
                                    <a href="delete-event.php?id=<?php echo $id; ?>" class="btn btn-sm btn-danger">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center">No tienes eventos registrados.</p>
        <?php endif; ?>

        <div class="mt-4 text-center">
            <a href="new-event.php" class="btn btn-outline-primary">Crear nuevo evento</a>
            <a href="logout.php" class="btn btn-outline-danger ms-2">Salir de la aplicación</a>
        </div>
    </main>

</body>

</html>