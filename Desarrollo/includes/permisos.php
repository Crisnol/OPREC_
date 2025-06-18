<?php
session_start();

// Función para verificar si el usuario está logueado
function verificarSesion() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: iniciosesion.php");
        exit();
    }
}

// Función para verificar permisos específicos
function verificarPermiso($roles_permitidos) {
    verificarSesion();
    if (!in_array($_SESSION['rol'], $roles_permitidos)) {
        header("Location: inicio.php");
        exit();
    }
}

// Función para verificar si el usuario es administrador
function esAdministrador() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'Responsable';
}

// Función para verificar si el usuario es docente
function esDocente() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'Docente';
}

// Función para verificar si el usuario es estudiante
function esEstudiante() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'Estudiante';
}
?> 