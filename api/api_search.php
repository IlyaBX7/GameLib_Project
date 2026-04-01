<?php
header('Content-Type: application/json');

$query = urlencode($_GET['q'] ?? '');
if (!$query) { 
    echo json_encode([]); 
    exit; 
}

$url = "https://steamcommunity.com/actions/SearchApps/" . $query;
$response = @file_get_contents($url);

echo $response ? $response : json_encode([]);
?>