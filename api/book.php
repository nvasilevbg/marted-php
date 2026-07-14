<?php
require_once __DIR__ . '/../inc/functions.php';
header('Content-Type: application/json; charset=utf-8');
$raw = file_get_contents('php://input');
$d = json_decode($raw, true);
if (!$d) $d = $_POST;
$r = add_booking($d);
if (!$r['ok']) { http_response_code(400); }
echo json_encode($r);