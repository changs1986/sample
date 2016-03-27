<?php
return array(
'URL_ROUTER_ON'   => true, //开启路由
'URL_ROUTE_RULES' => array( //定义路由规则 
    'goods/:id'=>array('Goods/up','',array('method'=>'put')),
    'goods/:id'=>array('Goods/delete','',array('method'=>'delete')),
    'goods/:id'=>array('Goods/item','',array('method'=>'get')),
    'goods'=>array('Goods/add','',array('method'=>'post')),
    'goods'=>array('Goods/items','',array('method'=>'get')),
),);
