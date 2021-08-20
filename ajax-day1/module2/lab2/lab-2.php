<?php

include 'books.php';

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
      <h1 class="display-4">AJAX</h1>      
    </div>

    <div class="row">
      <h3>Добавление книги</h3>
    </div>
    <div class="row">   

    <form method=POST>  
      <input type="text" name="title" id="title" autocomplete=off />
      <input type="button" value="Добавить" onclick="add()" />
      <!-- <input type="submit"> -->
    </form>

    <script>
/* Реализовать функциюю add(), которая будет принимать данные из поля title и отправлять POST-запрос на lab-2.php с параметром title*/
      function add(){
        let title = document.querySelector('#title');
        let books = document.querySelector('#books');
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'books.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        if (title.value.length < 10) return;
        xhr.send(`title=${encodeURIComponent(title.value)}`);

        xhr.onreadystatechange = () => {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        console.log(xhr.responseText);

                        let newBook = `<div class="row">	  

<div class="card mb-3" style="max-width: 540px;">
<div class="row no-gutters">
  <div class="col-md-4">
  <img src="http://placehold.it/150x220"  width='100'>
  </div>
  <div class="col-md-8">
    <div class="card-body">
      <h5 class="card-title">Книга</h5>
      <p class="card-text">${title.value}</p>
      <p class="card-text">
        <small class="text-muted">
          <a href="#" class="like" data-id="${xhr.responseText}">Нравится <span>0</span></a>
        </small>
      </p>
    </div>
  </div>
</div>

</div>
</div>`;
books.innerHTML += newBook;
const likes = document.querySelectorAll('.like');
  likes[likes.length - 1].addEventListener('click', handleClick);
  title.value = '';
  title.focus();

                    }
                }
            }


        console.log(xhr);
      }


    </script>

    </div>
    <hr>
    <h3>Книги по запросам</h3>

<div id="books">
<?php
  foreach($books as $book){
?>  
	<div class="row">	  

  <div class="card mb-3" style="max-width: 540px;">
  <div class="row no-gutters">
    <div class="col-md-4">
    <img src="<?= $book['src'] ? "img/".$book['src'] : 'http://placehold.it/150x220'  ?>"  width='100' />
    </div>
    <div class="col-md-8">
      <div class="card-body">
        <h5 class="card-title">Книга</h5>
        <p class="card-text"><?= $book['title'] ?></p>
        <p class="card-text"><small class="text-muted"><a href="#" class="like" data-id="<?= $book['id']?>">Нравится <span><?=  $book['likes'] ?></span></a></small></p>
      </div>
    </div>
  </div>

</div>

  </div>


<?php
  }
?>
  </div>

  </div>
  <script>
const handleClick = function(ev) { 
 ev.preventDefault();
 const xhr = new XMLHttpRequest();
 let id = this.getAttribute('data-id');
 let span = this.getElementsByTagName('span')[0]

 xhr.open('GET', "lab-2.php?like="+id, true);
 xhr.send(null);

 span.innerHTML = +span.innerHTML+1;

 xhr.onreadystatechange = () => {
   if(xhr.readyState == 4){
	 if(xhr.status != 200){
    
	 } else {
    //this.getElementsByTagName('span')[0].innerHTML = xhr.responseText;
  }

   }
 }
}

const likes = document.querySelectorAll('.like');
for (let i = 0; i < likes.length; i++) {
  likes[i].addEventListener('click', handleClick);
}

  </script>
  
</body>
</html>