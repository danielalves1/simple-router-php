<?php

class Router {

	private $request;
	private $supportedHttpMethods = ["GET", "POST"];

	function __construct (IRequest $request) 
	{
		$this->request = $request;
	}

	function __call($name, $args)
	{	
		list($route, $callback) = $args;

		if (!in_array(strtoupper($name), $this->supportedHttpMethods))
		{
			$this->invalidMethodHandler();
		}

		$this->{strtolower($name)}[$this->formatRoute($route)] = $callback;
	}

	private function formatRoute($route)
	{
		$result = rtrim($route, "/");
		if ($result === "") 
		{
			return "/";
		} 
		return $result;
	}

	private function invalidMethodHandler()
	{
		header("{$this->request->serverProtocol} 405 Method Not Allowed");
	}

	private function defaultRequestHandler()
	{
		header("{$this->request->serverProtocol} 404 Not Found");
	}

	function resolve()
	{
		$methodDictionary = $this->{strtolower($this->request->requestMethod)};
		$formatedRoute = $this->formatRoute($this->request->requestUri);
		$callback = array_key_exists($formatedRoute, $methodDictionary) ? $methodDictionary[$formatedRoute] : null;
		if (is_null($callback))
		{
			$this->defaultRequestHandler();
			return;
		}

		echo call_user_func_array($callback, [$this->request]);
	}

	function __destruct()
	{
		$this->resolve();
	}

}