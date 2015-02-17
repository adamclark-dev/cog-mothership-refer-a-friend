<?php

namespace Message\Mothership\ReferAFriend\Reward;

use Message\Cog\DB\Entity\EntityLoaderCollection;

/**
 * Class RewardProxy
 * @package Message\Mothership\ReferAFriend\Reward
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 *
 * Class for handling lazy loading of constraints and triggers on rewards
 */
class RewardProxy extends Reward
{
	/**
	 * @var EntityLoaderCollection
	 */
	private $_loaders;

	public function setLoaders(EntityLoaderCollection $loaders)
	{
		$this->_loaders = $loaders;
	}

	/**
	 * Load constraints on request
	 *
	 * @return Constraint\Collection
	 */
	public function getConstraints()
	{
		if (parent::getConstraints()->count() === 0) {
			$constraints = $this->_loaders->get('constraint')
				->load($this);

			foreach ($constraints as $constraint) {
				$this->addConstraint($constraint);
			}
		}

		return parent::getConstraints();
	}

	/**
	 * Load triggers on request
	 *
	 * @return Trigger\Collection
	 */
	public function getTriggers()
	{
		if (parent::getTriggers()->count() === 0) {
			$triggers = $this->_loaders->get('trigger')
				->load($this);

			foreach ($triggers as $trigger) {
				$this->addTrigger($trigger);
			}
		}

		return parent::getTriggers();
	}
}