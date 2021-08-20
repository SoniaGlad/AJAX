<?php

$jsonString = file_get_contents('example1.json');

// метод json_decode() декодирует JSON
$json = json_decode($jsonString);

echo "<pre>";
print_r($json);
echo "</pre>";

$members = $json->members;
foreach($members as $member){
  echo "{$member->name} {$member->age} <hr />";
}

