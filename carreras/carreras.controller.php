<?php

include("../conexion.php");



//$action = "obtener_registros";
@$action = $_POST["operacion"];

main($action, $conexion);

//CREACION FUNCION MAIN
function main($action, $conexion)
{
    // el methodo switch toma varias funciones en cada case(caso) para que por medio del argumentoy/o varible
    //$action defina cual va  a hacer uso la main

    switch ($action) {
        case 'crear': //en caso de que action sea crear se ejectura la funcion crear; si no en caso de editar y borrar lo mismo
            crear($conexion);
            break;
        case 'editar':
            Editar($conexion);
            break;
        case 'borrar':
            Borrar($conexion);
            break;
        case 'obtener_registro': //en caso de que action sea crear se ejectura la funcion crear; si no en caso de editar y borrar lo mismo
            obtener_registro($conexion);
            break;


        default:
            obtener_registros($conexion);

            break;
    }
}

//CREACION FUNCION CREAR : debe tener el agumento de conexion ya que debe hacerlo en la 
//base de datos

function crear($conexion)
{

    $stmt = $conexion->prepare("INSERT INTO carreras(codigo_carrera, descripcion_carrera, valor_total_carrera, estado) VALUES(:codigo_carrera, :descripcion_carrera, :valor_total_carrera, :estado)");

    $resultado = $stmt->execute(
        array(
            'codigo_carrera' => $_POST["codigo_carrera"],
            ':descripcion_carrera' => $_POST["descripcion_carrera"],
            ':valor_total_carrera' => $_POST["valor_total_carrera"],
            ':estado' => $_POST["estado"]
        )
    );

    if (!empty($resultado)) {
        echo 'Registro creado';
    } else {
        echo 'Registro no creado';
    }
}
//CREACION FUNCION EDITAR : esto anidado en la funcion crear inicial
function Editar($conexion)
{

    $stmt = $conexion->prepare("UPDATE carreras SET descripcion_carrera=:descripcion_carrera, valor_total_carrera=:valor_total_carrera,
     estado=:estado WHERE codigo_carrera = :codigo_carrera");
    $resultado = $stmt->execute(
        array(

            
            ':descripcion_carrera'  => $_POST["descripcion_carrera"],
            ':valor_total_carrera'  => $_POST["valor_total_carrera"],
            ':estado'  => $_POST["estado"],
            ':codigo_carrera'  => $_POST["codigo_carrera"]
        )
    );
    if (!empty($resultado)) {
        echo 'Registro actulizado';
    } else {
        echo "No se pudo actulizar el registro";
    };
}

//CREACION FUNCION BORRAR : anidado en la de crear 
function Borrar($conexion)
{

    if (isset($_POST["codigo_carrera"])) {

        $stmt = $conexion->prepare("DELETE FROM carreras WHERE codigo_carrera = :codigo_carrera");

        $resultado = $stmt->execute(
            array(
                ':codigo_carrera'  => $_POST["codigo_carrera"]
            )
        );
        if (!empty($resultado)) {
            echo 'Registro borrado';
        }
    }
}







function obtener_registro($conexion)
{

    $salida = array();

    try {
        $stmt = $conexion->prepare("SELECT * FROM carreras WHERE codigo_carrera = :codigo_carrera LIMIT 1");
        $stmt->bindParam(':codigo_carrera', $_POST['codigo_carrera'], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $salida = $resultado;
        } else {
            $salida["error"] = "No se encontraron resultados";
        }
    } catch (PDOException $e) {
        $salida["error"] = "Error en la ejecución de la consulta: " . $e->getMessage();
    }
    echo json_encode($salida);
}






function obtener_registros($conexion)
{
    $query = "";
    $salida = array();
    $query = "SELECT * FROM carreras ";

    if (isset($_POST["search"]["value"])) {
        $query .= 'WHERE descripcion_carrera LIKE "%' . $_POST["search"]["value"] . '%" ';
    }

    if (isset($_POST["order"])) {
        $query .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST["order"][0]['dir'] . ' ';
    } else {
        $query .= 'ORDER BY codigo_carrera DESC ';
    }

    if (isset($_POST['length']) && isset($_POST['start'])) {
        $query .= 'LIMIT ' . ($_POST["start"]) . ',' . $_POST["length"];
    }

    $stmt = $conexion->prepare($query);

    try {
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        $datos = array();
        $filtered_rows = $stmt->rowCount();

        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;

        foreach ($resultado as $fila) {
            $sub_array = array();
            $sub_array[] = $fila["codigo_carrera"];
            $sub_array[] = $fila["descripcion_carrera"];
            $sub_array[] = $fila["valor_total_carrera"];
            $sub_array[] = $fila["estado"];
            $sub_array[] = '<div class="text-center"><button type="button" name="editar" id="' . $fila["codigo_carrera"] . '" class="btn btn-warning btn-xs editar"><i class="bi bi-pencil-fill"></i></button></div>';
            $sub_array[] = '<div class="text-center"><button type="button" name="borrar" id="' . $fila["codigo_carrera"] . '" class="btn btn-danger btn-xs borrar"><i class="bi bi-trash-fill"></i></button></div>';

            $datos[] = $sub_array;
        }

        $salida = array(
            "draw"            => $draw,
            "recordsTotal"    => $filtered_rows,
            "recordsFiltered" => obtener_todos_registros(),
            "data"            => $datos

        );

        echo json_encode($salida);
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function obtener_todos_registros()
{
    include("../conexion.php");
    $stmt = $conexion->prepare("SELECT * FROM carreras");
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    return $stmt->rowCount();
}
