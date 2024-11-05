<!-- views/incidente/list.php -->
<?php include 'views/layout/header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Incidentes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1>Lista de Incidentes</h1>
    <div class="row d-flex justify-content-around">
        <input id="incidentId" type="text" class="form-control w-75" placeholder="Digite el ID del incidente" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
        <button class="btn btn-primary mb-3" onClick="filtrarPorId()">Buscar</button>
        <a href="index.php?entity=incidente&action=create" class="btn btn-primary mb-3">Crear Incidente</a>
    </div>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Fecha de Ocurrencia</th>
                <th>Estado</th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
                <?php if (empty($incidentes)): ?>
                    <tr>
                        <td colspan="8" class="text-center">No hay resultados disponibles</td>
                    </tr>
                <?php else: ?>
                <?php foreach ($incidentes as $incidente): ?>
                <tr>
                    <td><?= $incidente['id'] ?></td>
                    <td><?= $incidente['descripcion'] ?></td>
                    <td><?= $incidente['fecha_ocurrencia'] ?></td>
                    <td><?= $incidente['estado_nombre'] ?></td>
                    <td><?= $incidente['usuario_nombre'] ?></td>
                    <td>
                        <a href="index.php?entity=incidente&action=show&id=<?= $incidente['id'] ?>" class="btn btn-info btn-sm">Ver</a>
                        <a href="index.php?entity=incidente&action=edit&id=<?= $incidente['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="index.php?entity=incidente&action=delete&id=<?= $incidente['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">Eliminar</a>
                        <a href="index.php?entity=incidente&action=planes_accion&id=<?= $incidente['id'] ?>" class="btn btn-secondary btn-sm">Planes de Acción</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
</div>

<script>
    function filtrarPorId() {
        const id = document.getElementById('incidentId').value;
        window.location.href = `index.php?entity=incidente&action=index&id=${id}`;
    }
</script>
</body>
</html>
