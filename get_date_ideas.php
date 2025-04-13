<?php
  header('Content-Type: application/json');
  $file = 'date_ideas.json';

  if (file_exists($file)) {
      $data = file_get_contents($file);
      echo $data;
  } else {
      // if file does not exist, initialize with an empty JSON array
      echo json_encode([]);
  }
?>
