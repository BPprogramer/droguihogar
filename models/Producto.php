<?php

namespace Model;

class  Producto extends ActiveRecord {
    protected static $tabla = 'productos';
    protected static $columnasDB = ['id', 'nombre','codigo','stock_minimo',  'precio_venta',  'categoria_id', 'proveedor_id'];

    public $id;
    public $nombre;
    public $codigo;

    public $stock_minimo;

    public $precio_venta;


    public $categoria_id;
    public $proveedor_id;

 
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->codigo = $args['codigo'] ?? null;

        $this->stock_minimo = $args['stock_minimo'] ?? 0;
        $this->precio_venta = $args['precio_venta'] ?? '';


        $this->categoria_id = $args['categoria_id'] ?? null;
        $this->proveedor_id = $args['proveedor_id'] ?? null;

      
    }
    public function formatearDatosFloat(){
      
        $this->precio_venta = floatval(str_replace(',','',$this->precio_venta));
     
     
    }


}