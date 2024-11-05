<!-- views/hallazgo/edit.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Hallazgo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .tag {
            display: inline-block;
            padding: 5px 10px;
            margin: 5px;
            background-color: #007bff;
            color: white;
            border-radius: 15px;
            position: relative;
        }
        .tag .close {
            margin-left: 10px;
            cursor: pointer;
            font-weight: bold;
            color: white;
            position: absolute;
            right: 8px;
            top: 4px;
        }
    </style>
</head>
<body>
<?php include 'views/layout/header.php'; ?>
<div class="container mt-4">
    <h1>Editar Hallazgo</h1>
    <form action="index.php?entity=hallazgo&action=edit&id=<?= $hallazgo['id'] ?>" method="POST">
        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="<?= $hallazgo['titulo'] ?>" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required><?= $hallazgo['descripcion'] ?></textarea>
        </div>
        <div class="form-group">
            <label for="id_sede">Sede</label>
            <select class="form-control" id="id_sede" name="id_sede" required>
                <option value="" <?= empty($hallazgo['sede_id']) ? 'selected' : '' ?>>Seleccione una sede</option>
                <?php foreach ($sedes as $sede): ?>
                    <option value="<?= $sede['id'] ?>" <?= (isset($hallazgo['sede_id']) && $sede['id'] == $hallazgo['sede_id']) ? 'selected' : '' ?>>
                        <?= $sede['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="id_estado">Estado</label>
            <select class="form-control" id="id_estado" name="id_estado" required>
                <?php foreach ($estados as $estado): ?>
                    <option value="<?= $estado['id'] ?>" <?= ($estado['id'] == $hallazgo['id_estado']) ? 'selected' : '' ?>>
                        <?= $estado['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="id_usuario">Usuario Responsable</label>
            <select class="form-control" id="id_usuario" name="id_usuario" required>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>" <?= ($usuario['id'] == $hallazgo['id_usuario']) ? 'selected' : '' ?>>
                        <?= $usuario['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="procesosSelect">Seleccionar Proceso</label>
            <select class="form-control" id="procesosSelect">
                <option value="">Seleccione un proceso...</option>
                <?php foreach ($procesos as $proceso): ?>
                    <?php if (!in_array($proceso['id'], $selectedProcesoIds)): ?>
                        <option value="<?= $proceso['id'] ?>"><?= $proceso['nombre'] ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Mostrar procesos seleccionados como tags -->
        <div id="selectedProcesses" class="mb-3">
            <?php foreach ($selectedProcesoIds as $id): ?>
                <span class="tag" data-id="<?= $id ?>">
                    <?= $procesos[$id-1]['nombre'] ?>
                    <span class="close" onclick="eliminarProceso(<?= $id ?>)">X</span>
                </span>
                <input type="hidden" name="procesos[]" value="<?= $id ?>" id="input-<?= $id ?>">
            <?php endforeach; ?>
        </div>

        <div id="hiddenInputsContainer"></div>
        
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="index.php?entity=hallazgo&action=index" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    const selectedProcesses = <?= json_encode($selectedProcesoIds) ?>;

    function agregarProceso() {
        const select = document.getElementById('procesosSelect');
        const selectedValue = select.value;
        const selectedText = select.options[select.selectedIndex].text;

        if (selectedValue && !selectedProcesses.includes(selectedValue)) {
            selectedProcesses.push(selectedValue);
            
            // Crear un tag para el proceso seleccionado
            const tag = document.createElement('span');
            tag.className = 'tag';
            tag.dataset.id = selectedValue;
            tag.innerHTML = `${selectedText} <span class="close" onclick="eliminarProceso(${selectedValue})">X</span>`;
            document.getElementById('selectedProcesses').appendChild(tag);
            
            // Crear un input oculto para enviar el valor seleccionado
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'procesos[]';
            hiddenInput.value = selectedValue;
            hiddenInput.id = `input-${selectedValue}`;
            document.getElementById('hiddenInputsContainer').appendChild(hiddenInput);
            
            // Eliminar la opción del select
            select.querySelector(`option[value="${selectedValue}"]`).remove();
            select.value = ''; // Reiniciar el select
        }
    }

    function eliminarProceso(value) {
        const index = selectedProcesses.indexOf(value);
        if (index > -1) {
            selectedProcesses.splice(index, 1);

            // Remover el tag del DOM
            const tag = document.querySelector(`.tag[data-id='${value}']`);
            if (tag) tag.remove();

            // Eliminar el input oculto correspondiente
            const hiddenInput = document.getElementById(`input-${value}`);
            if (hiddenInput) hiddenInput.remove();

            // Volver a agregar la opción al select
            const select = document.getElementById('procesosSelect');
            const option = document.createElement('option');
            option.value = value;
            option.text = tag.innerText.slice(0, -1); // Remover ' X' del texto
            select.appendChild(option);
        }
    }

    // Añadir evento para el select
    document.getElementById('procesosSelect').addEventListener('change', agregarProceso);
</script>

</body>
</html>
