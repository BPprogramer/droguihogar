<?php

namespace Model;

class Compra extends ActiveRecord {

    protected static $tabla = 'compras';

    protected static $columnasDB = [
        'id',
        'proveedor_id',
        'fecha',
        'total'
    ];

    public $id;
    public $proveedor_id;
    public $fecha;
    public $total;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->proveedor_id = $args['proveedor_id'] ?? null;
        $this->fecha = $args['fecha'] ?? date('Y-m-d');
        $this->total = $args['total'] ?? 0;
    }

    public function validar()
    {
        if(!$this->proveedor_id){
            self::$alertas['error'][] = "El proveedor es obligatorio";
        }

        if(!$this->fecha){
            self::$alertas['error'][] = "La fecha de la compra es obligatoria";
        }

        return self::$alertas;
    }
}