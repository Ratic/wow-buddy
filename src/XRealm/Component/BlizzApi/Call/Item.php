<?php
namespace XRealm\Component\BlizzApi\Call;

use XRealm\Component\BlizzApi\Call\Call;

class Item extends Call {
    public function __construct()
    {
        $this->url = '/item/{item}';
    }
}