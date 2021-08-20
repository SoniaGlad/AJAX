<?php

include "vendor/autoload.php";

use Lcobucci\JWT\Builder;

// Если закомментировать блок if ниже, то можно увидеть сам token в теле страницы
if(
  $_POST['login'] != 'user' ||
  $_POST['password'] != '123'
) {
  header('HTTP/1.0 401 Unauthorized');
  
  die;
}

$time = time();
$token = (new Builder())->issuedBy('http://localhost:8080/ajax-day2/module3/lab3/') // конфигурирует эмитента 
                        ->permittedFor('http://localhost:8080/ajax-day2/module3/lab3/') // конфигурирует аудиторию
                        ->identifiedBy('4f1g23a12ba', true) // настраивает идентификатор (утверждение jti), реплицируя как элемент заголовка
                        ->issuedAt($time) // настраивает время, когда токен был выдан (iat заявки)
                        ->canOnlyBeUsedAfter($time + 60) // настраивает время, после которого токен может быть использован (утверждение nbf)
                        ->expiresAt($time + 3600) // настраивает время истечения токена (exp заявки)
                        ->withClaim('uid', 1) // настраивает новую заявку под названием «uid»
                        ->getToken(); // получает сгенерированный токен


$token->getHeaders(); // Получает заголовки токена
$token->getClaims(); // Получает токен претензий

//echo $token->getHeader('jti'); // будет "4f1g23a12ba"
//echo $token->getClaim('iss'); // будет "http://mysite.local"
//echo $token->getClaim('uid'); // будет "1"
echo $token; // строковое представление объекта является строкой JWT (довольно просто, правда?)
