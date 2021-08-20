<?php


?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>AJAX</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <div class="row">
      <h1 class="display-4">Форма</h1>      
    </div>

    <div class="row">
      <h3>Форма</h3>
    </div>
    <div class="row" id="result" >   

    <form method=POST id=form >  
      <input type="text" name="login" id="login" autocomplete=off class="form-control" />
      <input type="password" name="password" id="password" autocomplete=off class="form-control" />
      <input type="button" value="Войти" onclick="go()" class="btn btn-primary" />
      <!-- <input type="submit" > -->
      <div id="error"></div>
    </form>
    </div>
    

    <script>
      function go(){ 
        let login = document.querySelector('#login').value;
        let password = document.querySelector('#password').value;
      
        let result = '';
        const xhr = new XMLHttpRequest();
        const url = 'login.php';
        xhr.open('POST', url, true);
        xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + password));
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send(null);

        xhr.onreadystatechange = () => {
          if(xhr.readyState == 4){
          if(xhr.status == 401){
            let error = document.querySelector('#error');
            error.innerHTML = '<div class="alert alert-danger">Ошибка ввода логина и пароля</div>';
          }
          if(xhr.status == 200){
            console.log('Статус: ',xhr.status);
            console.log('Ответ: ',xhr.responseText);
            let form = document.querySelector('#form');
            let result = document.querySelector('#result');
            form.remove();
            result.innerHTML = xhr.responseText;
          }

          }
        }

      }


    </script>

  
    <hr>
  

  </div>

  
</body>
</html>