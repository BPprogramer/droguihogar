<?php

namespace Controllers;

use Model\LoteProducto;
use Model\Producto;
use Model\Proveedor;

class ApiProductos
{

    public static function productosSeleccionables()
    {

        $productos = Producto::all();
        echo json_encode($productos);
    }

    public static function crear()
    {

        $producto = new Producto($_POST);
        $producto->formatearDatosFloat();
        // if($producto->codigo == ""){
        //     $producto->codigo = null;
        // }
        // $producto->ventas = 0;

        $resultado = $producto->guardar();

        if ($resultado) {
            echo json_encode(['type' => 'success', 'msg' => 'El Producto ha sido registrado exitosamente']);
            return;
        }
        echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
        return;
    }
    public static function editar()
    {
        if (!is_auth()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }
        $producto = Producto::find($_POST['id']);
        if (!$producto) {
            echo json_encode(['type' => 'error', 'msg' => 'Hay un Problema con el Producto']);
            return;
        }
        $producto->sincronizar($_POST);

        $producto->formatearDatosFloat();



        $resultado = $producto->guardar();

        if ($resultado) {
            echo json_encode(['type' => 'success', 'msg' => 'El Producto ha sido actualizado exitosamente']);
            return;
        }
        echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
        return;
    }
    public static function editarStock()
    {
        if (!is_auth()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }

        $producto = Producto::find($_POST['id']);
        if (!$producto) {
            echo json_encode(['type' => 'error', 'msg' => 'Hay un Problema con el Producto']);
            return;
        }

        $stock_actual = $producto->stock;
        $precio_compra_actual = $producto->precio_compra;
        $stock_adquirido = $_POST['stock'];
        $precio_compra_adquirido =  floatval(str_replace(',', '', $_POST['precio_compra']));

        $stock = $stock_actual + $stock_adquirido;
        $precio_compra = ($stock_actual * $precio_compra_actual + $stock_adquirido * $precio_compra_adquirido) / $stock;
        $producto->stock = $stock;
        $producto->precio_compra = $precio_compra;
        $resultado = $producto->guardar();

        if ($resultado) {
            echo json_encode(['type' => 'success', 'msg' => 'El Producto ha sido actualizado exitosamente']);
            return;
        }
        echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
        return;
    }
    public static function eliminar()
    {
        if (!is_auth()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }
        $id = $_POST['id'];
        if (!$id) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error Intenta Nuevamente']);
            return;
        }
        $producto = Producto::find($_POST['id']);
        if (!$producto) {
            echo json_encode(['type' => 'error', 'msg' => 'Hay un Problema con el producto']);
            return;
        }
        $resultado = $producto->eliminar();
        if ($resultado['status']) {
            echo json_encode(['type' => 'success', 'msg' => 'El producto ha sido Eliminado con Exito']);
            return;
        }
        echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
        return;
    }

    public static function productos()
    {
        $start = $_GET['start'] ?? 0;
        $length = $_GET['length'] ?? 10;
        $search = $_GET['search']['value'] ?? '';
        $orderColumnIndex = $_GET['order'][0]['column'] ?? 0;
        $orderDir = $_GET['order'][0]['dir'] ?? 'asc';

        $columnas = ['codigo', 'nombre', 'stock', 'precio_compra', 'precio_venta'];
        $orderColumn = $columnas[$orderColumnIndex - 1] ?? 'id';

        $productos = Producto::all();



        if ($search !== '') {
            $productos = array_filter($productos, function ($producto) use ($search) {
                return stripos($producto->nombre, $search) !== false ||
                    stripos($producto->codigo, $search) !== false;
            });
        }

        $totalRegistros = count($productos);
        $productos = array_slice($productos, $start, $length);

        $data = [];

        foreach ($productos as $key => $producto) {

            // traer lotes del producto
            $lotes = LoteProducto::whereArray([
                'producto_id' => $producto->id
            ]);

            $stock_total = 0;
            $precio_total = 0;
            $cantidad_lotes = 0;

            foreach ($lotes as $lote) {

                $stock_total += $lote->cantidad_disponible;
                $precio_total += $lote->precio_compra;
                $cantidad_lotes++;
            }

            // promedio precio compra
            $precio_compra = $cantidad_lotes > 0
                ? $precio_total / $cantidad_lotes
                : 0;

            // Acciones
            $acciones = "<div class='d-flex justify-content-center'>";
            $acciones .= "<button data-producto-id='{$producto->id}' id='editar' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio'><span class='toolMio-text'>Editar</span><i class='fas fa-pen'></i></button>";
            $acciones .= "<button data-producto-id='{$producto->id}' id='eliminar' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio'><span class='toolMio-text'>Eliminar</span><i class='fas fa-trash'></i></button>";
            $acciones .= "</div>";

            // Stock visual
            $stock = "<div class='d-flex justify-content-center'>";

            $clase = $stock_total <= $producto->stock_minimo
                ? 'btn-danger'
                : 'bg-success text-white';

            $stock .= "<button class='btn w-65 btn-inline {$clase} btn-sm' style='min-width:70px'>{$stock_total}</button>";
            $stock .= "</div>";

            $codigo = $producto->codigo ?: '';

            $data[] = [
                $start + $key + 1,
                $codigo,
                $producto->nombre,
                $stock,
                $producto->stock_minimo,
                number_format($precio_compra),
                number_format($producto->precio_venta),
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


    public static function consultarProducto()
    {
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            echo json_encode([
                'type' => 'error',
                'msg' => 'Hubo un error, Intenta Nuevamente'
            ]);
            return;
        }

        $producto = Producto::find($id);

        if (!$producto) {
            echo json_encode([
                'type' => 'error',
                'msg' => 'Producto no encontrado'
            ]);
            return;
        }

        // traer lotes del producto
        $lotes = LoteProducto::whereArray([
            'producto_id' => $id
        ]);

        $stock = 0;
        // $precio_total = 0;

        foreach ($lotes as $lote) {

            if ($lote->cantidad_disponible <= 0) {
                continue;
            }

            $stock += $lote->cantidad_disponible;

            // $precio_total += $lote->precio_compra * $lote->cantidad_disponible;
        }

        // promedio ponderado
        // $precio_compra = $stock > 0
        //     ? $precio_total / $stock
        //     : 0;

        echo json_encode([
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'precio_venta' => $producto->precio_venta,
            'stock_minimo' => $producto->stock_minimo,
            // 'precio_compra' => $precio_compra,
            'stock' => $stock
        ]);
    }
    public static function inventarioBajo()
    {
        $productos_todos = Producto::all();

      

        $data = [];

        foreach ($productos_todos as $producto) {

            // calcular stock desde los lotes
            $lotes = LoteProducto::whereArray([
                'producto_id' => $producto->id
            ]);

            $stock = 0;
            $precio_total = 0;

            foreach ($lotes as $lote) {

                if ($lote->cantidad_disponible <= 0) continue;

                $stock += $lote->cantidad_disponible;

                // promedio ponderado del costo
                $precio_total += $lote->precio_compra * $lote->cantidad_disponible;
            }

            $precio_compra = $stock > 0
                ? $precio_total / $stock
                : 0;

            $stock_minimo = abs($producto->stock_minimo);

            // filtrar inventario bajo
            if ($stock > $stock_minimo) {
                continue;
            }

            $proveedor = Proveedor::find($producto->proveedor_id);

            $stock_html = "<div class='d-flex justify-content-center'>";
            $stock_html .= "<button data-producto-id='{$producto->id}' id='agregar_stock' type='button' class='btn w-65 btn-inline btn-danger btn-sm' style='min-width:70px'>{$stock}</button>";
            $stock_html .= "</div>";

            $data[] = [
                count($data) + 1,
                $producto->nombre,
                $stock_html,
                $producto->stock_minimo,
                $proveedor->nombre ?? '',
                $proveedor->celular ?? ''
            ];
        }

        echo json_encode([
            "data" => $data
        ], JSON_UNESCAPED_SLASHES);
    }
}
