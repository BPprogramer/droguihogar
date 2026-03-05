<?php include_once __DIR__ . '/../templates/content-header.php'; ?>

<section class="content" id="productos">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-header">
            <div class="row justify-content-between">

              <div class="col-4">
                <h3 class="card-title">Productos</h3>
              </div>

              <div class="col-4 d-flex justify-content-end">
                <button type="button" id="registrar" class="btn bg-hover-azul text-white toolMio">
                  Registrar Producto
                </button>
              </div>

            </div>
          </div>

          <div class="card-body">

            <table id="tabla" class="display responsive table w-100 table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>CODIGO</th>
                  <th>PRODUCTO</th>
                  <th>DISPONIBLES</th>
                  <th>INVENTARIO MINIMO</th>
                  <th>PRECIO COMPRA</th>
                  <th>PRECIO VENTA</th>
                  <th class="text-center">Acciones</th>
                </tr>
              </thead>
            </table>

          </div>

        </div>

      </div>
    </div>
  </div>
</section>



<div class="modal fade" id="modal-producto">
  <div class="modal-dialog modal-50rem">
    <div class="modal-content">

      <div class="modal-header bg-azul">
        <h4 class="modal-title text-white">Registrar Producto</h4>

        <button type="button" class="close" data-dismiss="modal">
          <span class="text-white">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <form id="productoForm">

          <div class="card-body">

            <div class="row">

              <div class="form-group col-md-6">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control" id="nombre">
              </div>

              <div class="form-group col-md-6">
                <label for="codigo">Código</label>
                <input type="text" name="codigo" class="form-control" id="codigo">
              </div>

            </div>



            <div class="row">

              <div class="form-group col-md-6">
                <label for="categoria_id">Categoria</label>
                <select class="form-control selectCategoria" id="categoria_id"></select>
              </div>

              <div class="form-group col-md-6">
                <label for="proveedor_id">Proveedor</label>
                <select class="form-control selectProveedor" id="proveedor_id"></select>
              </div>

            </div>



            <div class="row">



              <div class="form-group col-md-6">
                <label for="stock_minimo">Stock mínimo</label>
                <input type="number" name="stock_minimo" class="form-control" id="stock_minimo">
              </div>

            </div>



            <!-- NUEVO BLOQUE PARA LOTES -->



            <hr class="bg-azul">



            <div class="row">
              <div class="form-group col-md-6">
                <label for="precio_venta">Precio venta</label>
                <input type="text" name="precio_venta" class="form-control" id="precio_venta">
              </div>
            </div>
          </div>



          <div class="card-footer">

            <div class="row justify-content-between">

              <div class="col-6">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                  Cerrar
                </button>
              </div>

              <div>
                <button type="submit" id="btnSubmit" class="btn bg-hover-azul text-white">
                  Enviar
                </button>
              </div>

            </div>

          </div>

        </form>

      </div>

    </div>
  </div>
</div>