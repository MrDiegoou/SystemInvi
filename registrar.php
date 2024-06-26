<?php
// Incluye el archivo de conexión a la base de datos
include("conexion.php");

// Habilitar la visualización de errores de PHP
ini_set('display_errors', 1);

// Verifica si se han enviado datos del formulario de registro
if (isset($_POST['register'])) {
    // Obtén los datos del formulario y realiza la validación
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $doc_identi = $_POST['doc_identi'];
    $motivo = $_POST['motivo']; 
    $visita = $_POST['visita'];
    $nombre_emergencia = $_POST['nombre_emergencia'];
    $telefono_emergencia = $_POST['telefono_emergencia'];
    
    // Validación de datos
    if (empty($nombres) || empty($apellidos) || empty($doc_identi) || empty($visita) || empty($nombre_emergencia) ||  empty($telefono_emergencia)) {
        echo 'Por favor completa todos los campos.';
    } else {
        try {
            // Validar formato del nombre
            if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóú\s]+$/', $nombres)) {
                throw new Exception('Formato de nombre incorrecto. No se permiten números ni caracteres especiales.');
            }

            // Validar formato del apellido
            if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóú\s]+$/', $apellidos)) {
                throw new Exception('Formato de apellido incorrecto. No se permiten números ni caracteres especiales.');
            }

            // Validar formato de la cédula
            if (!preg_match('/^[0-9]{7,10}$/', $doc_identi)) {
                throw new Exception('Formato de cédula incorrecto. Debe ser una cédula válida.');
            }

            // Validar formato del teléfono 
            if (!preg_match('/^[0-9]{10}$/', $telefono_emergencia)) {
                throw new Exception('Formato de número incorrecto. Debe tener 10 dígitos.');
            }

            // Crea la consulta SQL preparada
            $consulta = "INSERT INTO usuarios (nombres, apellidos, doc_identi, motivo, visita, nombre_emergencia, telefono_emergencia, fecha, hora) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE(), CURTIME())";

            // Prepara la consulta
            $stmt = mysqli_prepare($conex, $consulta);

            // Vincula los parámetros a la consulta
            mysqli_stmt_bind_param($stmt, "ssissss", $nombres, $apellidos, $doc_identi, $motivo, $visita, $nombre_emergencia, $telefono_emergencia);

            // Ejecuta la consulta
            if (mysqli_stmt_execute($stmt)) {
                echo '¡Tu registro ha sido todo un éxito! Que tengas un día increíble lleno de logros y oportunidades. ¡Espacio y Mercadeo está aquí para hacer brillar tu día!';
            } else {
                echo 'Hubo un error al registrar. Por favor, inténtelo de nuevo más tarde.';
            }

            // Cierra la declaración preparada
            mysqli_stmt_close($stmt);
        } catch (Exception $e) {
            // Manejo de excepciones: Mostrar un mensaje personalizado
            echo $e->getMessage();
        }
    }
}
?>
