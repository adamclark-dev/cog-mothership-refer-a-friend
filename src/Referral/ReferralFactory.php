<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Cog\DB\Entity\EntityLoaderCollection;

/**
 * Class ReferralFactory
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 */
class ReferralFactory
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
	 * @return Referral
	 */
	public function getReferral($type)
	{
		$this->_checkType($type);

		return new Referral($this->_types->get($type));
	}

	/**
	 * @param $type
	 *
	 * @return ReferralProxy
	 */
	public function getReferralProxy($type)
	{
		$this->_checkType($type);

		$referral = new ReferralProxy($this->_types->get($type));
		$referral->setLoaders($this->_loaders);

		return $referral;
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