<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

require '../src/vendor/autoload.php';

$app = new \Slim\App;

// Enable CORS (Cross-Origin Resource Sharing)
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Endpoint to insert data into the database
$app->post('/postName', function (Request $request, Response $response, array $args) {
    try {
        $data = json_decode($request->getBody());

        if (!empty($data->fname) && !empty($data->lname)) {
            $fname = $data->fname;
            $lname = $data->lname;

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "demo";

            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO names (fname, lname) VALUES (:fname, :lname)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->execute();

            $conn = null;

            $responseArray = [
                "status" => "success",
                "data" => null,
            ];

            $response->getBody()->write(json_encode($responseArray));
            return $response->withHeader('Content-Type', 'application/json');
        } else {

            $responseArray = [
                "status" => "error",
                "message" => "Invalid or missing data",
            ];
            $response->getBody()->write(json_encode($responseArray));
            return $response->withHeader('Content-Type', 'application/json');
        }
    } catch (PDOException $e) {

        $errorMessage = [
            "status" => "error",
            "message" => $e->getMessage(),
        ];
        $response->getBody()->write(json_encode($errorMessage));
        return $response->withHeader('Content-Type', 'application/json');
    }
});

//endpoint printName
$app->post('/printName', function (Request $request, Response $response, array $args) {
    $data = [
        [
            "lname" => "hortizuela",
            "fname" => "manny"
        ],
        [
            "lname" => "licayan",
            "fname" => "arnold"
        ]
    ];

    $responseData = [
        "status" => "success",
        "data" => $data
    ];

    // Convert the data to JSON and set the response content type
    $response->getBody()->write(json_encode($responseData));
    return $response->withHeader('Content-Type', 'application/json');
});

// Endpoint to update data in the database
$app->post('/updateName', function (Request $request, Response $response, array $args) {
    try {
        $data = json_decode($request->getBody());

        if (!empty($data->id) && !empty($data->fname) && !empty($data->lname)) {
            $id = $data->id;
            $fname = $data->fname;
            $lname = $data->lname;

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "demo";

            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE names SET fname = :fname, lname = :lname WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $conn = null;

            $responseArray = [
                "status" => "success",
                "data" => null,
            ];

            $response->getBody()->write(json_encode($responseArray));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $responseArray = [
                "status" => "error",
                "message" => "Invalid or missing data",
            ];
            $response->getBody()->write(json_encode($responseArray));
            return $response->withHeader('Content-Type', 'application/json');
        }
    } catch (PDOException $e) {

        $errorMessage = [
            "status" => "error",
            "message" => $e->getMessage(),
        ];
        $response->getBody()->write(json_encode($errorMessage));
        return $response->withHeader('Content-Type', 'application/json');
    }
});

// Endpoint to delete data from the database
$app->delete('/deleteName', function (Request $request, Response $response, array $args) {
    try {
        $data = json_decode($request->getBody());

        if (!empty($data->id)) {
            $id = $data->id;

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "demo";

            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "DELETE FROM names WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $conn = null;

            $responseArray = [
                "status" => "success",
                "data" => null,
            ];

            $response->getBody()->write(json_encode($responseArray));
            return $response->withHeader('Content-Type', 'application/json');
        } else {

            $responseArray = [
                "status" => "error",
                "message" => "Invalid or missing data",
            ];
            $response->getBody()->write(json_encode($responseArray));
            return $response->withHeader('Content-Type', 'application/json');
        }
    } catch (PDOException $e) {
        $errorMessage = [
            "status" => "error",
            "message" => $e->getMessage(),
        ];
        $response->getBody()->write(json_encode($errorMessage));
        return $response->withHeader('Content-Type', 'application/json');
    }
});

$app->run();
    