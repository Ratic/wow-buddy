<?php
namespace XRealm\Component\BlizzApi\Call;

use XRealm\Component\BlizzApi\Call\Call;

class RealmStatus extends Call {
	public function __construct()
	{
		$this->url = '/realm/status';
	}
}