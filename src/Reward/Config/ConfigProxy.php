<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Cog\DB\Entity\EntityLoaderCollection;

class ConfigProxy extends Config
{
	private $_loaders;

	/**
	 * @param EntityLoaderCollection $loaders
	 */
	public function setLoaders(EntityLoaderCollection $loaders)
	{
		$this->_loaders = $loaders;
	}

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
}