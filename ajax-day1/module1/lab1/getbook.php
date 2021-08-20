<?php

$books = [
'Николас Закас, Джереми Мак-Пик, Джо Фосетт Ajax для профессионалов 2013',
'Vanessa Wang, Frank Salim, Marcelo Jabali The Definitive Guide to HTML5 WebSocket 2013',
'Matt Frisbie  Professional JavaScript for Web Developers 2019',
'Andrew Lombardi WebSocket: Lightweight Client-Server Communications 2015',
'Крейн Дейв, Паскарелло Эрик Ajax в действии 2008',
];

if( !empty($_GET['search']) ){
  $search  = mb_strtolower(strip_tags($_GET['search']));
  $books = array_filter($books, function($book){
    global $search;
    return strpos(mb_strtolower($book), $search) !== false;
  });
 echo implode($books, '|');
 die;
}

