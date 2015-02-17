<?php

namespace Message\Mothership\ReferAFriend\Reward;

use Message\Cog\DB\Entity\EntityLoaderCollection;

/**
 * Class RewardFactory
 * @package Message\Mothership\ReferAFriend\Reward
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 */
class RewardFactory
{
	/**
	 * @var Type\Collection
	 */
	private $_types;

	final public function __construct(Type\Collection $types, EntityLoaderCollection $loaders)
	{
		$this->_types   = $types;
		$this->_loaders = $loaders;
	}

	/**
	 * @param $type
	 *
	 * @return Reward
	 */
	public function getReward($type)
	{
		$this->_checkType($type);

		return new Reward($this->_types->get($type));
	}

	/**
	 * @param $type
	 *
	 * @return RewardProxy
	 */
	public function getRewardProxy($type)
	{
		$this->_checkType($type);

		$reward = new RewardProxy($this->_types->get($type));
		$reward->setLoaders($this->_loaders);

		return $reward;
	}

	/**
	 * @param $type
	 *
	 * @throws \InvalidArgumentException
	 */
	private function _checkType($type)
	{
		if (!is_string($type)) {
			$varType = gettype($type) === 'object' ? get_class($type) : gettype($type);
			throw new \InvalidArgumentException('Type must be a string, ' . $varType . ' given');
		}
	}
}