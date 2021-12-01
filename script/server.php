<?php 

header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

// if ( !array_key_exists( 'HTTP_X_TOKEN', $_SERVER) ) {
//     header('Status-Code: 403');
// 	echo json_encode([ 'error' => "No autorizado - error en el token", ]);
// 	die;
// }

// $url = 'http://localhost:8001';
// $ch = curl_init( $url );
// curl_setopt(
//     $ch,
//     CURLOPT_HTTPHEADER,
//     [
//         "X-Token: {$_SERVER['HTTP_X_TOKEN']}"
//     ]
// );

// curl_setopt(
//     $ch,
//     CURLOPT_RETURNTRANSFER,
//     true
// );

// $ret = curl_exec( $ch );

// if ( $ret !== 'true') {
//     header('Status-Code: 403');
// 	echo json_encode([ 'error' => "No autorizado - error en credenciales", ]);
// 	die;
// }


// Definimos los recursos disponibles
$allowedResourceTypes = [
    'books',
    'authors',
    'genres',
];

// Validamos que el recurso este disponible
$resourceType = $_GET['resource_type'];
if ( !in_array( $resourceType, $allowedResourceTypes ) ) {
	http_response_code( 400 );
	echo json_encode(
		[
			'error' => "$resourceType is un unkown",
		]
	);
	
	die;
}
// Defino los recursos
$books = [
    1 => [
        'titulo' => 'Los juegos del calamar',
        'id_autor' => 1,
        'id_genero' => 2,
    ],
    2 => [
        'titulo' => 'El señor de los anillos',
        'id_autor' => 3,
        'id_genero' => 5,
    ],
    3 => [
        'titulo' => 'Juego de tronos',
        'id_autor' => 4,
        'id_genero' => 5,
    ],
];

// Comprobamos si el id del recurso viene.
$resourceId = array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] : '';

// Generamos la respuesta asumiendo que la solicitud es correcta
switch( strtoupper($_SERVER['REQUEST_METHOD'])) {
    case 'GET':
        if ( empty($resourceId) ) {
            echo json_encode( $books );
        } else {
            if ( array_key_exists($resourceId, $books) ) {
                echo json_encode( $books[ $resourceId ]);
            } else {
                http_response_code(404);
            }
        }
        break;
    case 'POST':
        $json = file_get_contents( 'php://input' );
        
        array_push($books, json_decode($json, true));

        echo array_keys($books)[count($books)-1];

        break;
    case 'PUT':
        // Validamos que el recurso buscado existe
        if ( !empty($resourceId) &&  array_key_exists($resourceId, $books) ) {
            // Tomamos los datos en crudo
            $json = file_get_contents( 'php://input' );
            $books[$resourceId] =  json_decode($json, true);

            // Retornamos la colección modificada
            echo json_encode( $books);
        }

        break;
    case 'DELETE':
        // Validamos que el recurso buscado existe
        if ( !empty($resourceId) &&  array_key_exists($resourceId, $books) ) {
            // Eliminar el recurso
            unset( $books[$resourceId]);

        }
            // Retornamos la colección modificada
            echo json_encode( $books);
            break;
}