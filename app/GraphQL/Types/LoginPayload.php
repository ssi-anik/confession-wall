<?php

namespace App\GraphQL\Types;

class LoginPayload
{
    public $props = [];

    public function __construct ($props = []) {
        $this->props = $props;
    }

    public function __isset ($name) {
        return isset($this->props[$name]);
    }

    public function __get ($name) {
        return $this->props[$name] ?? null;
    }
}