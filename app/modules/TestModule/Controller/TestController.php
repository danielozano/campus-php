<?php

namespace TestModule\Controller;

use Framework\Http\Response;

class TestController
{
	public function bye($a = "World")
	{
		return new Response("Goodbye $a");
	}
}