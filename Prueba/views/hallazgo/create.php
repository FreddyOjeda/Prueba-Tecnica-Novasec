<!-- views/hallazgo/create.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Hallazgo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .tag {
            display: inline-block;
            padding: 5px 10px;
            margin: 5px;
            background-color: #007bff;
            color: white;
            border-radius: 15px;
        }
        .tag .close {
            margin-left: 5px;
            cursor: pointer;
            color: white;
        }
    </style>
</head>
<body>
<?php include 'views/layout/header.php'; ?>
<div class="container mt-4">
    <h1>Crear Hallazgo</h1>
    <form action="index.php?entity=hallazgo&action=create" method="POST">
        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
        </div>
        <div class="form-group">
            <label for="id_sede">Sede</label>
            <select class="form-control" id="id_sede" name="id_sede" required>
                <option value="" <?= empty($hallazgo['sede_id']) ? 'selected' : '' ?>>Seleccione una sede</option>
                <?php foreach ($sedes as $sede): ?>
                    <option value="<?= $sede['id'] ?>"><?= $sede['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="id_estado">Estado</label>
            <select class="form-control" id="id_estado" name="id_estado" required>
                <?php foreach ($estados as $estado): ?>
                    <option value="<?= $estado['id'] ?>"><?= $estado['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="id_usuario">Usuario Responsable</label>
            <select class="form-control" id="id_usuario" name="id_usuario" required>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>"><?= $usuario['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="procesosSelect">Seleccionar Proceso</label>
            <select class="form-control" id="procesosSelect">
                <option value="">Seleccione un proceso...</option>
                <?php foreach ($procesos as $proceso): ?>
                    <option value="<?= $proceso['id'] ?>"><?= $proceso['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="selectedProcesses" class="mb-3">
            <!-- Aquí se agregarán los tags dinámicamente -->
        </div>
        
        <!-- Contenedor oculto donde se almacenarán los valores seleccionados para el envío del formulario -->
        <div id="hiddenInputsContainer"></div>
        
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="index.php?entity=hallazgo&action=index" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    const selectedProcesses = [];

    function agregarProceso() {
        const select = document.getElementById('procesosSelect');
        const selectedValue = select.value;
        const selectedText = select.options[select.selectedIndex].text;

        // Evitar agregar si ya está seleccionado o si no hay selección
        if (selectedValue && !selectedProcesses.includes(selectedValue)) {
            selectedProcesses.push(selectedValue);
            
            // Crear un tag para el proceso seleccionado
            const tag = document.createElement('span');
            tag.className = 'tag';
            tag.innerText = selectedText;
            const close = document.createElement('span');
            close.className = 'close';
            close.innerText = 'X';
            close.onclick = () => eliminarProceso(selectedValue, tag);
            tag.appendChild(close);
            document.getElementById('selectedProcesses').appendChild(tag);
            
            // Crear un input oculto para enviar el valor seleccionado en el formulario
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

    function eliminarProceso(value, tag) {
        const index = selectedProcesses.indexOf(value);
        if (index > -1) {
            selectedProcesses.splice(index, 1);
            tag.remove(); // Elimina el tag del DOM
            
            // Eliminar el input oculto correspondiente
            document.getElementById(`input-${value}`).remove();

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
