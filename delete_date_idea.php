<?php
header('Content-Type: application/json');
$file = 'date_ideas.json';

// Read POST input.
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['index'])) {
    echo json_encode(["status" => "error", "message" => "No index provided"]);
    exit;
}

$index = intval($input['index']);

// Load the existing ideas.
if (file_exists($file)) {
    $data = file_get_contents($file);
    $ideas = json_decode($data, true);
    if (!is_array($ideas)) {
        $ideas = [];
    }
} else {
    echo json_encode(["status" => "error", "message" => "Data file not found"]);
    exit;
}

// Check index boundaries.
if ($index < 0 || $index >= count($ideas)) {
    echo json_encode(["status" => "error", "message" => "Invalid index"]);
    exit;
}

// Remove the idea from the array.
array_splice($ideas, $index, 1);

// Write the updated ideas back to the JSON file.
if (file_put_contents($file, json_encode($ideas, JSON_PRETTY_PRINT))) {
    echo json_encode(["status" => "success", "ideas" => $ideas]);
} else {
    echo json_encode(["status" => "error", "message" => "Unable to update data"]);
}
?>
