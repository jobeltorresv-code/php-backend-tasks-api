<?php

class Validator
{
    public static function required($fields, $data)
    {
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Field '$field' is required", 400);
            }
        }
    }
}