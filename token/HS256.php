<?php
    class HS256
    {
        protected static $name = 'HS256';
        protected $key;

        public function __construct(string $key)
        {
            $this->setKey($key);
        }

        public function sign(string $message): string
        {
            $signature = hash_hmac($this->algorithm(), $message, $this->key, true);
    
            if ($signature === false) {
                throw new Exception("Signature is false");
            }
    
            return $signature;
        }

        public function verify(string $plain, string $signature)
        {
            if ($signature != $this->sign($plain)) {
                throw new Exception("Error with verification");
            }
        }

        protected function algorithm(): string
        {
            return 'sha' . substr($this->name(), 2);
        }

        public function name(): string
        {
            return static::$name;
        }

        public function getKey(): string
        {
            return $this->key;
        }

        public function setKey(string $key)
        {
            if (strlen($key) < 32 || strlen($key) > 6144) {
                throw new Exception("Length is outside of bounds");
            }
    
            $this->key = $key;
        }
    }
?>