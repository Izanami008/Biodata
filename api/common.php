<?php
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');
session_start();
require_once __DIR__ . '/../db.php';
function json_input(): array { $v=json_decode(file_get_contents('php://input'), true); return is_array($v)?$v:$_POST; }
function respond(array $data, int $status=200): never { http_response_code($status); echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); exit; }
function require_admin(): void { if (($_SESSION['role'] ?? '') !== 'admin') respond(['error'=>'Akses admin diperlukan'],403); }
function require_user(): void { if (empty($_SESSION['user_id'])) respond(['error'=>'Silakan login terlebih dahulu'],401); }
function ensure_method(array $allowed): void { if (!in_array($_SERVER['REQUEST_METHOD'] ?? 'GET',$allowed,true)) respond(['error'=>'Metode tidak diizinkan'],405); }
