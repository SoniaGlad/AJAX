<?php


if (
  $_SERVER['PHP_AUTH_USER'] != 'user' || $_SERVER['PHP_AUTH_PW'] != '123'
) {
  // при активной строке ниже, при вводе неправильных данных в поля формы, появляется доп. окно с просьбой ввести данные - так делать НЕ надо!
  // header('WWW-Authenticate: Basic realm="My Realm"');
  header('HTTP/1.0 401 Unauthorized');
  echo 'Текст, отправляемый в том случае,
  если пользователь нажал кнопку Cancel';
  exit;
} else {
  echo "<p>Привет, {$_SERVER['PHP_AUTH_USER']}!</p>";
  echo "<p>Вы ввели пароль {$_SERVER['PHP_AUTH_PW']}.</p>"; 
}


?>
