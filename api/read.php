<?php 
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once "../config/database.php";
    include_once "../class/users.php";

    $database = new Database();
    $connection = $database->getConnection();

    $userCommands = new User($connection);

    $allUserData = $userCommands->getUsers();
    $countUserData = $allUserData->rowCount();

    if ($countUserData > 0) {
        $userData = array();
        $userData["body"] = array();
        $userData["itemCount"] = $countUserData;
        while ($row = $allUserData->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $a = array(
                "id" => $id,
                "username" => $username,
                "password" => $password,
                "firstName" => $firstName,
                "lastName" => $lastName,
                "email" => $email,
                "privilegeLevel" => $privilegeLevel
            );
            array_push($userData["body"], $a);
        }
        echo json_encode($userData);
    }
    else{
        http_response_code(404);
        echo json_encode(array("message" => "Nothing found here"));
    }
?>