<?php
session_start();

function verificarSesion() {
    if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
        header('Location: iniciosesion.php');
        exit();
    }
}

function verificarPermiso($roles_permitidos) {
    verificarSesion();
    
    if (!in_array($_SESSION['rol'], $roles_permitidos)) {
        header('Location: acceso_denegado.php');
        exit();
    }
    return true;
}

function esAdministrador() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'Responsable';
}

function esDocente() {
    return isset($_SESSION['rol']) && ($_SESSION['rol'] === 'Docente' || $_SESSION['rol'] === 'Responsable');
}

function esEstudiante() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'Estudiante';
}
?> 