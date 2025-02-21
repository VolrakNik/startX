<?php

namespace Common\Requests;

use Common\Exceptions\Exception;
use Common\Exceptions\ValidationException;

class RequestValidator
{
    private $errors = [];
    private $validatedFields = [];

    /**
     * @param array $fields
     * @param array $values
     * @return void
     */
    public function validate(array $fields, array $values): void
    {
        foreach ($fields as $fieldName => $rules) {
            $fieldValue = $values[$fieldName] ?? null;
            foreach ($rules as $rule) {
                $ruleName = $rule;
                $params = [];

                if (str_contains($rule, ':')) {
                    [$ruleName, $ruleValue] = explode(':', $rule, 2);
                    $params = explode(',', $ruleValue);
                }

                $method = 'validate' . ucfirst($ruleName);
                if (!method_exists($this, $method)) {
                    throw new Exception("Rule '$ruleName' does not exist");
                }

                try {
                    $this->$method($fieldName, $fieldValue, $params);
                } catch (ValidationException $e) {
                    $this->errors[$fieldName][] = $e->getMessage();
                }
            }
            $this->validatedFields[$fieldName] = $fieldValue;
        }

        if (!empty($this->errors)) {
            throw new ValidationException(json_encode($this->errors));
        }
    }

    public function getValidatedFields(): array
    {
        return $this->validatedFields;
    }

    protected function validateRequired(string $fieldName, $fieldValue, array $params = []): void
    {
        if (is_null($fieldValue) || trim((string)$fieldValue) === '') {
            throw new ValidationException($fieldName . " is required");
        }
    }

    protected function validateString(string $fieldName, $fieldValue, array $params = []): void
    {
        if (!is_null($fieldValue) && !is_string($fieldValue)) {
            throw new ValidationException($fieldName . " should be a string");
        }
    }

    protected function validateNumeric(string $fieldName, $fieldValue, array $params = []): void
    {
        if (!is_null($fieldValue) && !is_numeric($fieldValue)) {
            throw new ValidationException($fieldName . " should be a numeric");
        }
    }

    protected function validateInteger(string $fieldName, $fieldValue, array $params = []): void
    {
        if (!is_null($fieldValue) && !is_numeric($fieldValue)) {
            throw new ValidationException($fieldName . " should be an integer");
        }
    }

    protected function validateMin(string $fieldName, $fieldValue, array $params = []): void
    {
        if (!isset($params[0])) {
            throw new Exception("Parameter 'min' must be a number");
        }

        $min = (int)$params[0];
        if (is_string($fieldValue) && mb_strlen($fieldValue) < $min) {
            throw new ValidationException($fieldName . " should be at least $min characters");
        }

        if (is_numeric($fieldValue) && $fieldValue < $min) {
            throw new ValidationException($fieldName . " should be at least $min");
        }
    }

    protected function validateMax(string $fieldName, $fieldValue, array $params = []): void
    {
        if (!isset($params[0])) {
            throw new Exception("Parameter 'max' must be a number");
        }

        $max = (int)$params[0];
        if (is_string($fieldValue) && mb_strlen($fieldValue) > $max) {
            throw new ValidationException($fieldName . " should be at most $max characters");
        }

        if (is_numeric($fieldValue) && $fieldValue > $max) {
            throw new ValidationException($fieldName . " should be at most $max");
        }
    }
}