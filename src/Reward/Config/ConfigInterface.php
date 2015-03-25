<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Mothership\ReferAFriend\Reward;

/**
 * Interface ConfigInterface
 * @package Message\Mothership\ReferAFriend\Reward\Config
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 */
interface ConfigInterface
{
	/**
	 * Set the ID of the ConfigInterface
	 *
	 * @param int $id
	 */
	public function setID($id);

	/**
	 * Get the ID of the ConfigInterface
	 *
	 * @return int
	 */
	public function getID();

	/**
	 * Set the name of the ConfigInterface
	 *
	 * @param string $name
	 */
	public function setName($name);

	/**
	 * Get the name of the ConfigInterface, if set
	 *
	 * @return string | null
	 */
	public function getName();

	/**
	 * Get the name with additional information about its type and time of creation
	 *
	 * @return string
	 */
	public function getFullName();

	/**
	 * Set the time the ConfigInterface was created
	 *
	 * @param \DateTime $createdAt
	 */
	public function setCreatedAt(\DateTime $createdAt);

	/**
	 * Set the type of reward the ConfigInterface pertains to
	 *
	 * @param Reward\Type\TypeInterface $type
	 */
	public function setType(Reward\Type\TypeInterface $type);

	/**
	 * Get the type of reward the ConfigInterface pertains to
	 *
	 * @return Reward\Type\TypeInterface
	 */
	public function getType();

	/**
	 * Set the message that will be sent to the referred email address. Uses the translator to parse variables.
	 *
	 * @param string $message
	 */
	public function setMessage($message);

	/**
	 * Get the message that will be sent to the referred email address.
	 *
	 * @return string
	 */
	public function getMessage();

	/**
	 * Get all registered constraints
	 *
	 * @return Constraint\Collection
	 */
	public function getConstraints();

	/**
	 * Register a constraint to the ConfigInterface. Must be unique.
	 *
	 * @param Constraint\ConstraintInterface $constraint
	 */
	public function addConstraint(Constraint\ConstraintInterface $constraint);

	/**
	 * Get all registered triggers.
	 *
	 * @return Trigger\Collection
	 */
	public function getTriggers();

	/**
	 * Register a trigger to the ConfigInterface. Must be unique.
	 *
	 * @param Trigger\TriggerInterface $trigger
	 */
	public function addTrigger(Trigger\TriggerInterface $trigger);

	/**
	 * Get all reward options
	 *
	 * @return RewardOption\Collection
	 */
	public function getRewardOptions();

	/**
	 * Register a reward option to the ConfigInterface. Must be unique.
	 *
	 * @param RewardOption\RewardOptionInterface $rewardOption
	 */
	public function addRewardOption(RewardOption\RewardOptionInterface $rewardOption);
}