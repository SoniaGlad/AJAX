<?php

define('BOOKS_DB','books.sq3');
$db = new SQLite3(BOOKS_DB);
if( !filesize(BOOKS_DB) ){

  $books = [
    'Николас Закас, Джереми Мак-Пик, Джо Фосетт Ajax для профессионалов 2013'=>'ajax-prof.jpg',
    'Vanessa Wang, Frank Salim, Marcelo Jabali The Definitive Guide to HTML5 WebSocket 2013'=>'html5-websocket.jpg',
    'Matt Frisbie  Professional JavaScript for Web Developers 2019' => 'javascript-for-web-developers.jpg',
    'Andrew Lombardi WebSocket: Lightweight Client-Server Communications 2015' => 'websocket.jpg',
    'Крейн Дейв, Паскарелло Эрик Ajax в действии 2008' => 'ajax-action.jpg',
    ];

  $db->exec('CREATE TABLE books (
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    title TEXT,
    src TEXT,
    likes INT
  )');

  foreach($books as $book => $src){
    $db->exec("INSERT INTO books (title, src, likes) VALUES ('$book','$src',0)");
  } 
}


$results = $db->query('SELECT id, title, src, likes FROM books');
$books = [];
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    $books[] = $row;
}

if( !empty($_GET['like']) ){
  $id = (int) $_GET['like'];
  $db->query("UPDATE books SET likes = likes + 1 WHERE id = $id");
  $results = $db->query("SELECT likes FROM books WHERE id = $id");
  $row = $results->fetchArray(SQLITE3_ASSOC);
  echo $row['likes'];
  die;
}



?>
