<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__ . '/vendor/autoload.php';
include_once 'db.php';
include_once 'st.php';

$db = new Database();
$db = $db->getConnection();

$st_id = filter_input(INPUT_GET, 'st', FILTER_SANITIZE_URL);

$st = new Student($db);

$get_grades = $st->getGrades($st_id);