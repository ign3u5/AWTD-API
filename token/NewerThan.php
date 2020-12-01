<?php
class NewerThan
{
    private $number;

    public function __construct($timestamp)
    {
        $this->number = $timestamp;
    }

    public function validate($name, $value)
    {
        if ($value <= $this->number)
        {
            throw new Exception($this->message($name));
        }
    }

    private function message(string $name): string
    {
        return "The `$name` must be newer than `$this->number`.";
    }
}
?>