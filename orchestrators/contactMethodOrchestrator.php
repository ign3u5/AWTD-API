<?php
require_once __DIR__."/../handlers/requestHandler.php";
    require_once __DIR__."/../handlers/responseHandler.php";
    require_once __DIR__."/../handlers/tokenHandler.php";
    require_once __DIR__."/../handlers/emailHandler.php";
    require_once __DIR__."/../models/contactForm.php";

    class ContactMethodOrchestrator
    {
        private $emailHandler;
        public function __construct()
        {
            $this->emailHandler = new EmailHandler("WS311471@weston.ac.uk");
        }
        public function Handle()
        {
            switch ($_SERVER['REQUEST_METHOD'])
            {
                case "POST":
                    $jsonRequestResponse = NewJsonObjectRequest(function ($p) { return ContactFormData::Create($p);});
                    if ($jsonRequestResponse->IsFail())
                        return $jsonRequestResponse;

                    return $this->emailHandler->EmailContactFormData($jsonRequestResponse->payload);
                break;
                case "OPTIONS":
                    return NewResponse(200, "Options request response");
                break;
                default:
                    return NewResponse(400, "Invalid request method");
            }
        }
    }
?>