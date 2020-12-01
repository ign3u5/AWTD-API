<?php
require_once "DefaultValidator.php";
require_once "JsonParser.php";
require_once "Base64Parser.php";

class Parser
{
    private $verifier;
    private $validator;
    private $jsonParser;
    private $base64Parser;

    public function __construct($verifier)
    {
        $this->verifier = $verifier;
        $this->validator = new DefaultValidator();
        $this->jsonParser = new JsonParser();
        $this->base64Parser = new Base64Parser();
    }

    public function parse(string $jwt): array
    {
        list($header, $payload, $signature) = $this->explodeJwt($jwt);

        $this->verifySignature($header, $payload, $signature);

        $claims = $this->extractClaims($payload);

        $this->validator->validate($claims);

        return $claims;
    }

    private function explodeJwt(string $jwt): array
    {
        $sections = explode('.', $jwt);

        if (count($sections) != 3) {
            throw new Exception('Token format is not valid');
        }

        return $sections;
    }

    public function verify(string $jwt)
    {
        list($header, $payload, $signature) = $this->explodeJwt($jwt);

        $this->verifySignature($header, $payload, $signature);
    }

    private function verifySignature(string $header, string $payload, string $signature)
    {
        $signature = $this->base64Parser->decode($signature);

        $this->verifier->verify("$header.$payload", $signature);
    }

    private function extractClaims(string $payload): array
    {
        return $this->jsonParser->decode($this->base64Parser->decode($payload));
    }

    public function validate(string $jwt)
    {
        list($header, $payload, $signature) = $this->explodeJwt($jwt);

        $this->verifySignature($header, $payload, $signature);

        $claims = $this->extractClaims($payload);

        $this->validator->validate($claims);
    }
}
?>