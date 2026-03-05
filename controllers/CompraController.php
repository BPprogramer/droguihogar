<?php

namespace Controllers;


use MVC\Router;

class CompraController {

    public static function index(Router $router){
        
        if(!is_auth() || $_SESSION['roll']!=1){
            header('Location:/login');
        }
        
        $router->render('compras/index', [
            'titulo' => 'Compras',
            'nombre'=>$_SESSION['nombre']
        
        ]);
    }
}

/* 
id
nombre
codigo
stock
stock_minimo
precio_compra
precio_venta
ventas
id_categoria
id_proveedor

*/