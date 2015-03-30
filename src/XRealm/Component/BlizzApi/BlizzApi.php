<?php
namespace XRealm\Component\BlizzApi;

use Lsw\ApiCallerBundle\Call\HttpGetJson;
use Lsw\ApiCallerBundle\Caller\ApiCallerInterface;
use XRealm\Component\BlizzApi\Call\CallInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class BlizzApi {

	protected $api;
	private $host;

	public function __construct(ApiCallerInterface $api)
	{
		$this->api = $api;
		$this->host = 'http://eu.battle.net/api/wow';
	}

	public function get($name, $parameters = array())
	{
		$namespace = __NAMESPACE__;
		$class = $namespace . '\\Call\\' . ucfirst($name);
		$object = new $class;
		$object->setParameters($parameters);
		return $this->call($object);
	}

	protected function call(CallInterface $call)
	{
		$data = (array) $this->api->call(new HttpGetJson($call->getUrl($this->host), $call->getParameters()));
		return $data;
	}

}