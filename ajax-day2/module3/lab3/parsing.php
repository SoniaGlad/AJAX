<?php

include "vendor/autoload.php";

use Lcobucci\JWT\Parser;
$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJub25lIiwianRpIjoiNGYxZzIzYTEyYmEifQ.eyJpc3MiOiJodHRwOlwvXC9teXNpdGUubG9jYWwiLCJhdWQiOiJodHRwOlwvXC9teXNpdGUubG9jYWwiLCJqdGkiOiI0ZjFnMjNhMTJiYSIsImlhdCI6MTU4NTc3NjI1NCwibmJmIjoxNTg1Nzc2MzE0LCJleHAiOjE1ODU3Nzk4NTQsInVpZCI6MX0.";

$token = (new Parser())->parse((string) $token); // парсим из строки
$token->getHeaders(); // получаем заголовки из токена
$token->getClaims(); // получает токен претензий

echo $token->getHeader('jti'); // будет "4f1g23a12ba"
echo $token->getClaim('iss'); // будет "сайт"
echo $token->getClaim('uid'); // будет "1"