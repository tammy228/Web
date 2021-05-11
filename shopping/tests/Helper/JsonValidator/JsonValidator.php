<?php


namespace App\Tests\Helper\JsonValidator;


use Opis\JsonSchema\Schema;
use Opis\JsonSchema\ValidationError;
use Opis\JsonSchema\Validator;

class JsonValidator
{
    private $validator;

    private $error;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    public function schema()
    {
        return array();
    }

    public function validate($data) : bool
    {

        $result = $this
            ->validator
            ->schemaValidation($data, Schema::fromJsonString(json_encode($this->schema())));

        $isValid = $result->isValid();

        if(!$isValid)
            $this->error = $result->getFirstError();

        return $isValid;
    }

    /**
     * @return ValidationError
     */
    public function getError()
    {
        return $this->error;
    }
}