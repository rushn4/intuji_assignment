<?php

require './vendor/autoload.php';

session_start();


#[NoReturn] function dd($data): void
{
    print_r($data);
    die();
}