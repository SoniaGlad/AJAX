<?php

include 'books.php';

?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Fetch API</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <div class="row">
      <h1 class="display-4">Fetch API</h1>      
    </div>
    <div class="row">
      <h3>Добавление книги</h3>
    </div>
    <div class="row">   

    <form method=POST >  
      <input type="text" name="title" id="title" autocomplete=off />
      <input type="button" value="Добавить" onclick="add()" />
      <input type="submit" >
    </form>
    </div>

    <script>
      function add(){
        let title = document.querySelector('#title');

      }
    </script>  
    <hr>
    <h3>Книги</h3>

<?php
  foreach($books as $book){
?>  
	<div class="row">	  

  <div class="card mb-3" style="max-width: 540px;">
  <div class="row no-gutters">
    <div class="col-md-4">
    <img src="img/<?= $book['src']?>"  width='100' />
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
  <script>
const handleClick = function(ev) { 
 ev.preventDefault();

 let id = this.getAttribute('data-id');
 let span = this.getElementsByTagName('span')[0]

 fetch("example4.php?like="+id)
  .then((response) => {
    return response.json();
  })
  .then((data) => {
    console.log(data)
    span.innerHTML = +span.innerHTML+1;
    // this.getElementsByTagName('span')[0].innerHTML = data;
  });

}

const likes = document.querySelectorAll('.like');
for(let i = 0; i < likes.length; i++){
  likes[i].addEventListener('click', handleClick)
}

  </script>
  
</body>
</html>