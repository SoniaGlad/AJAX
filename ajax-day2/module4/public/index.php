<?php 
require_once __DIR__ . '/../vendor/autoload.php';

define('DBNAME', 'course.sq3');

$courses = [
 [ 
 'title'  => 'JavaScript. Уровень 1. Основы веб - программирования',
 'duration' => 24,
 'url' => 'https://www.specialist.ru/course/oprveb-a',
 ],
];

$courseDB = new SQLite3(DBNAME);

if( !filesize(DBNAME) ){
    $courseDB->exec('CREATE TABLE IF NOT EXISTS course (
        id        INTEGER PRIMARY KEY AUTOINCREMENT,
        title     TEXT    NOT NULL,
        url       TEXT    NOT NULL,
        duration  INTEGER NULL NULL
     )');
    foreach ($courses as $course){
        $courseDB->exec(<<<SQL
INSERT INTO course VALUES (
  NULL,
  '${course['title']}',
  '${course['url']}',
  ${course['duration']}
);
SQL
);
    }
}

        
$restServer = new \ByJG\RestServer\ServerRequestHandler();

$restServer->addRoute(
    \ByJG\RestServer\RoutePattern::get(
        '/',  
        function ($response, $request) {  
            $response->write('OK');
        }
    )
);
$restServer->addRoute(
    \ByJG\RestServer\RoutePattern::get(
        '/course',
        function ($response, $request) { 
            global $courseDB;
            $result = $courseDB->query('SELECT * FROM course');
            $tmp = [];
            while($tmp[] = $result->fetchArray(SQLITE3_ASSOC));
            array_pop($tmp);
            $response->write($tmp);
        }
    )
);
$restServer->addRoute(
    \ByJG\RestServer\RoutePattern::get(
        '/course/{id}',
        function ($response, $request) { 
            global $courseDB;
            $id = (int) $request->get('id');
            $result = $courseDB->query('SELECT * FROM course WHERE id='.$id);

            $response->write($result->fetchArray(SQLITE3_ASSOC));
        }
    )
);  
    
$restServer->addRoute(
    \ByJG\RestServer\RoutePattern::post(
        '/course',
        function ($response, $request) {
	global $courseDB;
        file_put_contents('test.txt',var_export(getallheaders(), true));
        $method = strip_tags($request->post('_method'));
        
        parse_str(getallheaders()['body'], $fid) ;
        file_put_contents('test.txt', "\r\n".$fid['id'], FILE_APPEND);
        file_put_contents('test.txt', "\r\n".$fid['_method'], FILE_APPEND);

        $title = strip_tags($request->post('title'));
        $url = strip_tags($request->post('url'));
        $duration = strip_tags($request->post('duration'));
        
        if(strtolower($method) == 'delete' && $id = $request->post('id')){
          $courseDB->exec(<<<SQL
DELETE FROM course WHERE id = ${id}
SQL
);  
$response->write(['message' => 'Course deleted']);
        } else if($fid['_method'] == 'delete' && $id = $fid['id']){
          $courseDB->exec(<<<SQL
DELETE FROM course WHERE id = ${id}
SQL
);  
$response->write(['message' => 'Course deleted']);
        } else 
            
        if( $title && $url && $duration){
         $courseDB->exec(<<<SQL
INSERT INTO course VALUES (
  NULL,
  '${title}',
  '${url}',
  ${duration}
);
SQL
);
         $response->write(['message' => 'Курс создан', 'id' => $courseDB->lastInsertRowID() ]);
       
        } else          
          $response->write(['error' => 'Не хватает данных для создания курса']);
        }
    )
);

$restServer->addRoute(
    \ByJG\RestServer\RoutePattern::get(
        '/course.xml',                   // The route
        function ($response, $request) {  // The Closure for Process the request 
            $response->write(['qwer'=>123,'rtyu' => 456]);
        }, null,
\ByJG\RestServer\HandleOutput\XmlHandler::class 
    )
);

$restServer->handle();