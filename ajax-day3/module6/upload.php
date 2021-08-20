<?php

echo "<pre>";
print_r($_FILES);
echo "</pre>";

if (move_uploaded_file($_FILES['file']['tmp_name'], "upload/". $_FILES['file']['name'])) {
  echo "Файл корректен и был успешно загружен.\n";
} 