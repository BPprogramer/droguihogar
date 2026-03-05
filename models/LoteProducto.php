<?php

namespace Model;

class LoteProducto extends ActiveRecord {



    protected static $tabla = 'lotes_productos';

    protected static $columnasDB = [
        'id',
        'producto_id',
        'codigo_lote',
        'fecha_vencimiento',
        'cantidad_ingresada',
        'cantidad_disponible',
        'precio_compra',
        'compra_id',

    ];

    public $id;
    public $producto_id;
    public $codigo_lote;
    public $fecha_vencimiento;

    public $cantidad_ingresada;
    public $cantidad_disponible;

    public $precio_compra;
    public $compra_id;


    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->producto_id = $args['producto_id'] ?? null;
        $this->codigo_lote = $args['codigo_lote'] ?? null;
        $this->fecha_vencimiento = $args['fecha_vencimiento'] ?? null;

        $this->cantidad_ingresada = $args['cantidad_ingresada'] ?? 0;
        $this->cantidad_disponible = $args['cantidad_disponible'] ?? 0;

        $this->precio_compra = $args['precio_compra'] ?? null;
        $this->compra_id = $args['compra_id'] ?? null;

    }

    public function validar()
    {

        if(!$this->producto_id){
            self::$alertas['error'][] = "El producto es obligatorio";
        }

        if($this->cantidad_ingresada <= 0){
            self::$alertas['error'][] = "La cantidad ingresada debe ser mayor a 0";
        }

        if($this->cantidad_disponible < 0){
            self::$alertas['error'][] = "La cantidad disponible no puede ser negativa";
        }

        return self::$alertas;
    }

    public function formatearDatosFloat(){

        if($this->precio_compra){
            $this->precio_compra = floatval(str_replace(',','',$this->precio_compra));
        }

    }
}