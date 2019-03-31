<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$homeUrl = "http://localhost/api/";


$page = isset($_GET['page']) ? $_GET['page'] : 1;


$perPage = 5;


$from = ($perPage * $page) - $perPage;
