<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Mothership\ReferAFriend\Reward\Type;
use Message\Cog\Localisation\Translator;

class Config implements ConfigInterface
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
	 * @var Type\TypeInterface
	 */
	private $_type;

	/**
	 * @var string
	 */
	private $_message;

	/**
	 * @var \DateTime
	 */
	private $_createdAt;

	/**
	 * @var Translator
	 */
	private $_translator;

	/**
	 * @var Constraint\Collection
	 */
	protected $_constraints;

	/**
	 * @var Trigger\Collection
	 */
	protected $_triggers;

	/**
	 * @var RewardOption\Collection
	 */
	protected $_rewardOptions;

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

	public function setMessage($message)
	{
		if (!is_string($message)) {
			throw new \InvalidArgumentException('Message must be a string!');
		}

		$this->_message = $message;
	}

	public function getMessage()
	{
		return $this->_message;
	}

	public function setCreatedAt(\DateTime $createdAt)
	{
		$this->_createdAt = $createdAt;
	}

	public function setType(Type\TypeInterface $type)
	{
		$this->_type = $type;
	}

	public function getType()
	{
		if (null === $this->_type) {
			throw new \LogicException('Reward type not set!');
		}

		return $this->_type;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConstraints()
	{
		return $this->_constraints ?: new Constraint\Collection;
	}

	/**
	 * {@inheritDoc}
	 */
	public function addConstraint(Constraint\ConstraintInterface $constraint)
	{
		if (!in_array($constraint->getName(), $this->_type->validConstraints())) {
			throw new \LogicException('Constraints of type `' . $constraint->getName() . '` cannot be set on rewards with a type of `' . $this->_type->getName() . '`');
		}

		if (null === $this->_constraints) {
			$this->_constraints = new Constraint\Collection;
		}

		$this->_constraints->add($constraint);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTriggers()
	{
		return $this->_triggers ?: new Trigger\Collection;
	}

	/**
	 * {@inheritDoc}
	 */
	public function addTrigger(Trigger\TriggerInterface $trigger)
	{
		if (!in_array($trigger->getName(), $this->_type->validTriggers())) {
			throw new \LogicException('Triggers of type `' . $trigger->getName() . '` cannot be set on rewards with a type of `' . $this->_type->getName() . '`');
		}

		if (null === $this->_triggers) {
			$this->_triggers = new Trigger\Collection;
		}

		$this->_triggers->add($trigger);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRewardOptions()
	{
		return $this->_rewardOptions ?: new RewardOption\Collection;
	}

	/**
	 * {@inheritDoc}
	 */
	public function addRewardOption(RewardOption\RewardOptionInterface $rewardOption)
	{
		if (!in_array($rewardOption->getName(), $this->_type->validRewardOptions())) {
			throw new \LogicException('Reward options of type `' . $rewardOption->getName() . '` cannot be set on rewards with a type of `' . $this->_type->getName() . '`');
		}

		if (null === $this->_rewardOptions) {
			$this->_rewardOptions = new RewardOption\Collection;
		}

		$this->_rewardOptions->add($rewardOption);
	}

	private function _getNameSuffix()
	{
		$suffix = $this->getName() ? '(' : '';

		$suffix .= $this->_translator->trans($this->getType()->getDisplayName());

		if (null !== $this->_createdAt) {
			$suffix .= ', ' . $this->_createdAt->format(self::DATE_FORMAT);
		}

		$suffix .= $this->getName() ? ')' : '';

		return $suffix;
	}
}