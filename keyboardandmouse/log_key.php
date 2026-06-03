<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if ($raw !== '' && !is_array($data)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid JSON']);
    exit;
}

$items = isset($data['items']) && is_array($data['items']) ? $data['items'] : (is_array($data) ? [$data] : []);

// Database-free mode: the browser stores counts in localStorage for report.php.
// This endpoint remains as a compatibility no-op for older cached dashboard pages.
echo json_encode(['ok' => true, 'inserted' => 0, 'received' => count($items), 'storage' => 'browser']);
