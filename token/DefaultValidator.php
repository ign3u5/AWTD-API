<?php
require_once "NewerThan.php";
require_once "OlderThanOrSame.php";
require_once "PublicClaimNames.php";

class DefaultValidator
{
    private $rules = [];

    public function __construct()
    {
        $this->addRule(
            PublicClaimNames::EXPIRATION_TIME,
            new NewerThan(time()),
            false
        );
        $this->addRule(
            PublicClaimNames::NOT_BEFORE,
            new OlderThanOrSame(time()),
            false
        );
        $this->addRule(
            PublicClaimNames::ISSUED_AT,
            new OlderThanOrSame(time()),
            false
        );
    }

    public function addRule($claimName, $rule, $required = true)
    {
        $this->rules[$claimName][] = [$rule, $required];
    }

    public function validate(array $claims = [])
    {
        foreach ($this->rules as $claimName => $rules) {
            $exists = isset($claims[$claimName]);
            $value = $exists ? $claims[$claimName] : null;

            foreach ($rules as $ruleAndState) {
                
                list($rule, $required) = $ruleAndState;

                if ($exists) {
                    $rule->validate($claimName, $value);
                } elseif ($required) {
                    $message = "The `$claimName` is required.";
                    throw  new Exception("DefaultValidator error".$message);
                }
            }
        }
    }
}
?>