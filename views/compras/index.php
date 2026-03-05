<?php include_once __DIR__ . '/../templates/content-header.php'; ?>

<section class="content" id="compras">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <div class="card">

          <div class="card-header">
            <div class="row justify-content-between">

              <div class="col-4">
                <h3 class="card-title">Compras</h3>
              </div>

              <div class="col-4 d-flex justify-content-end">
                <button type="button" id="registrar" class="btn bg-hover-azul text-white toolMio">
                  Registrar Compra
                </button>
              </div>

            </div>
          </div>

          <div class="card-body">

            <table id="tabla" class="display responsive table w-100 table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>PROVEEDOR</th>
                  <th>FECHA</th>
                  <th>TOTAL</th>
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



<div class="modal fade" id="modal-compra">
  <div class="modal-dialog modal-50rem">
    <div class="modal-content">

      <div class="modal-header bg-azul">
        <h4 class="modal-title text-white">Registrar Compra</h4>

        <button type="button" class="close" data-dismiss="modal">
          <span class="text-white">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <form id="compraForm">

          <div class="card-body">

            <!-- proveedor y fecha -->

            <div class="row">

              <div class="form-group col-md-6">
                <label for="proveedor_id">Proveedor</label>
                <select class="form-control selectProveedor" id="proveedor_id"></select>
              </div>

              <div class="form-group col-md-6">
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" class="form-control" id="fecha">
              </div>

            </div>


            <hr class="bg-azul">


            <!-- BUSCADOR PRODUCTO -->

            <div class="row">

              <div class="form-group col-md-8">
                <label>Producto</label>
                <select class="form-control select2bs4" id="selectProductos"></select>
              </div>

              <div class="form-group col-md-4">
                <label>Código Barras</label>
                <input type="text" id="codigo-producto" class="form-control">
              </div>

            </div>


            <hr class="bg-azul">


            <!-- TABLA DETALLE -->

            <div class="row">
              <div class="col-12">

                <table class="table table-bordered" id="tabla-detalle">

                  <thead>
                    <tr>
                      <th style="width:120px">CODIGO</th>
                      <th>PRODUCTO</th>
                      <th style="width:120px">CANTIDAD</th>
                      <th style="width:150px">PRECIO COMPRA</th>
                      <th style="width:170px">VENCIMIENTO</th>
                      <th style="width:80px"></th>
                    </tr>
                  </thead>

                  <tbody id="detalle-body">

                  </tbody>

                </table>

              </div>
            </div>


            <hr class="bg-azul">


            <!-- TOTAL -->

            <div class="row justify-content-end">

              <div class="form-group col-md-4">
                <label>Total Compra</label>
                <input type="text" name="total" class="form-control" id="total" readonly>
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
                  Guardar Compra
                </button>
              </div>

            </div>

          </div>

        </form>

      </div>

    </div>
  </div>
</div>