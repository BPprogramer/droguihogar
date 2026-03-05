(function () {

    const compras = document.querySelector('#compras');

    if (compras) {

        const btnRegistrarCompra = document.querySelector('#registrar');
        const formulario = document.querySelector('#compraForm');
        const selectProveedores = document.querySelector('#proveedor_id');

        const selectProductos = $('#selectProductos');
        const codigoProducto = document.querySelector('#codigo-producto');

        let productos = [];

        let tablaCompras;
        let idProveedor = null;
        let id;

        const fecha = document.querySelector('#fecha');
        const total = document.querySelector('#total');

        const btnSubmit = document.querySelector('#btnSubmit');
        const detalleBody = document.querySelector('#detalle-body');


        mostrarCompras();


        btnRegistrarCompra.addEventListener('click', function () {

            id = null;
            idProveedor = null;

            accionesModal();

        });

        $('#tabla').on('click', '#editar', function (e) {

            id = e.currentTarget.dataset.compraId;

            accionesModal();

        });

        $('#tabla').on('click', '#eliminar', function (e) {

            const id = e.currentTarget.dataset.compraId;

            alertaEliminarCompra(id, e);

        });

        function alertaEliminarCompra(id, e) {

            const totalCompra = e.currentTarget
                .parentElement
                .parentElement
                .parentElement
                .childNodes[3].textContent;

            Swal.fire({
                icon: 'warning',
                html: `<h2>¿Eliminar esta compra por <b>${totalCompra}</b>?</h2><br><p>Esta acción no se puede deshacer</p>`,
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then(result => {

                if (result.isConfirmed) {
                    eliminarCompra(id);
                }

            });

        }

        async function eliminarCompra(id) {

            const datos = new FormData();
            datos.append('id', id);

            const url = `${location.origin}/api/compras/eliminar`;

            try {

                const respuesta = await fetch(url, {
                    body: datos,
                    method: 'POST'
                });

                const resultado = await respuesta.json();

                eliminarToastAnterior();

                if (resultado.type == 'error') {

                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                        body: resultado.msg
                    });

                } else {

                    tablaCompras.ajax.reload();

                    $(document).Toasts('create', {
                        class: 'bg-azul text-blanco',
                        title: 'Completado',
                        body: resultado.msg
                    });

                }

                setTimeout(() => {
                    eliminarToastAnterior();
                }, 4000);

            } catch (error) {
                console.log(error);
            }

        }


        /* =========================
        CARGAR PRODUCTOS
        ========================= */

        async function consultarProductos() {

            const url = `/api/productos/seleccionables`;

            try {

                const respuesta = await fetch(url);
            
                productos = await respuesta.json();
     
                llenarSelectProductos(productos);

            } catch (error) {
                console.log(error)
            }

        }


        function llenarSelectProductos(productos) {

            selectProductos.empty();

            // opción vacía para placeholder
            const opcionVacia = new Option('', '', true, true);
            selectProductos.append(opcionVacia);

            productos.forEach(producto => {

                const opcion = new Option(
                    `${producto.nombre} - ${producto.codigo}`,
                    producto.id,
                    false,
                    false
                );

                selectProductos.append(opcion);

            });

            selectProductos.select2({
                theme: 'bootstrap4',
                placeholder: 'Buscar producto...',
                allowClear: true
            });

        }


        /* =========================
        SELECCIONAR PRODUCTO
        ========================= */

        selectProductos.on('select2:select', function (e) {

            const idProducto = e.params.data.id;

            const producto = productos.find(p => p.id == idProducto);

            if (producto) {
                agregarFilaProducto(producto);
            }

            selectProductos.val(null).trigger('change');

        });


        /* =========================
        CODIGO DE BARRAS
        ========================= */

        codigoProducto.addEventListener('input', function () {

            const codigo = codigoProducto.value.trim();

            if (!codigo) return;

            const producto = productos.find(p => p.codigo == codigo);

            if (producto) {

                agregarFilaProducto(producto);

                codigoProducto.value = ''; // limpia input

            }

        });



        /* =========================
        AGREGAR FILA PRODUCTO
        ========================= */

        function agregarFilaProducto(producto) {

            // verificar si ya existe el producto
            const filas = document.querySelectorAll('#detalle-body tr');

            for (let fila of filas) {

                const productoExistente = fila.querySelector('.producto_id').value;

                if (productoExistente == producto.id) {

                    // si ya existe, solo aumenta la cantidad
                    const inputCantidad = fila.querySelector('.cantidad');
                    inputCantidad.value = parseInt(inputCantidad.value) + 1;

                    calcularTotal();
                    return;
                }

            }

            // si no existe, crear nueva fila
            const tr = document.createElement('tr');

            tr.innerHTML = `
        <td>
            <input type="hidden" class="producto_id" value="${producto.id}">
            <input type="text" class="form-control codigo" value="${producto.codigo}" readonly>
        </td>

        <td>
            <input type="text" class="form-control producto" value="${producto.nombre}" readonly>
        </td>

        <td>
            <input type="number" class="form-control cantidad" value="1">
        </td>

        <td>
            <input type="text" class="form-control precio">
        </td>

        <td>
            <input type="date" class="form-control vencimiento">
        </td>

        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm eliminarFila">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

            detalleBody.appendChild(tr);

        }



        $('#tabla-detalle').on('click', '.eliminarFila', function () {

            $(this).closest('tr').remove();
            calcularTotal();

        });



        $('#tabla-detalle').on('input', '.cantidad, .precio', function () {

            calcularTotal();

        });



        function calcularTotal() {

            let suma = 0;

            const filas = document.querySelectorAll('#detalle-body tr');

            filas.forEach(fila => {

                const cantidad = fila.querySelector('.cantidad').value || 0;
                const precio = fila.querySelector('.precio').value || 0;

                suma += cantidad * precio;

            });

            total.value = suma.toLocaleString('en');

        }



        function mostrarCompras() {

            if ($.fn.DataTable.isDataTable('#tabla')) {
                $('#tabla').DataTable().destroy();
            }

            tablaCompras = $('#tabla').DataTable({

                processing: true,
                serverSide: true,

                ajax: {
                    url: '/api/compras',
                    type: 'GET'
                },

                responsive: true,

                order: [[0, 'desc']],

                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
                    "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron resultados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }

            });

        }



        async function consultarProveedores() {

            const url = `/api/productos-proveedores`;

            try {

                const respuesta = await fetch(url);
                const proveedores = await respuesta.json();

                llenarSelectProveedores(proveedores);

            } catch (error) {

            }

        }



        function llenarSelectProveedores(proveedores) {

            limpiarHtml(selectProveedores);

            proveedores.forEach(proveedor => {

                const opcion = document.createElement('OPTION');

                opcion.value = proveedor.id;
                opcion.textContent = proveedor.nombre;

                if (proveedor.id == idProveedor) {
                    opcion.selected = true;
                }

                selectProveedores.appendChild(opcion);

            });

            $('.selectProveedor').select2();

        }



        async function enviarDatos() {



            const filas = document.querySelectorAll('#detalle-body tr');

            const detalle = [];

            filas.forEach(fila => {

                const producto_id = fila.querySelector('.producto_id').value;
                const cantidad = fila.querySelector('.cantidad').value;
                const precio = fila.querySelector('.precio').value;
                const vencimiento = fila.querySelector('.vencimiento').value;

                detalle.push({
                    producto_id,
                    cantidad,
                    precio,
                    vencimiento
                });

            });


            const datos = new FormData();
            if (id) {
                datos.append('id', id);
            }

            datos.append('proveedor_id', selectProveedores.value);
            datos.append('fecha', fecha.value);
            datos.append('total', total.value.replace(/,/g, ''));
            datos.append('detalle', JSON.stringify(detalle));



            btnSubmit.disabled = true;

            let url = '';

            if (id) {
                url = `${location.origin}/api/compras/editar`;
            } else {
                url = `${location.origin}/api/compras/crear`;
            }

            try {

                const respuesta = await fetch(url, {
                    body: datos,
                    method: 'POST'
                });


                const resultado = await respuesta.json();


                eliminarToastAnterior();

                btnSubmit.disabled = false;

                if (resultado.type == 'error') {

                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                        body: resultado.msg
                    });

                } else {

                    tablaCompras.ajax.reload();

                    $(document).Toasts('create', {
                        class: 'bg-azul text-blanco',
                        title: 'Completado',
                        body: resultado.msg
                    });

                    setTimeout(() => {
                        eliminarToastAnterior();
                    }, 4000);

                    formulario.reset();
                    detalleBody.innerHTML = "";

                    $('#modal-compra').modal('hide');

                }

            } catch (error) {
                console.log(error)
            }

        }



        function eliminarToastAnterior() {

            if (document.querySelector('#toastsContainerTopRight')) {
                document.querySelector('#toastsContainerTopRight').remove();
            }

        }



        async function accionesModal() {

            formulario.reset();
            btnSubmit.disabled = false;

            detalleBody.innerHTML = "";

            $('#modal-compra').modal('show');

            await consultarProveedores();
            await consultarProductos();

            if (id) {
                await consultarCompra();
            }

            inicializarValidador();
        }

        async function consultarCompra() {

            try {

                const respuesta = await fetch(`/api/compras/compra?id=${id}`);
                const resultado = await respuesta.json();

                if (resultado.type === 'error') {

                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                        body: resultado.msg
                    });

                    $('#modal-compra').modal('hide');
                    return;
                }

                llenarFormulario(resultado.compra);

            } catch (error) {
                console.log(error);
            }

        }

        function llenarFormulario(compra) {

            idProveedor = compra.proveedor_id;

            // seleccionar proveedor en select2
            $('#proveedor_id')
                .val(compra.proveedor_id)
                .trigger('change');

            fecha.value = compra.fecha;

            detalleBody.innerHTML = "";

            compra.detalle.forEach(item => {

                const producto = productos.find(p => p.id == item.producto_id);

                if (!producto) return;

                const tr = document.createElement('tr');

                tr.innerHTML = `
        <td>
            <input type="hidden" class="producto_id" value="${producto.id}">
            <input type="text" class="form-control codigo" value="${producto.codigo}" readonly>
        </td>

        <td>
            <input type="text" class="form-control producto" value="${producto.nombre}" readonly>
        </td>

        <td>
            <input type="number" class="form-control cantidad" value="${item.cantidad}">
        </td>

        <td>
            <input type="text" class="form-control precio" value="${item.precio}">
        </td>

        <td>
            <input type="date" class="form-control vencimiento" value="${item.vencimiento ?? ''}">
        </td>

        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm eliminarFila">
                <i class="fas fa-trash"></i>
            </button>
        </td>
        `;

                detalleBody.appendChild(tr);

            });

            calcularTotal();

        }


        function inicializarValidador() {

            $.validator.setDefaults({

                submitHandler: function () {

                    if (!validarPreciosCompra()) return;

                    enviarDatos();

                }
            });


            $('#compraForm').validate({

                rules: {

                    proveedor_id: {
                        required: true
                    },

                    fecha: {
                        required: true
                    }

                },

                messages: {

                    proveedor_id: {
                        required: "El proveedor es obligatorio"
                    },

                    fecha: {
                        required: "La fecha es obligatoria"
                    }

                },

                errorElement: 'span',

                errorPlacement: function (error, element) {

                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);

                },

                highlight: function (element) {

                    $(element).addClass('is-invalid');

                },

                unhighlight: function (element) {

                    $(element).removeClass('is-invalid');

                }

            });

        }

        function validarPreciosCompra() {

            const filas = document.querySelectorAll('#detalle-body tr');

            // validar si no hay productos
            if (filas.length === 0) {

                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Error',
                    body: 'Debe agregar al menos un producto a la compra'
                });

                return false;
            }

            // validar precios
            for (let fila of filas) {

                const precio = parseFloat(fila.querySelector('.precio').value || 0);

                if (!precio || precio <= 0) {

                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                        body: 'Todos los productos deben tener precio de compra mayor a 0'
                    });

                    return false;
                }
            }

            return true;
        }


        function limpiarHtml(referencia) {

            while (referencia.firstChild) {
                referencia.removeChild(referencia.firstChild);
            }

        }

    }

})();