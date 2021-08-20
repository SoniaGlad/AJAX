<?php

require '../vendor/autoload.php';

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

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\GraphQL;

try {

    $queryType = new ObjectType([
        'name' => 'Query',
        'fields' => [
            'book' => [
                'type' => Type::string(),
                'args' => [
                    'id' => ['type' => Type::int()],
                ],
                'resolve' => function ($rootValue, $args) {
                   global $bookDB;
                   $id = (int) $args['id'];
                   if($id){
                     $result = $bookDB->query('SELECT * FROM book WHERE id='.$id);
                   } else {
                    $result = $bookDB->query('SELECT * FROM book');  
                   }

                   while($response[] = $result->fetchArray(SQLITE3_ASSOC)){

                   };
                   array_pop($response);
                   return json_encode($response);
                }
            ],
        ],
    ]);

    $mutationType = new ObjectType([
        'name' => 'Insert',
        'fields' => [
            'insert' => [
                'type' => Type::int(),
                'args' => [
                    'title' => ['type' => Type::string()],
                    'author' => ['type' => Type::string()],
                    'pubyear' => ['type' => Type::int()],
                ],
                'resolve' => function ($rootValue, $args) {
                    global $bookDB;
                   
                    $title = (string) $args['title'];
                    $author = (string) $args['author'];
                    $pubyear = (int) $args['pubyear'];
                    $bookDB->exec("INSERT INTO book VALUES ( NULL,'{$title}', '{$author}', $pubyear)");
                   
                    $id = $bookDB->lastInsertRowID();

                    return $id;
                },
            ],
            'delete' => [
                'type' => Type::string(),
                'args' => [
                    'id' => ['type' => Type::int()],
                ],
                'resolve' => function ($rootValue, $args) {
                    global $bookDB;
                   
                    $id = (int) $args['id'];
                    
                    $bookDB->exec("DELETE FROM book WHERE id=$id");                
                    

                    return 'Запись удалена';
                },
            ],
        ],
    ]);

    // See docs on schema options:
    // http://webonyx.github.io/graphql-php/type-system/schema/#configuration-options
    $schema = new Schema([
        'query' => $queryType,
        'mutation' => $mutationType,
    ]);

    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'];
    $variableValues = isset($input['variables']) ? $input['variables'] : null;

    $rootValue = ['prefix' => 'You said: '];
    $result = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
    $output = $result->toArray();
} catch (\Exception $e) {
    $output = [
        'error' => [
            'message' => $e->getMessage()
        ]
    ];
}
header('Content-Type: application/json; charset=UTF-8');
echo json_encode($output);