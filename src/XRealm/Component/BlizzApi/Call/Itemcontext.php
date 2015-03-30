<?php
namespace XRealm\Component\BlizzApi\Call;

use XRealm\Component\BlizzApi\Call\Call;

class Itemcontext extends Call {
    public function __construct()
    {
        $this->url = '/item/{item}/{context}';
    }
}