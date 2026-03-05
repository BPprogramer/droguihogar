<?php

namespace Model;

class ProductoVentaLote extends ActiveRecord {

    protected static $tabla = 'productos_venta_lotes';
    protected static $columnasDB = [
        'id',
        'producto_venta_id',
        'lote_producto_id',
        'cantidad'
    ];

    public $id;
    public $producto_venta_id;
    public $lote_producto_id;
    public $cantidad;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->producto_venta_id = $args['producto_venta_id'] ?? null;
        $this->lote_producto_id = $args['lote_producto_id'] ?? null;
        $this->cantidad = $args['cantidad'] ?? 0;
    }

    public function validar()
    {

        if(!$this->producto_venta_id){
            self::$alertas['error'][] = "La venta del producto es obligatoria";
        }

        if(!$this->lote_producto_id){
            self::$alertas['error'][] = "El lote del producto es obligatorio";
        }

        if($this->cantidad <= 0){
            self::$alertas['error'][] = "La cantidad debe ser mayor a 0";
        }

        return self::$alertas;
    }

}