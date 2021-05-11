<?php


namespace App\Tests\Helper\JsonValidator\UserController;

use App\Tests\Helper\JsonValidator\JsonValidator;

class ListUserResponseValidator extends JsonValidator
{
    public function schema()
    {
        return array(
            "type" => "object",
            "additionalProperties" => false,
            "properties" => array(
                "max_page" => array("type" => "integer"),
                "count" => array("type" => "integer"),
                "limit" => array("type" => "integer"),
                "page" => array("type" => "integer"),
                "data" => array(
                    "type" => "array",
                    "uniqueItems" => true,
                    "items" => array(
                        "type" => "object",
                        "additionalProperties" => false,
                        "properties" => array(
                            "uuid" => array(
                                "type" => "string",
                                "pattern" => "^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-4[0-9A-Fa-f]{3}-[89ABab][0-9A-Fa-f]{3}-[0-9A-Fa-f]{12}$"
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
                    )
                )
            )
        );
    }
}