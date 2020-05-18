<?php

include('app/lib/common.php');

$request = $_SERVER['REQUEST_URI'];
$params = substr($request, strpos($request, "?"));

switch ($request) {
    case strpos($request, '/register') :
        $url = 'app/register.php' . $params;

        break;

}
