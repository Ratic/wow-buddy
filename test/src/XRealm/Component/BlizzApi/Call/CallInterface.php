<?php
namespace XRealm\Component\BlizzApi\Call;

interface CallInterface {
	public function getUrl($host = false);
	public function getParameters();
	public function setParameters($parameters);
}