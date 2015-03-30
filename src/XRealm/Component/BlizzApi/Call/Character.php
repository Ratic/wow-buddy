<?php
namespace XRealm\Component\BlizzApi\Call;

use XRealm\Component\BlizzApi\Call\Call;

class Character extends Call {
    public function __construct()
    {
        $this->url = '/character/{realm}/{character}';
    }
}