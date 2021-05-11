<?php


namespace App\Tests\Helper\JsonValidator\UserController;


use App\Tests\Helper\JsonValidator\JsonValidator;

class FetchUserResponseValidator extends JsonValidator
{
    public function schema()
    {
        return array(
            "type" => "object",
            "additionalProperties" => false,
            "properties" => array(
                "id" => array(
                    "type" => "integer"
                ),
                "uuid" => array(
                    "type" => "string",
                    "pattern" => "^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-4[0-9A-Fa-f]{3}-[89ABab][0-9A-Fa-f]{3}-[0-9A-Fa-f]{12}$"
                ),
                "display_name" => array(
                    "type" => "string",
                    "minLength" => 0
                ),
                "username" => array(
                    "type" => "string",
                    "pattern" => "^[a-zA-Z0-9\.\_\!\#\$\%\^\&\*]*[a-zA-Z\.\_\!\#\$\%\^\&\*]+[a-zA-Z0-9\.\_\!\#\$\%\^\&\*]*$"
                ),
                "email" => array(
                    "type" => "string",
                    "maxLength" => 256,
                    "format" => "email"
                ),
                "is_activated" => array(
                    "type" => "boolean"
                ),
                "create_at" => array(
                    "type" => "string",
                    "pattern" => "^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}\+00:00$"
                ),
                "update_at" => array(
                    "type" => "string",
                    "pattern" => "^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}\+00:00$"
                )
            )
        );
    }
}