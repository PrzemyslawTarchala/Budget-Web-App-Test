<?php

declare(strict_types=1);

namespace layer_1;

include("src/view.php");

class Controller
{
	private const DEFAULT_ACTION = 'login';
	private Request $request;
	private View $view;

	public function __construct(Request $request){
		$this->request = $request;
		$this->view = new View();
	}

	public function run(): void		
	{
		$action = $this->action() . 'Action';
		
		$this->$action(); //wywołanie akcji
	}

	public function loginAction(): void
	{
		$page = 'login';
		$this->view->render($page);
	}

	private function action(): string
	{
		return $this->request->getParam('action', self::DEFAULT_ACTION);
	}
}