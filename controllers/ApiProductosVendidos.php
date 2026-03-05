<?php

namespace Controllers;

use Model\Venta;

class ApiProductosVendidos
{
    public static function productosVendidos()
    {

        $fecha_inicial = $_GET['fecha-inicial'];
        $fecha_final = $_GET['fecha-final'];

        $db = Venta::getDB();

        $query = "
            SELECT 
                p.id,
                p.codigo,
                p.nombre,

                pv.cantidad,

                /* costo real desde lotes */
                COALESCE(SUM(pvl.cantidad * lp.precio_compra),0) AS total_compra,

                /* costo unitario */
                COALESCE(
                    SUM(pvl.cantidad * lp.precio_compra) / pv.cantidad,
                0) AS precio_compra,

                pv.precio_factura AS precio_venta,

                pv.precio_factura * pv.cantidad AS total_venta

            FROM productos_venta pv

            JOIN ventas v
                ON v.id = pv.venta_id

            JOIN productos p
                ON p.id = pv.producto_id

            LEFT JOIN productos_venta_lotes pvl
                ON pvl.producto_venta_id = pv.id

            LEFT JOIN lotes_productos lp
                ON lp.id = pvl.lote_producto_id

            WHERE DATE(v.fecha) BETWEEN '{$fecha_inicial}' AND '{$fecha_final}'

            GROUP BY pv.id
        ";

        $result = $db->query($query);

        $data = [];
        $i = 1;

        while ($row = $result->fetch_assoc()) {

            $data[] = [
                $i++,
                $row['id'],
                $row['codigo'],
                $row['nombre'],

                $row['cantidad'],

                '$' . number_format($row['precio_compra']),
                '$' . number_format($row['total_compra']),

                '$' . number_format($row['precio_venta']),
                '$' . number_format($row['total_venta']),
            ];
        }

        echo json_encode([
            "data" => $data
        ], JSON_UNESCAPED_SLASHES);
    }
}