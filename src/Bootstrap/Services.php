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
				$c['refer.referral.entity_loaders']
			);
		};

		$services['refer.referral.loader'] = function($c) {
			return new ReferAFriend\Referral\Loader($c['db.query.builder.factory'], $c['refer.referral_factory'], $c['refer.referral.entity_loaders']);
		};

		$services['refer.referral.create'] = function($c) {
			return new ReferAFriend\Referral\Create($c['db.query'], $c['user.current']);
		};

		$services['refer.referral.entity_loaders'] = function($c) {
			return new \Message\Cog\DB\Entity\EntityLoaderCollection([
				$c['refer.referral.referrer_loader']->getName()      => $c['refer.referral.referrer_loader'],
				$c['refer.referral.reward_config_loader']->getName() => $c['refer.referral.reward_config_loader'],
			]);
		};

		$services['refer.referral.referrer_loader'] = function($c) {
			return new ReferAFriend\Referral\Referrer\Loader($c['user.loader']);
		};

		$services['refer.referral.reward_config_loader'] = function($c) {
			return new ReferAFriend\Referral\RewardConfig\Loader(
				$c['refer.reward.config.loader']
			);
		};

		$services['refer.referral_builder'] = function($c) {
			return new ReferAFriend\Referral\ReferralBuilder(
				$c['refer.referral_factory'],
				$c['refer.reward.config.loader'],
				$c['user.current']
			);
		};

		$services['refer.validator'] = function($c) {
			return new ReferAFriend\Referral\Validator\Collection([
				$c['refer.validator.user_exists'],
				$c['refer.validator.already_referred'],
			]);
		};

		$services['refer.validator_factory'] = function($c) {
			return new ReferAFriend\Referral\Validator\CollectionFactory($c['refer.validator']);
		};

		$services['refer.validator.already_referred'] = function($c) {
			return new ReferAFriend\Referral\Validator\AlreadyReferred($c['refer.referral.loader']);
		};

		$services['refer.validator.user_exists'] = function($c) {
			return new ReferAFriend\Referral\Validator\UserExists($c['user.loader']);
		};

		$services['refer.form.type_select'] = function($c) {
			return new ReferAFriend\Form\TypeSelect($c['refer.reward.types'], $c['translator']);
		};

		$services['refer.form.reward_options'] = function($c) {
			return new ReferAFriend\Form\RewardOptions(
				$c['refer.reward.config.constraint.collection_builder'],
				$c['refer.reward.config.trigger.collection_builder']
			);
		};

		$services['refer.form.refer_a_friend'] = function($c) {
			return new ReferAFriend\Form\ReferAFriend;
		};

		$services['refer.reward.types'] = function($c) {
			return new ReferAFriend\Reward\Type\Collection([
				$c['refer.reward.types.no_reward'],
			]);
		};

		$services['refer.reward.types.no_reward'] = function($c) {
			return new ReferAFriend\Reward\Type\NoRewardType;
		};

		$services['refer.reward.config.create'] = function($c) {
			return new ReferAFriend\Reward\Config\Create($c['db.query'], $c['user.current']);
		};

		$services['refer.reward.config.delete'] = function($c) {
			return new ReferAFriend\Reward\Config\Delete($c['db.transaction'], $c['user.current']);
		};

		$services['refer.reward.config.loader'] = function($c) {
			return new ReferAFriend\Reward\Config\Loader($c['db.query.builder.factory'], $c['refer.reward.types'], $c['refer.reward.config.factory']);
		};

		$services['refer.reward.config.factory'] = function($c) {
			return new ReferAFriend\Reward\Config\ConfigFactory($c['refer.reward.config.entity_loaders'], $c['translator']);
		};

		$services['refer.reward.config.builder'] = function($c) {
			return new ReferAFriend\Reward\Config\ConfigBuilder($c['refer.reward.config.factory'], $c['refer.reward.config.constraints'], $c['refer.reward.config.triggers']);
		};


		$services['refer.reward.config.entity_loaders'] = function($c) {
			return new \Message\Cog\DB\Entity\EntityLoaderCollection([
				$c['refer.reward.config.constraint_loader']->getName() => $c['refer.reward.config.constraint_loader'],
				$c['refer.reward.config.trigger_loader']->getName()    => $c['refer.reward.config.trigger_loader'],
			]);
		};

		$services['refer.reward.config.constraint_loader'] = function($c) {
			return new ReferAFriend\Reward\Config\Constraint\Loader($c['db.query.builder.factory'], $c['refer.reward.config.constraints']);
		};

		$services['refer.reward.config.constraint_create'] = function($c) {
			return new ReferAFriend\Reward\Config\Constraint\Create($c['db.transaction']);
		};

		$services['refer.reward.config.constraints'] = function($c) {
			return new ReferAFriend\Reward\Config\Constraint\Collection([
				$c['refer.reward.config.constraints.timeout'],
			]);
		};

		$services['refer.reward.config.constraint.collection_builder'] = function($c) {
			return new ReferAFriend\Reward\Config\Constraint\CollectionBuilder($c['refer.reward.config.constraints']);
		};

		$services['refer.reward.config.constraints.timeout'] = function($c) {
			return new ReferAFriend\Reward\Config\Constraint\Constraints\Timeout;
		};

		$services['refer.reward.config.trigger_loader'] = function($c) {
			return new ReferAFriend\Reward\Config\Trigger\Loader($c['db.query.builder.factory'], $c['refer.reward.config.triggers']);
		};

		$services['refer.reward.config.trigger_create'] = function($c) {
			return new ReferAFriend\Reward\Config\Trigger\Create($c['db.transaction']);
		};

		$services['refer.reward.config.triggers'] = function($c) {
			return new ReferAFriend\Reward\Config\Trigger\Collection();
		};

		$services['refer.reward.config.trigger.collection_builder'] = function($c) {
			return new ReferAFriend\Reward\Config\Trigger\CollectionBuilder($c['refer.reward.config.triggers']);
		};

	}
}