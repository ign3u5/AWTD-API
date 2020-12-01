<?php

require_once "JsonParser.php";
require_once "Base64Parser.php";

class TokenGenerator
{
    private $signer;
    private $jsonParser;
    private $base64Parser;

    public function __construct($signer)
    {
        $this->signer = $signer;
        $this->jsonParser = new JsonParser();
        $this->base64Parser = new Base64Parser();
    }

    public function generate($claims = [])
    {
        $header = $this->base64Parser->encode($this->jsonParser->encode($this->header()));
        $payload = $this->base64Parser->encode($this->jsonParser->encode($claims));
        $signature = $this->base64Parser->encode($this->signer->sign("$header.$payload"));

        return join('.', [$header, $payload, $signature]);
    }
    
    private function header(): array
    {
        return ['alg' => $this->signer->name(), 'typ' => 'JWT'];
    }

}

?>