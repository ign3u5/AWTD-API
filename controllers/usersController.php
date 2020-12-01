<?php
    require_once __DIR__."/../models/database.php";
    require_once __DIR__."/../models/user.php";
    require_once __DIR__."/../handlers/responseHandler.php";

    class UsersController{
        private $connection;
        private $db_table = "userstbl";

        public function __construct($database){
            $this->connection = $database;
        }

        public function ReadUsers() {
            $sqlQuery = "SELECT username, password, firstName, lastName, email, privilegeLevel FROM 
            " . $this->db_table;

            $statement = $this->connection->prepare($sqlQuery);

            if($statement->execute())
            {
                $users = array();

                while ($row = $statement->fetch(PDO::FETCH_ASSOC))
                {
                    $userCreateResponse = User::Create($row);
                    if ($userCreateResponse->IsFail())
                        return $userCreateResponse;
                    array_push($users, $userCreateResponse->payload);
                }
                return NewResponse(200, "Successfully collected all user data", $users);
            }
            return RespondWithExecutionError();
        }

        public function CreateUser($user) {
            $sqlQuery = "INSERT INTO 
            " . $this->db_table . " SET 
            username = :username, 
            password = :password, 
            firstName = :firstName, 
            lastName = :lastName, 
            email = :email, 
            privilegeLevel = :privilegeLevel";
            $statement = $this->connection->prepare($sqlQuery);

            $this->SanitiseUser($user);

            $statement->bindParam(":username", $user->username);
            $statement->bindParam(":password", $user->password);
            $statement->bindParam(":firstName", $user->firstName);
            $statement->bindParam(":lastName", $user->lastName);
            $statement->bindParam(":email", $user->email);
            $statement->bindParam(":privilegeLevel", $user->privilegeLevel);
            try
            {
                if ($statement->execute())
                {
                    return NewResponse(201, "Successfully add new user");
                }
            }
            catch (PDOException $ex)
            {
                if ($ex->errorInfo[1] == 1062)
                    return NewResponse(400, "User already exists");
            }
            return RespondWithExecutionError();
        }

        public function ReadUser($username) {
            $sqlQuery = "SELECT * FROM 
            " . $this->db_table . " WHERE 
            username = :username";

            $statement = $this->connection->prepare($sqlQuery);

            Sanitise($username);

            $statement->bindParam(":username", $username);

            if ($statement->execute())
            {
                $row = $statement->fetch(PDO::FETCH_ASSOC);
                if (isset($row))
                {
                    $userCreateResponse = User::Create($row);
                    if ($userCreateResponse->IsFail())
                        return NewResponse(404, "User not found");
                    return $userCreateResponse;
                }
                return NewResponse(404, "User not found");
            }
            return RespondWithExecutionError();
        }

        public function UpdateUser($user) {
            $sqlQuery = "UPDATE 
            " . $this->db_table . " SET 
            password = :password, 
            firstName = :firstName, 
            lastName = :lastName, 
            email = :email, 
            privilegeLevel = :privilegeLevel WHERE 
            username = :username";
            $statement = $this->connection->prepare($sqlQuery);

            $this->SanitiseUser($user);

            $statement->bindParam(":username", $user->username);
            $statement->bindParam(":password", $user->password);
            $statement->bindParam(":firstName", $user->firstName);
            $statement->bindParam(":lastName", $user->lastName);
            $statement->bindParam(":email", $user->email);
            $statement->bindParam(":privilegeLevel", $user->privilegeLevel);

            if($statement->execute())
            {
                if ($statement->rowCount() > 0)
                    return NewResponse(201, "Successfully updated user");
                return NewResponse(404, "User not found");
            }
            return RespondWithExecutionError();
        }

        public function DeleteUser($username) {
            $sqlQuery = "DELETE FROM 
            " . $this->db_table . " WHERE 
            username = :username";

            $statement = $this->connection->prepare($sqlQuery);

            Sanitise($username);

            $statement->bindParam(":username", $username);

            if ($statement->execute())
            {
                if ($statement->rowCount() > 0)
                    return NewResponse(200, "Successfully deleted user data");
                return NewResponse(404, "User not found");
            }
            return RespondWithExecutionError();
        }

        private function SanitiseUser(&$user)
        {
            Sanitise($user->username);
            Sanitise($user->password);
            Sanitise($user->firstName);
            Sanitise($user->lastName);
            Sanitise($user->email);
            Sanitise($user->privilegeLevel);
        }
    }
?>