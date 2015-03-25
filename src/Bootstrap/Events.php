<?php

namespace Message\Mothership\ReferAFriend\Bootstrap;

use Message\Mothership\ReferAFriend\EventListener;
use Message\Cog\Bootstrap\EventsInterface;

/**
 * Class Events
 * @package Message\Mothership\ReferAFriend\Bootstrap
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Register event listeners for Refer a Friend module
 */
class Events implements EventsInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function registerEvents($dispatcher)
	{
		$dispatcher->addSubscriber(new EventListener\MenuListener);
		$dispatcher->addSubscriber(new EventListener\ReferralListener);
		$dispatcher->addSubscriber(new EventListener\SignUpListener);
	}
}