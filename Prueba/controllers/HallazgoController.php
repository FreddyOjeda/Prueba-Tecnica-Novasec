<?php
// controllers/HallazgoController.php
require_once 'models/HallazgoModel.php';
require_once 'models/ProcesoModel.php';
require_once 'models/EstadoModel.php';
require_once 'models/UsuarioModel.php';
require_once 'models/SedeModel.php';
require_once 'models/PlanAccionModel.php';

class HallazgoController {
    private $model;
    private $procesoModel;
    private $estadoModel;
    private $usuarioModel;
    private $sedeModel;
    private $planAccionModel;

    public function __construct($pdo) {
        $this->model = new HallazgoModel($pdo);
        $this->procesoModel = new ProcesoModel($pdo);
        $this->estadoModel = new EstadoModel($pdo);
        $this->usuarioModel = new UsuarioModel($pdo);
        $this->sedeModel = new SedeModel($pdo);
        $this->planAccionModel = new PlanAccionModel($pdo);
    }

    public function index() {
        $sede_id = $_GET['sede_id'] ?? null;
        $hallazgos = $this->model->getAll($sede_id);
        $sedes = $this->sedeModel->getAll();
        $estados = $this->estadoModel->getAll();
    
        require 'views/hallazgo/list.php';
    }

    public function show($id) {
        $hallazgo = $this->model->getById($id);
        require 'views/hallazgo/show.php';
    }

    public function create() {
        $procesos = $this->procesoModel->getAll();
        $estados = $this->estadoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
        $sedes = $this->sedeModel->getAll();
        require 'views/hallazgo/create.php';
    }

    public function insert($data) {
        $titulo = $data['titulo'];
        $descripcion = $data['descripcion'];
        $proceso_ids = $data['procesos'] ?? [];
        $id_estado = $data['id_estado'];
        $id_usuario = $data['id_usuario'];
        $id_sede = $data['id_sede'];

        $this->model->insert($titulo, $descripcion, $proceso_ids, $id_estado, $id_usuario, $id_sede);
        header('Location: index.php?entity=hallazgo&action=index');
    }

    public function edit($id) {
        $hallazgo = $this->model->getById($id);
        $procesos = $this->procesoModel->getAll();
        $estados = $this->estadoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
        $sedes = $this->sedeModel->getAll();
        $selectedProcesos = $this->model->getProcesos($hallazgo['id']);
        $selectedProcesoIds = array_column($selectedProcesos, 'id');
        require 'views/hallazgo/edit.php';
    }

    public function update($id, $data) {
        $titulo = $data['titulo'];
        $descripcion = $data['descripcion'];
        $proceso_ids = $data['procesos'] ?? [];
        $id_estado = $data['id_estado'];
        $id_usuario = $data['id_usuario'];
        $id_sede = $data['id_sede'];

        $this->model->update($id, $titulo, $descripcion, $proceso_ids, $id_estado, $id_usuario, $id_sede);
        header('Location: index.php?entity=hallazgo&action=index');
    }

    public function updateEstadoHallazgo($id_hallazgo, $estado_id) {
        $this->model->updateEstado($id_hallazgo, $estado_id);
        header("Location: index.php?entity=hallazgo&action=index");
    }    

    public function delete($id) {
        $this->model->delete($id);
        header('Location: index.php?action=index');
    }

    // Método para manejar la solicitud de planes de acción
    public function planesAccion($id_hallazgo) {
        $hallazgo = $this->model->getById($id_hallazgo);
        $planesAccion = $this->planAccionModel->getByRegistro($id_hallazgo, 'HALLAZGO');
        $estados = $this->estadoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
    
        $titulo = 'Hallazgo';
        $descripcion = $hallazgo['descripcion'];
        $id = $hallazgo['id'];
        $entity = 'hallazgo';
    
        require 'views/planaccion/planes_accion.php';
    }

    // Método para insertar un plan de acción
    public function insertPlanAccion($id_hallazgo, $data) {
        $id_plan_accion = $this->planAccionModel->insert($data);
        if ($id_plan_accion) {
            $this->planAccionModel->linkToRegistro($id_plan_accion, $id_hallazgo, 'HALLAZGO');
        }
        header('Location: index.php?entity=hallazgo&action=planes_accion&id=' . $id_hallazgo);
    }

    // Método para actualizar un plan de acción
    public function updatePlanAccion($id_hallazgo, $id_plan_accion, $data) {
        $this->planAccionModel->update($id_plan_accion, $data);
        header('Location: index.php?entity=hallazgo&action=planes_accion&id=' . $id_hallazgo);
    }

    // Método para eliminar un plan de acción
    public function deletePlanAccion($id_hallazgo, $id_plan_accion) {
        $this->planAccionModel->unlinkFromRegistro($id_plan_accion, $id_hallazgo, 'HALLAZGO');
        $this->planAccionModel->delete($id_plan_accion);
        header('Location: index.php?entity=hallazgo&action=planes_accion&id=' . $id_hallazgo);
    }
}
?>