<?php

namespace Controllers;

use Model\Compra;
use Model\LoteProducto;
use Model\Producto;
use Model\Proveedor;

class ApiCompras
{


    public static function crear()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $compra = new Compra([
                'proveedor_id' => $_POST['proveedor_id'],
                'fecha' => $_POST['fecha'],
                'total' => $_POST['total']
            ]);

            $resultado = $compra->guardar();

            if (!$resultado['resultado']) {
                echo json_encode([
                    'type' => 'error',
                    'msg' => 'Error al registrar la compra'
                ]);
                return;
            }

            // id de la compra recién creada
            $compra_id = $resultado['id'];

            $detalle = json_decode($_POST['detalle'], true);

            foreach ($detalle as $item) {

                $producto_id = $item['producto_id'];
                $cantidad = $item['cantidad'];
                $precio = $item['precio'];
                $vencimiento = $item['vencimiento'];

                $lote = new LoteProducto();

                $lote->compra_id = $compra_id;
                $lote->producto_id = $producto_id;
                $lote->codigo_lote = uniqid();


                // si no tiene vencimiento se guarda NULL
                $lote->fecha_vencimiento = $vencimiento ? $vencimiento : null;

                $lote->precio_compra = $precio;

                $lote->cantidad_ingresada = $cantidad;
                $lote->cantidad_disponible = $cantidad;

                $lote->guardar();
            }

            echo json_encode([
                'type' => 'success',
                'msg' => 'Compra registrada correctamente'
            ]);
        }
    }
    public static function editar()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'] ?? null;

            $compra = Compra::find($id);

            if (!$compra) {
                echo json_encode([
                    'type' => 'error',
                    'msg' => 'La compra no existe'
                ]);
                return;
            }

            // obtener lotes de la compra
            $lotes = LoteProducto::whereAll('compra_id', $id);


            // echo json_encode([
            //     'info' =>  $lotes,

            // ]);

            // return;

            // verificar si algún lote ya fue usado
            foreach ($lotes as $lote) {

                if ($lote->cantidad_ingresada != $lote->cantidad_disponible) {

                    echo json_encode([
                        'type' => 'error',
                        'msg' => 'No se puede editar la compra porque ya se vendieron productos de este lote'
                    ]);
                    return;
                }
            }

            // actualizar compra
            $compra->proveedor_id = $_POST['proveedor_id'];
            $compra->fecha = $_POST['fecha'];
            $compra->total = $_POST['total'];

            $compra->guardar();

            // eliminar lotes antiguos
            foreach ($lotes as $lote) {
                $lote->eliminar();
            }

            // crear nuevos lotes
            $detalle = json_decode($_POST['detalle'], true);

            foreach ($detalle as $item) {

                $lote = new LoteProducto();

                $lote->compra_id = $id;
                $lote->producto_id = $item['producto_id'];
                $lote->codigo_lote = uniqid();

                $lote->fecha_vencimiento = $item['vencimiento'] ? $item['vencimiento'] : null;

                $lote->precio_compra = $item['precio'];

                $lote->cantidad_ingresada = $item['cantidad'];
                $lote->cantidad_disponible = $item['cantidad'];

                $lote->guardar();
            }

            echo json_encode([
                'type' => 'success',
                'msg' => 'Compra actualizada correctamente'
            ]);
        }
    }

    public static function eliminar()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'] ?? null;

            if (!$id) {
                echo json_encode([
                    'type' => 'error',
                    'msg' => 'Compra inválida'
                ]);
                return;
            }

            $compra = Compra::find($id);

            if (!$compra) {
                echo json_encode([
                    'type' => 'error',
                    'msg' => 'La compra no existe'
                ]);
                return;
            }

            // traer los lotes de la compra
            $lotes = LoteProducto::whereAll('compra_id', $id);

            // verificar si algún lote ya fue usado
            foreach ($lotes as $lote) {

                if ($lote->cantidad_ingresada != $lote->cantidad_disponible) {

                    echo json_encode([
                        'type' => 'error',
                        'msg' => 'No se puede eliminar la compra porque ya se vendieron productos de este lote'
                    ]);
                    return;
                }
            }

            // eliminar lotes
            foreach ($lotes as $lote) {
                $lote->eliminar();
            }

            // eliminar compra
            $resultado = $compra->eliminar();

            if ($resultado['status']) {

                echo json_encode([
                    'type' => 'success',
                    'msg' => 'Compra eliminada correctamente'
                ]);
                return;
            }

            echo json_encode([
                'type' => 'error',
                'msg' => 'Hubo un error al eliminar la compra'
            ]);
        }
    }

    public static function compras()
    {
        $start = $_GET['start'] ?? 0;
        $length = $_GET['length'] ?? 10;
        $search = $_GET['search']['value'] ?? '';
        $orderColumnIndex = $_GET['order'][0]['column'] ?? 0;
        $orderDir = $_GET['order'][0]['dir'] ?? 'asc';

        // Columnas permitidas para ordenar
        $columnas = ['proveedor_id', 'fecha', 'total'];
        $orderColumn = $columnas[$orderColumnIndex - 1] ?? 'id';

        // Obtener compras
        $compras = Compra::all();

        // Filtrar búsqueda
        if ($search !== '') {
            $compras = array_filter($compras, function ($compra) use ($search) {

                $proveedor = Proveedor::find($compra->proveedor_id);

                return stripos($proveedor->nombre, $search) !== false ||
                    stripos($compra->fecha, $search) !== false;
            });
        }

        // Ordenar
        usort($compras, function ($a, $b) use ($orderColumn, $orderDir) {

            $valorA = strtolower($a->{$orderColumn});
            $valorB = strtolower($b->{$orderColumn});

            return $orderDir === 'asc' ? $valorA <=> $valorB : $valorB <=> $valorA;
        });

        $totalRegistros = count($compras);
        $compras = array_slice($compras, $start, $length);

        $data = [];

        foreach ($compras as $key => $compra) {

            $proveedor = Proveedor::find($compra->proveedor_id);

            $acciones = "<div class='d-flex justify-content-center'>";
            $acciones .= "<button data-compra-id='{$compra->id}' id='editar' class='btn btn-sm bg-hover-azul mx-2 text-white'><i class='fas fa-pen'></i></button>";
            $acciones .= "<button data-compra-id='{$compra->id}' id='eliminar' class='btn btn-sm bg-hover-azul mx-2 text-white'><i class='fas fa-trash'></i></button>";
            $acciones .= "</div>";

            $data[] = [
                $start + $key + 1,
                $proveedor ? $proveedor->nombre : '',
                $compra->fecha,
                number_format($compra->total),
                $acciones
            ];
        }

        echo json_encode([
            "draw" => intval($_GET['draw']),
            "recordsTotal" => $totalRegistros,
            "recordsFiltered" => $totalRegistros,
            "data" => $data
        ], JSON_UNESCAPED_UNICODE);
    }


    public static function compra()
    {
        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            echo json_encode([
                'type' => 'error',
                'msg' => 'Id inválido'
            ]);
            return;
        }

        $compra = Compra::find($id);

        if (!$compra) {
            echo json_encode([
                'type' => 'error',
                'msg' => 'La compra no existe'
            ]);
            return;
        }

        // traer todos los lotes de la compra
        $lotes = LoteProducto::whereAll('compra_id', $id);

        // verificar si ya se usó algún lote
        foreach ($lotes as $lote) {

            if ($lote->cantidad_ingresada != $lote->cantidad_disponible) {

                echo json_encode([
                    'type' => 'error',
                    'msg' => 'No es posible editar esta compra porque algunos productos ya fueron utilizados'
                ]);
                return;
            }
        }

        // si todo está disponible se arma el detalle
        $detalle = [];

        foreach ($lotes as $lote) {

            $detalle[] = [
                'producto_id' => $lote->producto_id,
                'cantidad' => $lote->cantidad_ingresada,
                'precio' => $lote->precio_compra,
                'vencimiento' => $lote->fecha_vencimiento
            ];
        }

        echo json_encode([
            'type' => 'success',
            'compra' => [
                'id' => $compra->id,
                'proveedor_id' => $compra->proveedor_id,
                'fecha' => $compra->fecha,
                'total' => $compra->total,
                'detalle' => $detalle
            ]
        ]);
    }
    public static function avastecimiento()
    {
        // Obtener todos los productos
        $productos_todos = Producto::all();

        // Filtrar productos con stock menor o igual al stock mínimo (usando valor absoluto)
        $productos = array_filter($productos_todos, function ($producto) {
            $stock_minimo = abs($producto->stock_minimo); // Asegurar que el stock mínimo sea positivo
            return $producto->stock <= $stock_minimo; // Retornar productos que cumplan la condición
        });

        // Array para almacenar los datos de los productos filtrados
        $data = [];

        // Recorrer los productos filtrados
        foreach ($productos as $producto) {
            // Obtener el proveedor asociado al producto
            $proveedor = Proveedor::find($producto->proveedor_id);

            // Generar el HTML para el stock (botón de agregar stock)
            $stock = "<div class='d-flex justify-content-center'>";
            $stock .= "<button data-producto-id='" . $producto->id . "' id='agregar_stock' type='button' class='btn w-65 btn-inline btn-danger btn-sm' style='min-width:70px'>" . $producto->stock . "</button>";
            $stock .= "</div>";

            // Agregar los datos del producto al array
            $data[] = [
                count($data) + 1, // Índice (empezando desde 1)
                $producto->nombre, // Nombre del producto
                $stock, // Stock (HTML)
                $producto->stock_minimo, // Stock mínimo
                number_format($producto->precio_compra), // Precio de compra formateado
                $proveedor->nombre, // Nombre del proveedor
                $proveedor->celular // Celular del proveedor
            ];
        }

        // Generar el JSON final
        $datoJson = json_encode(["data" => $data], JSON_UNESCAPED_SLASHES);

        // Imprimir el JSON
        echo $datoJson;
    }
}

// array(2) {
//     [0]=>
//     object(Model\Producto)#28 (11) {
//       ["id"]=>
//       string(2) "20"
//       ["nombre"]=>
//       string(8) "BUCHANAS"
//       ["codigo"]=>
//       string(6) "522001"
//       ["stock"]=>
//       string(1) "0"
//       ["stock_minimo"]=>
//       string(2) "20"
//       ["precio_compra"]=>
//       string(9) "120000.00"
//       ["precio_venta"]=>
//       string(9) "170000.00"
//       ["porcentaje_venta"]=>
//       string(6) "141.67"
//       ["ventas"]=>
//       string(2) "30"
//       ["categoria_id"]=>
//       string(2) "32"
//       ["proveedor_id"]=>
//       string(1) "7"
//     }
//     [2]=>
//     object(Model\Producto)#21 (11) {
//       ["id"]=>
//       string(2) "14"
//       ["nombre"]=>
//       string(9) "WINDERMAN"
//       ["codigo"]=>
//       string(7) "2503350"
//       ["stock"]=>
//       string(1) "6"
//       ["stock_minimo"]=>
//       string(2) "20"
//       ["precio_compra"]=>
//       string(8) "12000.00"
//       ["precio_venta"]=>
//       string(8) "15000.00"
//       ["porcentaje_venta"]=>
//       string(6) "125.00"
//       ["ventas"]=>
//       string(2) "24"
//       ["categoria_id"]=>
//       string(2) "29"
//       ["proveedor_id"]=>
//       string(1) "9"
//     }
//   }
