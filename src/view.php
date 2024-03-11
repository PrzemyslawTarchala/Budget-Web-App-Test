<?php

declare(strict_types=1);

namespace layer_1;

class View
{
	public function render($page)
	{
		require_once("templates/layout.php");
	}
}