<?php
    require_once __DIR__."/../models/user.php";  
    require_once "responseHandler.php";
    require_once __DIR__."/../token/HS256.php";
    require_once __DIR__."/../token/TokenGenerator.php";
    require_once __DIR__."/../token/Parser.php";
    require_once __DIR__."/../config.php";

    class JWTHandler
    {
        private $generator;
        private $parser;
        private $lifetimeMinutes;
        public function __construct()
        {
            $signature = new HS256(JWTDetails::SECRET);
            $this->generator = new TokenGenerator($signature);
            $this->parser = new Parser($signature);
            $this->lifetimeMinutes = 30;
        }

        private function lifetimeSeconds()
        {
            return $this->lifetimeMinutes * 60;
        }
        
        public function Decode($requestToken)
        {
            try
            {
                return NewResponseWithPayload(200, "Request token successfully parsed", $this->parser->parse($requestToken));
            }
            catch (Exception $ex)
            {
                return NewResponse(400, "Invalid auth token: " . $ex);
            }
        }
        public function Encode($token)
        {
            try
            {
                $requestToken = array();
                $requestToken["username"] = $token->username;
                $requestToken["privilegeLevel"] = $token->privilegeLevel;
                $requestToken["firstName"] = $token->firstName;
                $requestToken["lastName"] = $token->lastName;
                $requestToken["exp"] = time() + $this->lifetimeSeconds();
                echo $requestToken["username"];
                $generatedToken = $this->generator->generate($requestToken);
                echo $generatedToken;
                return NewResponseWithPayload(200, "Token refreshed", $generatedToken);
            }
            catch(Exception $ex)
            {
                return NewResponse(400, "Error encoding token");
            }
        }
    }
?>