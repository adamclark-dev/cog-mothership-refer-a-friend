<?php

namespace Message\Mothership\ReferAFriend\Bootstrap;

use Message\Cog\Bootstrap\RoutesInterface;

/**
 * Class Routes
 * @package Message\Mothership\ReferAFriend\Bootstrap
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 */
class Routes implements RoutesInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function registerRoutes($router)
	{
		// Control panel
		$router['ms.cp.refer_a_friend']->setParent('ms.cp')->setPrefix('/refer-a-friend');
		$router['ms.cp.refer_a_friend']->add('ms.cp.refer_a_friend.dashboard', '/', 'Message:Mothership:ReferAFriend::Controller:Dashboard#index');
		$router['ms.cp.refer_a_friend']->add('ms.cp.refer_a_friend.create', '/create', 'Message:Mothership:ReferAFriend::Controller:Reward#create');
		$router['ms.cp.refer_a_friend']->add('ms.cp.refer_a_friend.set_options_action', '/options/{type}', 'Message:Mothership:ReferAFriend::Controller:Reward#setOptionsAction')
			->setRequirement('type', '[a-z0-9\-_\/]+')
			->setMethod('POST')
		;
		$router['ms.cp.refer_a_friend']->add('ms.cp.refer_a_friend.set_options', '/options/{type}', 'Message:Mothership:ReferAFriend::Controller:Reward#setOptions')
			->setRequirement('type', '[a-z0-9\-_\/]+')
			->setMethod('GET')
		;
		$router['ms.cp.refer_a_friend']->add('ms.cp.refer_a_friend.view_config', '/config/view/{configID}', 'Message:Mothership:ReferAFriend::Controller:Reward#viewConfig')
			->setRequirement('type', '\d+')
		;

		// Website front end
		$router['ms.refer_a_friend']->setPrefix('/')->setPriority(-400);
		$router['ms.refer_a_friend']->add('ms.refer_a_friend.refer_action', '/refer-a-friend/submit', 'Message:Mothership:ReferAFriend::Controller:Referral#referAFriendAction')
			->setMethod('POST');
	}
}