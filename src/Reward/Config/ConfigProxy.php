<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Cog\DB\Entity\EntityLoaderCollection;

/**
 * Class ConfigProxy
 * @package Message\Mothership\ReferAFriend\Reward\Config
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Proxy class for lazy loading Config entities (Constraints, Triggers and RewardOptions)
 */
class ConfigProxy extends Config
{
	/**
	 * @var EntityLoaderCollection
	 */
	private $_loaders;

	/**
	 * @param EntityLoaderCollection $loaders
	 */
	public function setLoaders(EntityLoaderCollection $loaders)
	{
		$this->_loaders = $loaders;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConstraints()
	{
		if (null === $this->_constraints) {
			$constraints = $this->_loaders->get('constraint')->load($this);

			if (!$constraints) {
				throw new \LogicException('Could not load constraints!');
			}

			foreach ($constraints as $constraint) {
				$this->addConstraint($constraint);
			}
		}

		return parent::getConstraints();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTriggers()
	{
		if (null === $this->_triggers) {
			$triggers = $this->_loaders->get('trigger')->load($this);

			if (!$triggers) {
				throw new \LogicException('Could not load triggers!');
			}

			foreach ($triggers as $trigger) {
				$this->addTrigger($trigger);
			}
		}

		return parent::getTriggers();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRewardOptions()
	{
		if (null === $this->_rewardOptions) {
			$rewardOptions = $this->_loaders->get('reward_option')->load($this);

			if (!$rewardOptions) {
				throw new \LogicException('Could not load reward options!');
			}

			foreach ($rewardOptions as $rewardOption) {
				$this->addRewardOption($rewardOption);
			}
		}

		return parent::getRewardOptions();
	}
}