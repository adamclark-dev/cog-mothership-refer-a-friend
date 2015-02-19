<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Mothership\ReferAFriend\Referral\Type\TypeInterface;

class Config
{
	const DATE_FORMAT = 'D j M Y';

	/**
	 * @var int
	 */
	private $_id;

	/**
	 * @var string
	 */
	private $_name;

	/**
	 * @var TypeInterface
	 */
	private $_referralType;

	/**
	 * @var \DateTime
	 */
	private $_updatedAt;

	public function setID($id)
	{
		$this->_id = (int) $id;
	}

	public function getID()
	{
		if (null === $this->_id) {
			throw new \LogicException('ID not set!');
		}

		return $this->_id;
	}

	public function setName($name)
	{
		if (empty($name)) {
			return;
		}

		if (!is_string($name)) {
			throw new \InvalidArgumentException('Name must be a string!');
		};

		$this->_name = $name;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function getFullName()
	{
		$name = [];

		if ($this->_name) {
			$name[] = $this->_name;
		}

		$name[] = $this->_getNameSuffix();

		return implode(' ', $name);
	}

	public function setUpdatedAt(\DateTime $updatedAt)
	{
		$this->_updatedAt = $updatedAt;
	}

	public function setReferralType(TypeInterface $type)
	{
		$this->_referralType = $type;
	}

	public function getReferralType()
	{
		if (null === $this->_referralType) {
			throw new \LogicException('Referral type not set!');
		}

		return $this->_referralType;
	}

	private function _getNameSuffix()
	{
		$suffix = '(';

		$suffix .= $this->getReferralType()->getDisplayName();

		if (null !== $this->_updatedAt) {
			$suffix .= ', ' . $this->_updatedAt->format(self::DATE_FORMAT);
		}

		$suffix .= ')';

		return $suffix;
	}
}