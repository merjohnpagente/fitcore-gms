<?php
// api/index.php — Main Router

require_once 'config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Extract the last path segment (the "action")
$segments = array_filter(explode('/', $uri));
$action   = end($segments);

// Route: /api/members or /api/members/{id}
$id = null;
if (is_numeric($action)) {
    $id     = (int) $action;
    $action = 'members'; // treat as members endpoint with id
}

switch ($action) {
    case 'members':
        switch ($method) {
            case 'GET':
                require 'endpoints/read.php';
                break;
            case 'POST':
                require 'endpoints/create.php';
                break;
            case 'PUT':
                require 'endpoints/update.php';
                break;
            case 'DELETE':
                require 'endpoints/delete.php';
                break;
            default:
                sendResponse(false, "Method not allowed.", null, 405);
        }
        break;

    default:
        sendResponse(false, "Endpoint not found. Use /api/members", null, 404);
}
