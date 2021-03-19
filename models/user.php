<?php 
    require_once __DIR__."/../handlers/responseHandler.php";

    class User
    {
        public $username;
        public $password;
        public $firstName;
        public $lastName;
        public $email;
        public $privilegeLevel;

        public function __construct($userRequestData)
        {
            $this->username = $userRequestData["username"];
            $this->password = $userRequestData["password"];
            $this->firstName = $userRequestData["firstName"];
            $this->lastName = $userRequestData["lastName"];
            $this->email = $userRequestData["email"];
            $this->privilegeLevel = $userRequestData["privilegeLevel"];
        }

        public static function Create($requestContent){
            if (isset($requestContent["username"]) && 
            isset($requestContent["password"]) &&
            isset($requestContent["firstName"]) &&
            isset($requestContent["lastName"]) && 
            isset($requestContent["email"]) && 
            isset($requestContent["privilegeLevel"]))
            {
                return NewResponseWithPayload(200, "User is valid", new User($requestContent));
            }
            return NewResponse(400, "Invalid user request payload");
        }

        public static function CreateWithoutPass($requestContent) {
            if (isset($requestContent["username"]) && 
            isset($requestContent["firstName"]) &&
            isset($requestContent["lastName"]) && 
            isset($requestContent["email"]) && 
            isset($requestContent["privilegeLevel"]))
            {
                $requestContent["password"] = "noPassword";
                return NewResponseWithPayload(200, "User is valid", new User($requestContent));
            }
            return NewResponse(400, "Invalid user request payload");
        }
    }
?>