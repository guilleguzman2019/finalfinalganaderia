$(document).ready(function() {
    let codigoEditado = "";
    let pesoEditado = "";

    let filaSeleccionada;

    // Evento al hacer clic en el botón de editar
    $(".editarBtn").click(function() {
        // Guardar la fila seleccionada
        filaSeleccionada = $(this).closest("tr");

        // Obtener valores de la fila
        let codigoEditado = filaSeleccionada.find("td:eq(0)").text();
        let pesoEditado = filaSeleccionada.find("td:eq(1)").text();

        // Asignar valores al modal
        $("#codigo").val(codigoEditado);
        $("#peso").val(pesoEditado);
    });

    // Evento al hacer clic en el botón de guardar cambios
    $(".guardarCambiosBtn").click(function() {
        // Actualizar valores en la fila
        filaSeleccionada.find("td:eq(0)").text($("#codigo").val());
        filaSeleccionada.find("td:eq(1)").text($("#peso").val());

        let codigoNuevo = $("#codigo").val();
        let pesoNuevo = $("#peso").val();

        // Cerrar modal
        $("#editarModal").modal("hide");

        var rowId = filaSeleccionada.attr("id"); 

        let url = "https://graph.microsoft.com/v1.0/me/drive/items/013HAXRPK2J2SLW2HVMRG233RJUFWODTE2/workbook/tables/Table1/rows/itemAt(index=" + rowId + ")";

        $.ajax({
            url: url,
            type: "PATCH",
            headers: {
                "Authorization": "Bearer " + "'.$accessToken.'",
                "Content-Type": "application/json"
            },
            data: JSON.stringify({
                "index": rowId,
                values: [[null, codigoNuevo, null, null, null, null, pesoNuevo, null, null, null, null, null, null, null, null, null, null]] // Asegúrate de enviar solo los campos que quieres actualizar
            }),
            success: function(response) {
                console.log("exitoso", response);
            },
            error: function(error) {
                console.error("error", error);
            }
        });
    });

    // Evento al hacer clic en el botón de eliminar
    $(".eliminarBtn").click(function() {
        $(this).closest("tr").remove();

        let filaSeleccionada = $(this).closest("tr");

        console.log(filaSeleccionada.attr("id"));

        var rowId = filaSeleccionada.attr("id"); 


        // Hacer la solicitud DELETE a la API de Microsoft Graph
        $.ajax({
        url: "https://graph.microsoft.com/v1.0/me/drive/items/013HAXRPK2J2SLW2HVMRG233RJUFWODTE2/workbook/tables/Table1/rows/itemAt(index=" + rowId + ")",
        type: "DELETE",
        headers: {
            "Authorization": "Bearer " + "'.$accessToken.'" // Reemplaza accessToken con tu token
        },
        success: function() {
            // Si la fila se eliminó correctamente, la eliminamos de la tabla
            fila.remove();
            console.log("elimino");
        },
        error: function(error) {
            console.error("Error al eliminar la fila:", error);
        }
        });
    });

    $(".agregarFilaBtn").click(function() {
        // Obtener valores del formulario
        let codigoNuevo = $("#codigoNuevo").val();
        let pesoNuevo = $("#pesoNuevo").val();
    
        // Crear un objeto con los datos para la nueva fila
        let nuevaFila = {
            "values": [
                [null, codigoNuevo, null, null, null, null, pesoNuevo,null, null, null, null, null, null, null, null, null, null]
            ]
        };
    
        // Realizar una solicitud POST para agregar la fila
        $.ajax({
            url: "https://graph.microsoft.com/v1.0/me/drive/items/013HAXRPK2J2SLW2HVMRG233RJUFWODTE2/workbook/tables/Table1/rows/add",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + "'.$accessToken.'", // Reemplaza accessToken con tu token
                "Content-Type": "application/json"
            },
            data: JSON.stringify(nuevaFila),
            success: function(response) {
                // Si la solicitud es exitosa, agrega la nueva fila a la tabla HTML
                let filaHtml = `
                    <tr class="text-center">
                        <td>${codigoNuevo}</td>
                        <td>${pesoNuevo}</td>
                        <td>
                            <button class="btn btn-info editarBtn" data-toggle="modal" data-target="#editarModal">Editar</button>
                            <button class="btn btn-danger eliminarBtn">Eliminar</button>
                        </td>
                    </tr>
                `;
                $("tbody").append(filaHtml);
    
                // Cerrar modal
                $("#agregarModal").modal("hide");
    
                // Limpiar campos del formulario
                $("#codigoNuevo").val("");
                $("#pesoNuevo").val("");

                location.reload();
            },
            error: function(error) {
                // Manejar errores aquí
                console.log(error);
            }
        });
    });

    $("#finca").change(function() {
        let opcionSeleccionada = $(this).val().toLowerCase();
        $("tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(opcionSeleccionada) > -1);
        });
    });

    $("#manada").change(function() {
        let opcionSeleccionada = $(this).val().toLowerCase();
        $("tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(opcionSeleccionada) > -1);
        });
    });

    $("#fecha").change(function() {
        let fechaSeleccionada = $(this).val();

        console.log(fechaSeleccionada);
    
        let partesFecha = fechaSeleccionada.split("T");
        let fecha = partesFecha[0];
    
        let partesFechaFormato = fecha.split("-");
        let fechaFormateada = `${partesFechaFormato[2]}/${partesFechaFormato[1]}/${partesFechaFormato[0]}`;

        console.log(fechaFormateada);
    
        $("tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(fechaFormateada) > -1);
        });
    });

    $("#resetFiltros").click(function() {
        // Limpiar el campo de fecha
        $("#fecha").val("");

        // Mostrar todas las filas
        $("tbody tr").show();

        // Limpiar el filtro del select
        $("#filtro").val("");

        // Limpiar el campo de código
        $("#codigo").val("");

        // Limpiar el campo de peso
        $("#peso").val("");
    });

    $(".newPeso").change(function(){

        var fechaActual = new Date();

        var año = fechaActual.getFullYear();
        var mes = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Sumamos 1 porque los meses van de 0 a 11
        var dia = String(fechaActual.getDate()).padStart(2, '0');

        var fechaFormateada = dia + '/' + mes + '/' + año;

        console.log(fechaFormateada);

        console.log(accessToken);
        let nuevoPeso = $(this).val();
        console.log("El nuevo peso es:", nuevoPeso);

        var rowId = $(this).closest("tr").attr("id");

        var currentElement = $(this);

        let url = "https://graph.microsoft.com/v1.0/me/drive/items/013HAXRPK2J2SLW2HVMRG233RJUFWODTE2/workbook/tables/Table1/rows/itemAt(index=" + rowId + ")";

        $.ajax({
            url: url,
            type: "PATCH",
            headers: {
                "Authorization": "Bearer " + accessToken,
                "Content-Type": "application/json"
            },
            data: JSON.stringify({
                "index": rowId,
                values: [[null, null, null, null, fechaFormateada, null, nuevoPeso, null, null, null, null, null, null, null, null, null, null]] // Asegúrate de enviar solo los campos que quieres actualizar
            }),
            success: function(response) {
                console.log("exitoso", response);
                console.log($(this).closest("tr").find("td:eq(1)").text());
                currentElement.closest("tr").find("td:eq(1)").text(fechaFormateada);
            currentElement.closest("tr").find("td:eq(2)").text(nuevoPeso);
            $(".newPeso").val('');
            },
            error: function(error) {
                console.error("error", error);
            }
        });

        let url2 = 'https://graph.microsoft.com/v1.0/me/drive/items/013HAXRPK2J2SLW2HVMRG233RJUFWODTE2/workbook/worksheets/{31CDE089-39EF-47F1-835B-5211524DC60D}/tables/Table2/rows/add';

        let codigoAnimal = $(this).closest("tr").find("td:eq(0)").text() ;

        $.ajax({
            url: url2,
            type: "POST",
            headers: {
                "Authorization": "Bearer " + accessToken,
                "Content-Type": "application/json"
            },
            data: JSON.stringify({
                values: [[codigoAnimal, fechaFormateada, nuevoPeso]] // Asegúrate de enviar solo los campos que quieres actualizar
            }),
            success: function(response) {
                console.log("exitoso", response);
            },
            error: function(error) {
                console.error("error", error);
            }
        });

      });
    
});
