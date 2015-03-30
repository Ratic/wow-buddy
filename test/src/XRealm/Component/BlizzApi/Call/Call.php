<?php
namespace XRealm\Component\BlizzApi\Call;

use XRealm\Component\BlizzApi\Call\CallInterface;

abstract class Call implements CallInterface {

	protected $url;
	protected $parameters = array();

	public function getUrl($host = false)
	{
		if($host)
		{
			return $host . $this->parseUrl();
		}
		return $this->parseUrl();
	}
	public function getParameters()
	{
		return $this->parseParameters();
	}

	protected function parseParameters()
	{
		//TODO add parameters with pattern lik {realm} to url and remove them out of url bag
		return $this->parameters;
	}

	protected function parseUrl()
	{
		//TODO add parameters with pattern lik {realm} to url and remove them out of url bag
		return $this->url;
	}

	public function setParameters($parameters)
	{
		$this->parameters = array_merge($this->parameters, $parameters);
		return $this;
	}
}