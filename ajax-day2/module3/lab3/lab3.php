<?php


?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Lab 3 - JWT</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <div class="row">
      <h1 class="display-4">Lab 3 - JWT логинизация</h1>      
    </div>

    <div class="row" id="result" >   

          <form method=POST id=form >  
            <input type="text" name="login" id="login" autocomplete=off class="form-control" />
            <input type="password" name="password" id="password" autocomplete=off class="form-control" />
            <!-- <input type="button" value="Войти" onclick="go()" class="btn btn-primary" > -->
            <input type="button" value="Войти" class="btn btn-primary btn-first" >
            <!-- <input type="submit" > -->
            <div id="error"></div>
          </form>
    </div>
    
    <div class="row">
        <!-- <input type="button" value="Показать" onclick="show()" class="btn btn-primary"> -->
        <input type="button" value="Показать" class="btn btn-primary btn-second">
     </div>

    <script>
      'use strict';
    /* Создайте функцию go(), которая отправляет методом POST параметры login и password из формы на адрес create_token.php. Посмотрите содержимое create_token.php, там логин и пароль жёстко зашиты в код
    
    При 200 статусе ответа, установите пришедший токен в сессионное хранилище sessionStorage.setItem('параметр', 'значение') под именем key

    Удалите форму
    */

      // нахожу 1-ю кнопку для залогинивания
      let btn_first = document.querySelector('.btn-first');
      // вешаю на нее обработчик
      btn_first.addEventListener('click', go);

      function go(){ 
        let login = document.querySelector('#login');
        let password = document.querySelector('#password');

        // создаем новый xml запрос
        let xhr = new XMLHttpRequest();

        // ссылка на страницу, куда на проверку отправляем логин и пароль, т.е. create_token.php
        let url = 'create_token.php';

        // создаем запрос методом POST c нужным нам url и асинхронным запросом (т.е. true)
        xhr.open('POST', url, true);

        // устанавливаем заголовок (заголовок: по типу контента, значение: по умолчанию, т.е. кодирование ключ-значение)
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

        // отправляем запрос вида 2-х пар ключ-значение: 1. логин, 2. пароль
        xhr.send(`login=${login.value}&password=${password.value}`);

        // создаем обработчик события при каждом изменении свойства readyState (т.е. текущее состояние объекта)
        xhr.onreadystatechange = function() {
          // если текущее состояние равно 4 (операция полностью завершена) и если статус равен 200 (запрос выполнен успешно)
          if(xhr.readyState == 4 && xhr.status == 200){
                // выводим в консоль статус запроса
                console.log(`Статус: ${xhr.status}`);
                // выводим в консоль сам ответ (у нас это будет токен)
                console.log('Ответ: ', xhr.responseText);

                // находим форму
                let form = document.querySelector('#form');
                // находим куда будем выводить результат
                let result = document.querySelector('#result');

                // ВНИМАНИЕ! Не вижу смысла удалять форму, т.к. result.innerHTML перезапишет все данные, поэтому форма и так уже будет удалена
                // form.remove();

                // в результат на страницу помещаем сам ответ запроса (у нас это будет токен)
                result.innerHTML = xhr.responseText;

                // в объект Хранилище текущей Сессии (sessionStorage) мы сохраняем методом setItem('key', 'value') ответ запроса с ключом key ('key', xhr.responseText)
                sessionStorage.setItem('key', xhr.responseText);

          }
        }

      }

      /* Опишите фунцию show(), которая должна сделать запрос методом POST на страницу validating.php, при это отправив параметр token со значением из сессионного хранилища sessionStorage.getItem('параметр'), если этот параметр действительно существует */

      // нахожу 2-ю кнопку для показа
      let btn_second = document.querySelector('.btn-second');
      // вешаю на нее обработчик
      btn_second.addEventListener('click', show);


      function show(){ 
        // создаем переменную, куда будем выводить результат - это пустая строка
        let result = '';

        // создаем новый xml запрос
        let xhr = new XMLHttpRequest();

        // ссылка на страницу, куда отправляем запрос, т.е. validating.php
        let url = 'validating.php';

        // создаем запрос методом POST c нужным нам url и асинхронным запросом (т.е. true)
        xhr.open('POST', url, true);

        // устанавливаем заголовок (заголовок: по типу контента, значение: по умолчанию, т.е. кодирование ключ-значение)
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

        // если в сессионном хранилище (методом getItem()) мы находим нужное нам значение по интересующему ключу ->
        if (sessionStorage.getItem('key')) {
          // -> тогда и только тогда отправляем запрос вида 1-й пары ключ-значение: 1. токен
          xhr.send(`token=${sessionStorage.getItem('key')}`);
        }

        // создаем обработчик события при каждом изменении свойства readyState (т.е. текущее состояние объекта)
        xhr.onreadystatechange = function() {

          // -> и если статус равен 200 (запрос выполнен успешно) ->
          if(xhr.readyState == 4 && xhr.status == 200) {

              // -> и если статус равен 200 (запрос выполнен успешно) ->
              // if(xhr.status == 200) {
                // находим куда будем выводить результат
                let result = document.querySelector('#result');

                // ВАЖНО! 1. Оба вывода ниже заработают только через минуту после логина (кликать на "Показать" через минуту)!!! Т.к. при создании токена в файле create_token.php на строке 22 стоит 60 секунд (1 мин.); 2. Все выводы перестанут появляться после 1-го часа, т.к. в файле create_token.php на строке 23 стоит 3600 секунд (1 час).
                // выводим в консоль статус запроса
                console.log(`Статус: ${xhr.status}`);
                // выводим в консоль сам ответ (у нас это будет токен)
                console.log('Ответ: ', xhr.responseText);

                // -> Если хотим убрать форму при отсутствии данных или неправильных данных
                // let form = document.querySelector('#form');
                // form.remove();
                // <-

                // к 1-му результату выше суммируем/добавляем этот результат на страницу, т.е. помещаем токен в виде строки (распарсено JSONом)
                result.innerHTML += xhr.responseText;
              // }

          }
        }

      }

      // Если мы хотим после перезагрузки убрать форму:
      /* 
      (function(){
        //получаем токен из хранилища
        const token = sessionStorage.getItem('key')

        if( token ){
          //отправить запрос на validating.php
          //если ответ будет не 401, то удалить форму
          const xhr = new XMLHttpRequest();
          xhr.open('POST', 'validating.php', true)
          xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
          xhr.send(`token=${token}`);
          xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 && xhr.status == 200){
              let form   = $('#form')[0];
              form.remove()
            }
          }
        }

      })();
      */

    </script>

  
    <hr>
  

  </div>

  
</body>
</html>