<?php 
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once "../config/database.php";
    include_once "../class/users.php";

    $database = new Database();
    $connection = $database->getConnection();

    $user = new User($connection);

    $data = json_decode(file_get_contents("php://input"));

    $user->username = $data->username;
    $user->password = $data->password;
    $user->firstName = $data->firstName;
    $user->lastName = $data->lastName;
    $user->email = $data->email;
    $user->privilegeLevel = $data->privilegeLevel;

    if ($user->addUser())
    {
        echo "User created successfully.";
    }
    else {
        echo "User could not be created";
    }
?>