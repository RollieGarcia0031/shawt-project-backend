<?php 

function corsAllow(){
    $allowedOrigin = "http://localhost:3000";

    if( isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] != $allowedOrigin){
        header("HTTP/1.1 403 Forbidden");
        exit;
    }

    header("Access-Control-Allow-Origin: $allowedOrigin");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: *");
}