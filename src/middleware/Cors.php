<?php 

function corsAllow(){
    $allowedOrigin = "http://localhost:3000";
    
    header("Access-Control-Allow-Origin: $allowedOrigin");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Methods: *");
    header("Access-Control-Allow-Credentials: true");

    if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
        http_response_code(200);
        exit;
    }

    if( isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] != $allowedOrigin){
        header("HTTP/1.1 403 Forbidden");
        exit;
    }
}