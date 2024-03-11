<?php

declare(strict_types=1);

namespace layer_1;

class Request 
{
	private array $get = [];
	private array $post = [];
	private array $server = [];

	public function __construct(array $get, array $post, array $server)
	{
		$this->get = $get;
		$this->post = $post;
		$this->server = $server;
	}

	public function getParam(string $paramName, $default = null)
	{
		return $this->get[$paramName] ?? $default;
	}

	public function postParam(string $paramName, $default = null)
	{
		return $this->post[$paramName] ?? $default;
	}
}