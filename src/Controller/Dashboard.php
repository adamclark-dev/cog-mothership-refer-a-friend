<?php

namespace Message\Mothership\ReferAFriend\Controller;

use Message\Cog\Controller\Controller;

class Dashboard extends Controller
{
	public function index()
	{
		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:dashboard');
	}
}