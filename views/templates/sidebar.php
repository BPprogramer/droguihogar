<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <a href="/inicio" class="brand-link">
    <img src="../dist/img/AdminLTELogo.png" class="brand-image img-circle elevation-3" style="opacity:.8">
    <span class="brand-text font-weight-light">DROGUIHOGAR</span>
  </a>

  <div class="sidebar">

    <nav class="mt-2">

      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" data-accordion="false">

        <!-- Inicio -->

        <li class="nav-item">
          <a href="/inicio" class="nav-link <?= pagina_actual('/inicio') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-home"></i>
            <p>Inicio</p>
          </a>
        </li>


        <!-- Ventas -->

        <?php
        $ventas_open = pagina_actual('/crear-venta') ||
          pagina_actual('/ventas') ||
          pagina_actual('/reporte-ventas') ||
          pagina_actual('/productos-ventas');
        ?>

        <li class="nav-item <?= $ventas_open ? 'menu-open' : '' ?>">

          <a href="#" class="nav-link <?= $ventas_open ? 'active' : '' ?>">

            <i class="nav-icon fa-solid fa-cart-shopping"></i>
            <p>
              Ventas
              <i class="fas fa-angle-left right"></i>
            </p>

          </a>

          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="/crear-venta" class="nav-link <?= pagina_actual('/crear-venta') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Crear Venta</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="/ventas" class="nav-link <?= pagina_actual('/ventas') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Administrar Ventas</p>
              </a>
            </li>

            <?php if ($_SESSION['roll'] == 1) { ?>

              <li class="nav-item">
                <a href="/reporte-ventas" class="nav-link <?= pagina_actual('/reporte-ventas') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reporte de Ventas</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="/productos-ventas" class="nav-link <?= pagina_actual('/productos-ventas') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Productos Vendidos</p>
                </a>
              </li>

            <?php } ?>

          </ul>
        </li>


        <!-- Fiados -->

        <?php
        $fiados_open = pagina_actual('/fiados') || pagina_actual('/pagos');
        ?>

        <li class="nav-item <?= $fiados_open ? 'menu-open' : '' ?>">

          <a href="#" class="nav-link <?= $fiados_open ? 'active' : '' ?>">

            <i class="nav-icon fas fa-hand-holding-usd"></i>

            <p>
              Fiados
              <i class="fas fa-angle-left right"></i>
            </p>

          </a>

          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="/fiados" class="nav-link <?= pagina_actual('/fiados') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Fiados</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="/pagos" class="nav-link <?= pagina_actual('/pagos') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Pagos</p>
              </a>
            </li>

          </ul>
        </li>


        <!-- Cajas -->

        <li class="nav-item">
          <a href="/cajas" class="nav-link <?= pagina_actual('/cajas') ? 'active' : '' ?>">
            <i class="nav-icon fa-solid fa-cash-register"></i>
            <p>Cajas</p>
          </a>
        </li>


        <?php if ($_SESSION['roll'] == 1) { ?>

          <li class="nav-item">
            <a href="/productos" class="nav-link <?= pagina_actual('/productos') ? 'active' : '' ?>">
              <i class="nav-icon fa-brands fa-product-hunt"></i>
              <p>Productos</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/compras" class="nav-link <?= pagina_actual('/compras') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Compras</p>
            </a>
          </li>

        <?php } ?>


        <!-- Inventario -->

        <li class="nav-item">
          <a href="/inventario-bajo" class="nav-link <?= pagina_actual('/inventario-bajo') ? 'active' : '' ?>">
            <i class="nav-icon fa-solid fa-bag-shopping"></i>
            <p>Abastecimiento</p>
          </a>
        </li>


        <!-- Transacciones -->

        <?php
        $transacciones_open = pagina_actual('/ingresos') || pagina_actual('/egresos');
        ?>

        <li class="nav-item <?= $transacciones_open ? 'menu-open' : '' ?>">

          <a href="#" class="nav-link <?= $transacciones_open ? 'active' : '' ?>">

            <i class="nav-icon fa-solid fa-exchange-alt"></i>

            <p>
              Transacciones
              <i class="fas fa-angle-left right"></i>
            </p>

          </a>

          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="/ingresos" class="nav-link <?= pagina_actual('/ingresos') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Ingresos</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="/egresos" class="nav-link <?= pagina_actual('/egresos') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Egresos</p>
              </a>
            </li>

          </ul>
        </li>


        <!-- Clientes -->

        <li class="nav-item">
          <a href="/clientes" class="nav-link <?= pagina_actual('/clientes') ? 'active' : '' ?>">
            <i class="nav-icon fa-solid fa-people-arrows"></i>
            <p>Clientes</p>
          </a>
        </li>


        <!-- Proveedores -->

        <li class="nav-item">
          <a href="/proveedores" class="nav-link <?= pagina_actual('/proveedores') ? 'active' : '' ?>">
            <i class="nav-icon fa-solid fa-truck"></i>
            <p>Proveedores</p>
          </a>
        </li>


        <?php if ($_SESSION['roll'] == 1) { ?>

          <li class="nav-item">
            <a href="/usuarios" class="nav-link <?= pagina_actual('/usuarios') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Usuarios</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/categorias" class="nav-link <?= pagina_actual('/categorias') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-th"></i>
              <p>Categorías</p>
            </a>
          </li>

        <?php } ?>


      </ul>

    </nav>

  </div>

</aside>