<?php

namespace Message\Mothership\ReferAFriend\Bootstrap;

use Message\Mothership\ReferAFriend;
use Message\Cog\Bootstrap\ServicesInterface;

/**
 * Class Services
 * @package Message\Mothership\ReferAFriend\Bootstrap
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 */
class Services implements ServicesInterface
{
	public function registerServices($services)
	{
		$services['refer.reward_factory'] = function($c) {
			return new ReferAFriend\Reward\RewardFactory(
				$c['refer.reward.types'],
				$c['refer.reward.entity_loaders']
			);
		};

		$services['refer.reward.types'] = function($c) {
			return new ReferAFriend\Reward\Type\Collection([
				$c['refer.reward.types.no_reward'],
			]);
		};

		$services['refer.reward.types.no_reward'] = function($c) {
			return new ReferAFriend\Reward\Type\NoRewardType;
		};

		$services['refer.reward.entity_loaders'] = function($c) {
			return new \Message\Cog\DB\Entity\EntityLoaderCollection([
				$c['refer.reward.constraint_loader']->getName() => $c['refer.reward.constraint_loader'],
				$c['refer.reward.trigger_loader']->getName()    => $c['refer.reward.trigger_loader'],
			]);
		};

		$services['refer.reward.constraint_loader'] = function($c) {
			return new ReferAFriend\Reward\Constraint\Loader($c['db.query.builder.factory']);
		};

		$services['refer.reward.trigger_loader'] = function($c) {
			return new ReferAFriend\Reward\Trigger\Loader($c['db.query.builder.factory']);
		};
	}
}