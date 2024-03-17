<?php

declare(strict_types=1);

namespace layer_1;

include("src/view.php");

session_start();

class Controller
{
	private const DEFAULT_ACTION = 'login';
	private Request $request;
	private View $view;

	public function __construct(Request $request)
	{
		$this->request = $request;
		$this->view = new View();
	}

	public function run(): void		
	{
		if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany'] == true)) {
			header('Location: ../templates/pages/main_menu.php');
		} else {
			$action = $this->action() . 'Action';
			$this->$action(); //wywołanie akcji
		}
	}

	public function loginAction(): void
	{
		$page = 'login';
		$this->view->render($page);
	}

	public function registrationAction(): void
	{
		$page = 'registration';
		$this->view->render($page);
	}

	private function action(): string
	{
		return $this->request->getParam('action', self::DEFAULT_ACTION);
	}
}