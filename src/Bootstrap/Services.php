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

		$services['refer.referral.types'] = function($c) {
			return new ReferAFriend\Referral\Type\Collection([
				$c['refer.referral.types.no_referral'],
			]);
		};

		$services['refer.referral.types.no_referral'] = function($c) {
			return new ReferAFriend\Referral\Type\NoReferralType;
		};

		$services['refer.referral.entity_loaders'] = function($c) {
			return new \Message\Cog\DB\Entity\EntityLoaderCollection([
				$c['refer.referral.constraint_loader']->getName() => $c['refer.referral.constraint_loader'],
				$c['refer.referral.trigger_loader']->getName()    => $c['refer.referral.trigger_loader'],
				$c['refer.referral.referrer_loader']->getName()   => $c['refer.referral.referrer_loader'],
			]);
		};

		$services['refer.referral.constraint_loader'] = function($c) {
			return new ReferAFriend\Referral\Constraint\Loader($c['db.query.builder.factory']);
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
	}
}