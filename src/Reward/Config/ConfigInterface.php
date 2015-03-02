<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Mothership\ReferAFriend\Reward;

interface ConfigInterface
{
	public function setID($id);

	public function getID();

	public function setName($name);

	public function getName();

	public function getFullName();

	public function setCreatedAt(\DateTime $createdAt);

	public function setType(Reward\Type\TypeInterface $type);

	public function getType();

	public function getConstraints();

	public function addConstraint(Constraint\ConstraintInterface $constraint);

	public function getTriggers();

	public function addTrigger(Trigger\TriggerInterface $trigger);
}