<?php
require_once __DIR__ . '/../inc/functions.php';
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['taken' => taken_slots()]);