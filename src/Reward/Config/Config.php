<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Mothership\ReferAFriend\Referral\Type\TypeInterface;
use Message\Cog\Localisation\Translator;

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
	private $_createdAt;

	/**
	 * @var Translator
	 */
	private $_translator;

	private $_constraints;

	private $_triggers;

	public function __construct(Translator $translator)
	{
		$this->_translator = $translator;
	}

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

		if ($this->getName()) {
			$name[] = $this->getName();
		}

		$name[] = $this->_getNameSuffix();

		return implode(' ', $name);
	}

	public function setCreatedAt(\DateTime $createdAt)
	{
		$this->_createdAt = $createdAt;
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


	/**
	 * {@inheritDoc}
	 */
	public function getConstraints()
	{
		return $this->getRewardConfig()->getConstraints();
	}

	/**
	 * {@inheritDoc}
	 */
	public function addConstraint(Constraint\ConstraintInterface $constraint)
	{
		if (false === $this->_type->allowConstraints()) {
			throw new \LogicException('Constraints cannot be set on referrals with a type of `' . $this->_type->getName() . '`');
		}

		$this->_constraints->add($constraint);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTriggers()
	{
		return $this->_triggers;
	}

	/**
	 * {@inheritDoc}
	 */
	public function addTrigger(Trigger\TriggerInterface $trigger)
	{
		if (false === $this->_type->allowTriggers()) {
			throw new \LogicException('Triggers cannot be set on referrals with a type of `' . $this->_type->getName() . '`');
		}

		$this->_triggers->add($trigger);
	}

	private function _getNameSuffix()
	{
		$suffix = $this->getName() ? '(' : '';

		$suffix .= $this->_translator->trans($this->getReferralType()->getDisplayName());

		if (null !== $this->_createdAt) {
			$suffix .= ', ' . $this->_createdAt->format(self::DATE_FORMAT);
		}

		$suffix .= $this->getName() ? ')' : '';

		return $suffix;
	}
}