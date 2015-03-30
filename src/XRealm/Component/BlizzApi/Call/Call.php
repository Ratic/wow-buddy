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
            $insertedKeys = array();
            foreach($this->parameters as $key => $parameter)
            {
                $keyMatcher = '{' . $key . '}';
                if(strpos($this->url, $keyMatcher))
                {
                    $this->url = str_replace($keyMatcher, $parameter, $this->url);
                    $insertedKeys[] = $key;
                }
            }
            foreach($insertedKeys as $remove)
            {
                unset($this->parameters[$remove]);
            }
            return $this->parameters;
	}

	protected function parseUrl()
	{
		$this->parseParameters();
		return $this->url;
	}

	public function setParameters($parameters)
	{
		$this->parameters = array_merge($this->parameters, $parameters);
		return $this;
	}
}