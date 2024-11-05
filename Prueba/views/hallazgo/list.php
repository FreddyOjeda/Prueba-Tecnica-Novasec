<!-- views/hallazgo/list.php -->
<?php include 'views/layout/header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Hallazgos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Lista de Hallazgos</h1>
        <div class="row d-flex justify-content-around">
            <select class="form-control w-75" name="sede_id" id="sedeSelect" onchange="filtrarPorSede()">
                <option value="">Seleccione una sede</option>
                <?php foreach ($sedes as $sede): ?>
                    <option value="<?= $sede['id'] ?>" <?= isset($_GET['sede_id']) && $_GET['sede_id'] == $sede['id'] ? 'selected' : '' ?>>
                        <?= $sede['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <a href="index.php?entity=hallazgo&action=create" class="btn btn-primary mb-3">Crear Hallazgo</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Sede</th>
                    <th>Estado</th>
                    <th>Usuario</th>
                    <th>Procesos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($hallazgos)): ?>
                    <tr>
                        <td colspan="8" class="text-center">No hay resultados disponibles</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($hallazgos as $hallazgo): ?>
                    <tr>
                        <td><?= $hallazgo['id'] ?></td>
                        <td><?= $hallazgo['titulo'] ?></td>
                        <td><?= $hallazgo['descripcion'] ?></td>
                        <td><?= $hallazgo['sede_nombre'] ?></td>
                        <td>
                            <select class="form-control" style="width: 125px;" onchange="confirmarCambioEstado(<?= $hallazgo['id'] ?>, this.value)">
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?= $estado['id'] ?>" <?= $hallazgo['id_estado'] == $estado['id'] ? 'selected' : '' ?>>
                                        <?= $estado['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><?= $hallazgo['usuario_nombre'] ?></td>
                        <td>
                            <ul>
                                <?php foreach ($hallazgo['procesos'] as $proceso): ?>
                                    <li><?= $proceso['nombre'] ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            <a href="index.php?entity=hallazgo&action=show&id=<?= $hallazgo['id'] ?>" class="btn btn-info btn-sm">Ver</a>
                            <a href="index.php?entity=hallazgo&action=edit&id=<?= $hallazgo['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="index.php?entity=hallazgo&action=delete&id=<?= $hallazgo['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">Eliminar</a>
                            <a href="index.php?entity=hallazgo&action=planes_accion&id=<?= $hallazgo['id'] ?>" class="btn btn-secondary btn-sm">Planes de Acción</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
    <script>
        function filtrarPorSede() {
            const sedeId = document.getElementById('sedeSelect').value;
            window.location.href = `index.php?entity=hallazgo&action=index&sede_id=${sedeId}`;
        }
        function confirmarCambioEstado(idHallazgo, nuevoEstado) {
            const confirmacion = confirm("¿Estás seguro de que deseas cambiar el estado de este hallazgo?");
            if (confirmacion) {
                window.location.href = `index.php?entity=hallazgo&action=updateEstado&id=${idHallazgo}&estado_id=${nuevoEstado}`;
                alert("Cambio de estado exitoso.")
            }
        }
    </script>
</body>
</html>