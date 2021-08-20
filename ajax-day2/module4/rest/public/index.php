<?php 
require_once __DIR__ . '/../vendor/autoload.php';

define('DBNAME', 'book.sq3');

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

$bookDB = new SQLite3(DBNAME);

if( !filesize(DBNAME) ){
    $bookDB->exec('CREATE TABLE IF NOT EXISTS book (
        id        INTEGER PRIMARY KEY AUTOINCREMENT,
        title     TEXT    NOT NULL,
        author    TEXT    NOT NULL,
        pubyear   INTEGER NULL NULL
     )');
    foreach ($books as $book){
        $bookDB->exec(<<<SQL
INSERT INTO book VALUES (
  NULL,
  '${book['title']}',
  '${book['author']}',
  ${book['pubyear']}
);
SQL
);
    }
}

        
$restServer = new \ByJG\RestServer\ServerRequestHandler();

$restServer->addRoute(
    \ByJG\RestServer\RoutePattern::get(
        'http://localhost:8080/ajax-day2/module4/rest/public',  
        function ($response, $request) {  
            $response->write('OK');
        }
    )
);
$restServer->addRoute(
    \ByJG\RestServer\RoutePattern::get(
        '/book',
        function ($response, $request) { 
            global $bookDB;
            $result = $bookDB->query('SELECT * FROM book');
            $tmp = [];
            while($tmp[] = $result->fetchArray(SQLITE3_ASSOC));
            array_pop($tmp);
            $response->write($tmp);
        }
    )
);
$restServer->addRoute(
    \ByJG\RestServer\RoutePattern::get(
        '/book/{id}',
        function ($response, $request) { 
            global $bookDB;
            $id = (int) $request->get('id');
            $result = $bookDB->query('SELECT * FROM book WHERE id='.$id);

            $response->write($result->fetchArray(SQLITE3_ASSOC));
        }
    )
);  
    
$restServer->addRoute(
    \ByJG\RestServer\RoutePattern::post(
        '/book',
        function ($response, $request) {
	global $bookDB;	 
        file_put_contents('test.txt',var_export(getallheaders(), true));
        $method = strip_tags($request->post('_method'));
        
        parse_str(getallheaders()['body'], $fid) ;
        file_put_contents('test.txt', "\r\n".$fid['id'], FILE_APPEND);
        file_put_contents('test.txt', "\r\n".$fid['_method'], FILE_APPEND);

        $title = strip_tags($request->post('title'));
        $author = strip_tags($request->post('author'));
        $pubyear = strip_tags($request->post('pubyear'));
        
        if(strtolower($method) == 'delete' && $id = $request->post('id')){
          $bookDB->exec(<<<SQL
DELETE FROM book WHERE id = ${id}
SQL
);  
$response->write(['message' => 'Book deleted']);
        } else if($fid['_method'] == 'delete' && $id = $fid['id']){
          $bookDB->exec(<<<SQL
DELETE FROM book WHERE id = ${id}
SQL
);  
$response->write(['message' => 'Book deleted']);
        } else 
            
        if( $title && $author && $pubyear){
         $bookDB->exec(<<<SQL
INSERT INTO book VALUES (
  NULL,
  '${title}',
  '${author}',
  ${pubyear}
);
SQL
);
         $response->write(['message' => 'Книга создана', 'id' => $bookDB->lastInsertRowID() ]);
       
        } else          
          $response->write(['error' => 'Не хватает данных для создания книги']);
        }
    )
);
/*
 <form action="http://rest/book" method="post">
  <input type="text" name="title" value="javascript" />
  <input type="text" name="author" value="Крокфорд" />
  <input type="text" name="pubyear" value="2019" />
  <input type="submit" />
</form>  
 * 
 *  <form action="http://rest/book" method="post">
  <input type="text" name="id" value="14" />
  <input type="hidden" name="_method" value="delete" />  
  <input type="submit" />
</form>  
*/
$restServer->addRoute(
    \ByJG\RestServer\RoutePattern::get(
        '/book.xml',                   // The route
        function ($response, $request) {  // The Closure for Process the request 
            $response->write(['qwer'=>123,'rtyu' => 456]);
        }, null,
\ByJG\RestServer\HandleOutput\XmlHandler::class 
    )
);

$restServer->handle();