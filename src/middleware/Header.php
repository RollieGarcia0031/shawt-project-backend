<?php

function headerMiddleware() {
    $cond1 = isset($_SERVER['REQUEST_METHOD']);
    $cond2 = isset($_SERVER['CONTENT_TYPE']);
    $cond3 = strpos(  $_SERVER['CONTENT_TYPE'], 'application/json') !== false;

    $perfect = $cond1 && $cond2 && $cond3;
    if(!$perfect) {
        header("HTTP/1.1 400 Bad Request");
        exit;
    }
}