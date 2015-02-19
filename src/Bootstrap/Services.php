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
		$services['refer.referral_factory'] = function($c) {
			return new ReferAFriend\Referral\ReferralFactory(
				$c['refer.referral.types'],
				$c['refer.referral.entity_loaders']
			);
		};

		$services['refer.referral.loader'] = function($c) {
			return new ReferAFriend\Referral\Loader($c['db.query.builder.factory'], $c['refer.referral_factory'], $c['refer.referral.entity_loaders']);
		};

		$services['refer.referral.types'] = function($c) {
			return new ReferAFriend\Referral\Type\Collection([
				$c['refer.referral.types.no_reward'],
			]);
		};

		$services['refer.referral.types.no_reward'] = function($c) {
			return new ReferAFriend\Referral\Type\NoRewardType($c['refer.form.referral.no_reward']);
		};

		$services['refer.form.referral.no_reward'] = function($c) {
			return new ReferAFriend\Form\ReferralType\NoReward;
		};

		$services['refer.form.type_select'] = function($c) {
			return new ReferAFriend\Form\TypeSelect($c['refer.referral.types'], $c['translator']);
		};

		$services['refer.referral.entity_loaders'] = function($c) {
			return new \Message\Cog\DB\Entity\EntityLoaderCollection([
				$c['refer.referral.constraint_loader']->getName() => $c['refer.referral.constraint_loader'],
				$c['refer.referral.trigger_loader']->getName()    => $c['refer.referral.trigger_loader'],
				$c['refer.referral.referrer_loader']->getName()   => $c['refer.referral.referrer_loader'],
			]);
		};

		$services['refer.referral.constraint_loader'] = function($c) {
			return new ReferAFriend\Referral\Constraint\Loader($c['db.query.builder.factory'], $c['refer.referral.constraints']);
		};

		$services['refer.referral.constraints'] = function($c) {
			return new ReferAFriend\Referral\Constraint\Collection();
		};

		$services['refer.referral.trigger_loader'] = function($c) {
			return new ReferAFriend\Referral\Trigger\Loader($c['db.query.builder.factory'], $c['refer.referral.triggers']);
		};

		$services['refer.referral.triggers'] = function($c) {
			return new ReferAFriend\Referral\Trigger\Collection();
		};

		$services['refer.referral.referrer_loader'] = function($c) {
			return new ReferAFriend\Referral\Referrer\Loader($c['user.loader']);
		};

		$services['refer.reward.config.create'] = function($c) {
			return new ReferAFriend\Reward\Config\Create($c['db.transaction'], $c['user.current']);
		};

		$services['refer.reward.config.delete'] = function($c) {
			return new ReferAFriend\Reward\Config\Delete($c['db.transaction'], $c['user.current']);
		};

		$services['refer.reward.config.loader'] = function($c) {
			return new ReferAFriend\Reward\Config\Loader($c['db.query.builder.factory'], $c['refer.referral.types']);
		};
	}
}