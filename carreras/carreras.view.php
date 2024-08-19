<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UDEV</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <!-- Bootstrap Icons CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../css/style.css">

<body>

  <?php
  include("../componentes/navbar.php");

  ?>

  <h1 class="text-center">CARRERAS</h1>
  <div >

    <div class="row">
      <div class="col-2 offset-10">
        <div class="text-center">
          <!-- Button trigger modal -->
          <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalCarrera" id="botonCrear">

            <i class="bi bi-plus-circle-fill"></i> Crear
          </button>
        </div>
      </div>
    </div>
    <br>
    <div class="tabla-responsive">
      <table id="CarrerasTabla" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Codigo</th>
            <th>Descripcion Carrera</th>
            <th>Valor Total</th>
            <th>Estado</th>
            <th>Editar</th>
            <th>Borrar</th>
          </tr>
        </thead>
      </table>
    </div>
    <div class="modal fade" id="modalCarrera" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Registro de Carrera</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="post" id="formulario" enctype="multipart/form-data">
            <div class="modal-content">
              <div class="modal-body">


                <label for="codigo_carrera">Codigo</label>
                <input type="number" name="codigo_In_servicio" id="codigo_In_servicio" class="form-control">
                <br>

                <label for="descripcion_carrera">Ingrese la descripción</label>
                <input type="text" name="descripcion_servicio" id="descripcion_servicio" class="form-control">
                <br>
                <label for="valor_total_carrera">Ingrese el valor total</label>
                <input type="number" name="valor_total_servicio" id="valor_total_servicio" class="form-control">
                <br>

                <label for="estado">Ingrese el estado</label>
                <select name="estado" id="estado" class="form-control">
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                </select>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="id_servicio" id="id_servicio">
                <input type="hidden" method="POST" name="operacion" id="operacion">

                <!-- Este es un comentario<input type="submit" name="action" id="action" class="btn btn-primary" value="crear"   >
                <i class="bi bi-journal-plus" > </i> -->
                <button type="button" name="cancelar" id="cancelar" class="btn btn-secondary" data-bs-dismiss="modal" > Cancelar
                 
                </button>
                <button type="submit" name="action" id="action" class="btn btn-primary" value="ingresar" >Crear

                  

                </button>

                <?php $operacion = "mostrar" ?>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  </div>
  <div class="footer">
    <?php

    include("../componentes/pie.php");

    ?>

  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $("#botonCrear").click(function() {
        $("#formulario")[0].reset();
        $(".modal-title").text("crear servicio");
        $("#action").val("crear");
        $("#operacion").val("crear");
      });

      var dataTable = $('#CarrerasTabla').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
          url: "carreras.controller.php", //ver registros obtener todos
          type: "POST",
          //data:{action: 'obtener_todos_registros' }

        },
        "columnDefs": [{
            "targets": "_all",
            "className": "text-center"
          },
          {
            "targets": 2, //valor de la columna valor total
            "render": function(data, type, row) {
              return '$' + parseFloat(data).toLocaleString('es-ES', {
                minimumFractionDigits: 2
              });

            },
          }, {
            "targets": [4, 5],
            "orderable": false,

          }
        ]
      });

      $(document).on('submit', '#formulario', function(event) {
        event.preventDefault();
        //var formData= new FormData(this);
        //formData.append('operacion', $("#operacion").val());
        var codigo_In_servicio = $("#codigo_servicio").val();
        var descripcion_servicio = $("#descripcion_servicio").val();
        var valor_total_servicio = $("#valor_total_carrera").val();
        var estado = $("#estado").val();

        if (descripcion_carrera != '' && valor_total_carrera != '' && estado != '') {
          $.ajax({
            url: "carreras.controller.php", //crear
            method: "POST",
            data: new FormData(this),
            //data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
              alert(data);
              $('#formulario')[0].reset();
              $('#modalCarrera').modal('hide');
              //$("#boton").btn('danger');
              dataTable.ajax.reload();
            }
          });
        } else {
          alert("Algunos campos son obligatorios");
        }
      });
      //funcionanlidad editar
      $(document).on('click', '.editar', function() {

        var codigo_carrera = $(this).attr("id");

        $.ajax({
          url: "carreras.controller.php",
          method: "POST",
          data: {
            codigo_carrera: codigo_carrera,
            operacion: 'obtener_registro'
          },
          dataType: "json",
          success: function(data) {

            $('#modalCarrera').modal('show');
            $('#codigo_carrera').val(data.codigo_carrera);
            $('#descripcion_carrera').val(data.descripcion_carrera);
            $('#valor_total_carrera').val(data.valor_total_carrera);
            $('#estado').val(data.estado);

            $('#modal-title').text("Editar estudiante");
            $('#id_carrera').val(codigo_carrera);
            $('#action').val("editar").removeClass('btn-primary').addClass('btn-warnig');
            $('#operacion').val("editar");




          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
          }
        })
      });

      //Funcionalida de borrar
      $(document).on('click', '.borrar', function() {
        var codigo_carrera = $(this).attr("id");
        if (confirm("Esta seguro de borrar este registro:" + codigo_carrera + "?")) {
          $.ajax({
            url: "carreras.controller.php",
            method: "POST",
            data: {
              codigo_carrera: codigo_carrera,
              operacion: 'borrar'
            },
            success: function(data) {
              alert(data);
              dataTable.ajax.reload();
            }
          });
        } else {
          return false;
        }
      });




    });
  </script>





</body>

</html>