<?php

namespace Message\Mothership\ReferAFriend\Bootstrap;

use Message\Cog\Bootstrap\RoutesInterface;

class Routes implements RoutesInterface
{
	public function registerRoutes($router)
	{
		$router['ms.cp.refer_a_friend']->setParent('ms.cp')->setPrefix('/refer-a-friend');
		$router['ms.cp.refer_a_friend']->add('ms.cp.refer_a_friend.dashboard', '/', 'Message:Mothership:ReferAFriend::Controller:Dashboard#index');
	}
}