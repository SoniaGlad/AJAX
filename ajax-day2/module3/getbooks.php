<?php

$books = [
 [
 'author' => 'Николас Закас, Джереми Мак-Пик, Джо Фосетт',
 'title'  => 'Ajax для профессионалов',
 'pubyear' => 2013
 ],
 [
 'author' => 'Vanessa Wang, Frank Salim, Marcelo Jabali',
 'title'  => 'The Definitive Guide to HTML5 WebSocket',
 'pubyear' => 2013
 ],
 [
 'author' => 'Matt Frisbie',
 'title'  => 'Professional JavaScript for Web Developers',
 'pubyear' => 2019
 ],
 [
 'author' => 'Andrew Lombardi',
 'title'  => 'Lightweight Client-Server Communications',
 'pubyear' => 2015
 ],
 [
 'author' => 'Крейн Дейв, Паскарелло Эрик',
 'title'  => 'WebSocket: Lightweight Client-Server Communications',
 'pubyear' => 2008
 ],
];

if( !empty($_POST['search']) ){
  $search  = mb_strtolower(strip_tags($_POST['search']));
  $books = array_filter($books, function($book){
    global $search;
    return strpos(mb_strtolower($book['title']), $search) !== false;
  });
 header('Content-Type: application/json');

 echo json_encode($books,JSON_FORCE_OBJECT);
 die;
}
