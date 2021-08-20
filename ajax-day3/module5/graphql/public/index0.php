<?php

require '../vendor/autoload.php';


use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\GraphQL;

try {

    $queryType = new ObjectType([
        'name' => 'Story',
        'fields' => [
            'info' => [
                'type' => Type::listOf(Type::string()),
                'resolve' => function () {
                    return ['Ехал', 'Грека', 'через', 'реку'];
                }
            ],
        ],
    ]);

    $mutationType = new ObjectType([
        'name' => 'Calc',
        'fields' => [
            'sum' => [
                'type' => Type::int(),
                'args' => [
                    'x' => ['type' => Type::int()],
                    'y' => ['type' => Type::int()],
                ],
                'resolve' => function ($calc, $args) {
                    return $args['x'] + $args['y'];
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