<?php
class OlderThanOrSame
{
    private $number;

    public function __construct($timestamp)
    {
        $this->number = $timestamp;
    }

    public function validate(string $name, $value)
    {
        if ($value > $this->number) {
            throw new Exception($this->message($name));
        }
    }

    private function message(string $name): string
    {
        return "The `$name` must be older than or same `$this->number`.";
    }
}
?>