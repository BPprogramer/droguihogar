<?php include_once __DIR__ . '/../templates/content-header.php'; ?>



<!-- Main content -->
<section class="content" id="inventario-bajo">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <!-- /.card -->

        <div class="card">
          <div class="card-header">
            <div class="row justify-content-between">
              <div class="col-4">
                <h3 class="card-title">Avastesimiento</h3>
              </div>
             
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="tabla" class="display responsive table w-100 table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>PRODUCTO</th>
                  <th>INVENTARIO ACTUAL</th>
                  <th>INVENTARIO MINIMO</th>
             
                  <th>PROVEEDOR</th>
                  <th>TELEFONO</th>
                </tr>
              </thead>

            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>



<!-- Modal para actualizar stock -->

<div class="modal fade" id="modal-stock">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-azul">
        <h4 class="modal-title text-white">Actualizar Stock</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="stockForm">

          <div class="card-body">
         
              <div class="form-group">
                <label for="nombre_producto">Nombre del Producto</label>
                <input type="text" name="nombre_producto" class="form-control" readonly id="nombre_producto">
              </div>
              
    
         
          
              <div class="form-group">
                <label for="nuevo_stock">Cantidad Adquirida</label>
                <input type="number" name="nuevo_stock" class="form-control" id="nuevo_stock" placeholder="Digite la cantidad adquirida">
              </div>
           
          
            <hr class="bg-azul">
            <div class="row">
              <div class="form-group col-md-6">
                <label for="precio_paquete_nuevo">Precio paquete</label>
                <input type="text" name="precio_paquete_nuevo" class="form-control" id="precio_paquete_nuevo" placeholder="Precio del paquete completo">
              </div>
              <div class="form-group col-md-6">
                <label for="unidades_nuevo"> Unidades por paquete</label>
                <input type="number" name="unidades_nuevo" class="form-control" id="unidades_nuevo" placeholder="Unidades por paquete">
              </div>
            </div>
            <hr class="bg-azul">
            <div class="row">
              <div class="form-group col-md-6">
                <label for="precio_compra_nuevo">Precio de compra</label>
                <input type="text" name="precio_compra_nuevo" class="form-control" id="precio_compra_nuevo" placeholder="Precio de Compra">
              </div>
            
            </div>
           
          </div>



          <div class="card-footer">
            <div class="row justify-content-between">
              <div class="col-6">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>
              <div class="">
                <button type="submit" class="btn bg-hover-azul text-white" id="btnSubmitNewStock">Actualizar</button>
              </div>
            </div>
          </div>

        </form>
      </div>

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>