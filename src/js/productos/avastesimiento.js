(function(){
    const inventario_bajo = document.querySelector('#inventario-bajo');
    if(inventario_bajo){
        let tablaProductosVendidos;
        const formularioStock = document.querySelector('#stockForm');
        const nombre_producto = document.querySelector('#nombre_producto');
        const nuevo_stock = document.querySelector('#nuevo_stock');
        const precio_paquete_nuevo = document.querySelector('#precio_paquete_nuevo');
        const unidades_input_nuevo = document.querySelector('#unidades_nuevo');
        const precio_compra_nuevo = document.querySelector('#precio_compra_nuevo');
        const btnSubmitNewStock = document.querySelector('#btnSubmitNewStock');
        consultarProductos();

        $('#tabla').on('click', '#agregar_stock', function(e){
           
            idProductoActualizarStock = e.currentTarget.dataset.productoId;
            formularioStock.reset();
            btnSubmitNewStock.disabled = false;
            $('#modal-stock').modal('show');
            $('#stockForm').validate().resetForm();
            // Elimina todas las reglas de validación
            $('#stockForm').validate().destroy();
            // Elimina las clases de validación de los elementos
            $('#stockForm').find('.is-invalid').removeClass('is-invalid');
  
 
            const nombre = e.currentTarget.parentElement.parentElement.parentElement.childNodes[1].textContent;
            nombre_producto.value = nombre;
            inicializarValidadorStock();
        })

        precio_paquete_nuevo.addEventListener('input',function(e){
            const precio_paquete = formatearValor(e.target.value);
            precio_paquete_nuevo.value = precio_paquete;
            calcularNuevoPrecioCompra();
        })
        unidades_input_nuevo.addEventListener('input',function(e){
            
            calcularNuevoPrecioCompra();
        })

        precio_compra_nuevo.addEventListener('input',function(e){
            const precio_compra = formatearValor(e.target.value);
            precio_compra_nuevo.value = precio_compra;
        
        })



       
        function consultarProductos(){
 
            $("#tabla").dataTable().fnDestroy(); //por si me da error de reinicializar
    
            tablaCompras = $('#tabla').DataTable({
                ajax: '/api/productos/inventario-bajo',
                "deferRender":true,
                "retrieve":true,
                "proccesing":true,
                responsive:true,
                initComplete: function () {
                    // Inicializa los botones después de que la tabla se haya creado
                    var buttons = new $.fn.dataTable.Buttons(tablaCompras, {
                        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).container().appendTo('#tabla_wrapper .col-md-6:eq(0)');
                }
            });
            
       
       
        }
        function calcularNuevoPrecioCompra(){
            precio_compra_nuevo.value = '';
    
            const precio_paquete = parseFloat((precio_paquete_nuevo.value).replace(/,/g, ''));
            const unidades = parseFloat(unidades_input_nuevo.value);
            if(isNaN(precio_paquete) || isNaN(unidades)){
                return;
            }
            const precio_compra = precio_paquete/unidades;
            precio_compra_nuevo.value = (Math.round(precio_compra)).toLocaleString('en')
        }

        function formatearValor(valor){
           
            let valor_sin_formato = parseFloat(valor.replace(/,/g, ''));
            if(isNaN(valor_sin_formato)){
                valor_sin_formato = '';
            }
            const valor_formateado =  valor_sin_formato.toLocaleString('en');
            return valor_formateado ;
        }
        async function enviarDatosStock(){
            const datos = new FormData();
     
            datos.append('id', idProductoActualizarStock)
            datos.append('stock', (nuevo_stock.value));
            datos.append('precio_compra', (precio_compra_nuevo.value));

           btnSubmitNewStock.disabled = true;

            const url = `${location.origin}/api/producto/editar-stock`;
        
            
            
            try {
                const respuesta = await fetch(url,{
                    body:datos,
                    method: 'POST'
                })
                const resultado = await respuesta.json();
          
          
                eliminarToastAnterior();
         
                btnSubmitNewStock.disabled = false;
              
                if(resultado.type=='error'){
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                     
                        body: resultado.msg
                      })
                }else{
                   
                    $("#tabla").DataTable().ajax.reload();
               
                    $(document).Toasts('create', {
                        class: 'bg-azul text-blanco',
                        title: 'Completado',
                        
                        body: resultado.msg
                    })

                    setTimeout(()=>{
                        eliminarToastAnterior();
                    },4000)

                    formularioStock.reset();

                    $('#modal-stock').modal('hide');
         
                }
   
            } catch (error) {  
            }
        }
        
        function inicializarValidadorStock(id) {
            $.validator.setDefaults({
              submitHandler: function () {
                enviarDatosStock()
         
              }
            });
      
             $('#stockForm').validate({
              rules: {
                
               
                nuevo_stock: {
                    required: true
                },
               
                precio_compra_nuevo:{
                    required: true
                }
              },
              messages: {
                nuevo_stock: {
                    required: "El nombre es obligatorio"  
                },
                precio_compra_nuevo: {
                    required: 'la nueva cantidad adquirida es obligatoria'
                }

              },
              errorElement: 'span',
              errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
              },
              highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
              },
              unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
              }
            });
          };
          $('#stockForm').on('valid', function(event) {
            inicializarValidadorStock();
        });
        function eliminarToastAnterior(){
            if(document.querySelector('#toastsContainerTopRight')){
                document.querySelector('#toastsContainerTopRight').remove()
            }
        }
    }
})();