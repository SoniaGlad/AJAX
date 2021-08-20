<?php

// дописываем в заголовок, что тип контента именно json (строка ниже)
header('Content-Type: application/json');

$object = (object) array(
  "name" => 'Molecule Man',
  "age" => 29
);

// метод json_encode() кодирует ч-либо в JSON
$json = json_encode($object);

print_r($json);

