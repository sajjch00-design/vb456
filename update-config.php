<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$configFile = 'config.json';
$password = '7tk89ax1vz2';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || $input['password'] !== $password) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid password']);
        exit;
    }
    
    if (!isset($input['downloadLink']) || empty($input['downloadLink'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Download link is required']);
        exit;
    }
    
    $config = [
        'downloadLink' => $input['downloadLink'],
        'lastUpdated' => date('c')
    ];
    
    if (file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true, 'message' => 'Config updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update config file']);
    }
} else {
    // GET request - return current config
    if (file_exists($configFile)) {
        $config = json_decode(file_get_contents($configFile), true);
        echo json_encode($config);
    } else {
        echo json_encode(['downloadLink' => 'https://drive.google.com/uc?export=download&id=1yzXS6ExAq9dyNfeMN27ClmvEUH49TGxH']);
    }
}
?>