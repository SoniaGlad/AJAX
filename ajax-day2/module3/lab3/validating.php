<?php

include "vendor/autoload.php";

use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;
//$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJub25lIiwianRpIjoiNGYxZzIzYTEyYmEifQ.eyJpc3MiOiJodHRwOlwvXC9teXNpdGUubG9jYWwiLCJhdWQiOiJodHRwOlwvXC9teXNpdGUubG9jYWwiLCJqdGkiOiI0ZjFnMjNhMTJiYSIsImlhdCI6MTU4NTc3NjI1NCwibmJmIjoxNTg1Nzc2MzE0LCJleHAiOjE1ODU3Nzk4NTQsInVpZCI6MX0.";

if( !empty($_POST['token'])  ){

    $token = trim(strip_tags( $_POST['token'] ));
  try{    
    $token = (new Parser())->parse((string) $token); 

    $data = new ValidationData(); // будет использовать текущее время для проверки (iat, nbf и exp)
    $data->setIssuer('http://localhost:8080/ajax-day2/module3/lab3/');
    $data->setAudience('http://localhost:8080/ajax-day2/module3/lab3/');
    $data->setId('4f1g23a12ba');

    if($token->validate($data)){
      echo 'информация для содержателей токена!<br>';  
    }  
  }catch(Error$er){
    echo 'просмотр запрещён';
  }
} else {
  echo 'просмотр запрещён';
}