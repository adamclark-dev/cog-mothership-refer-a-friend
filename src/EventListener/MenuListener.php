<?php

namespace Message\Mothership\ReferAFriend\EventListener;

use Message\Cog\Event\EventListener;
use Message\Cog\Event\SubscriberInterface;
use Message\Mothership\ControlPanel\Event\BuildMenuEvent;

class MenuListener extends EventListener implements SubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return [
			BuildMenuEvent::BUILD_MAIN_MENU => [
				'buildMenuTabs',
			]
		];
	}

	public function buildMenuTabs(BuildMenuEvent $event)
	{
		$event->addItem('ms.cp.refer_a_friend.dashboard', 'Refer a Friend', ['ms.cp.refer_a_friend']);
	}
}