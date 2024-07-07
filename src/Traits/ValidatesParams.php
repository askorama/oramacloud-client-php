<?php

namespace OramaCloud\Traits;

trait ValidatesParams
{
    public function validate(array $params, array $rules = [])
    {
        foreach ($rules as $key => $rules) {
            $value = isset($params[$key]) ? $params[$key] : null;

            foreach ($rules as $rule) {
                if (in_array('optional', $rules) && is_null($value)) {
                    continue;
                }

                $validation = "validate{$rule}";
                if (method_exists($this, $validation)) {
                    $this->{$validation}($key, $value);
                } else {
                    throw new \InvalidArgumentException("The $rule validation rule is not supported.");
                }
            }

            $params[$key] = $value;
        }

        return $params;
    }

    private function validateRequired($key, $value)
    {
        if (is_null($value) || empty($value)) {
            throw new \InvalidArgumentException("The $key parameter is required.");
        }
    }

    private function validateString($key, $value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException("The $key parameter must be a string.");
        }
    }

    private function validateArray($key, $value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException("The $key parameter must be an array.");
        }
    }

    private function validateBoolean($key, $value)
    {
        if (!is_bool($value)) {
            throw new \InvalidArgumentException("The $key parameter must be a boolean.");
        }
    }

    private function validateInteger($key, $value)
    {
        if (!is_int($value)) {
            throw new \InvalidArgumentException("The $key parameter must be an integer.");
        }
    }

    private function validateNumeric($key, $value)
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException("The $key parameter must be numeric.");
        }
    }

    private function validateEmail($key, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("The $key parameter must be a valid email address.");
        }
    }

    private function validateUrl($key, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("The $key parameter must be a valid URL.");
        }
    }

    private function validateDate($key, $value)
    {
        if (strtotime($value) === false) {
            throw new \InvalidArgumentException("The $key parameter must be a valid date.");
        }
    }
}
