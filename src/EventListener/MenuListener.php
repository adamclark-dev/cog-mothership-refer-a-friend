<?php

namespace Message\Mothership\ReferAFriend\EventListener;

use Message\Cog\Event\EventListener;
use Message\Cog\Event\SubscriberInterface;
use Message\Mothership\ControlPanel\Event\BuildMenuEvent;

/**
 * Class MenuListener
 * @package Message\Mothership\ReferAFriend\EventListener
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 */
class MenuListener extends EventListener implements SubscriberInterface
{
	/**
	 * {@inheritDoc}
	 */
	static public function getSubscribedEvents()
	{
		return [
			BuildMenuEvent::BUILD_MAIN_MENU => [
				'buildMenuTabs',
			]
		];
	}

	/**
	 * Add `Refer a Friend` to main admin panel menu
	 *
	 * @param BuildMenuEvent $event
	 */
	public function buildMenuTabs(BuildMenuEvent $event)
	{
		$event->addItem('ms.cp.refer_a_friend.dashboard', 'Refer a Friend', ['ms.cp.refer_a_friend']);
	}
}