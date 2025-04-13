<?php
header('Content-Type: application/json');
$file = 'date_ideas.json';

// Get POST data
$newIdea = json_decode(file_get_contents('php://input'), true);

if (!$newIdea) {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
    exit;
}

// Check if the file exists and load existing ideas
if (file_exists($file)) {
    $data = file_get_contents($file);
    $ideas = json_decode($data, true);

    // In case the file content is not a valid JSON array
    if (!is_array($ideas)) {
        $ideas = [];
    }
} else {
    $ideas = [];
}

// Append the new idea to the list
$ideas[] = $newIdea;

// Save the updated list back to the file
if (file_put_contents($file, json_encode($ideas, JSON_PRETTY_PRINT))) {
    echo json_encode(["status" => "success", "ideas" => $ideas]);
} else {
    echo json_encode(["status" => "error", "message" => "Unable to save data"]);
}
?>
