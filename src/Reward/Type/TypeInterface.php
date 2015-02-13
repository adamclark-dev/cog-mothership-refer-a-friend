<?php

namespace Message\Mothership\ReferAFriend\Reward\Type;

use Message\Mothership\ReferAFriend\Reward\Trigger\TriggerInterface;

interface TypeInterface
{
	public function getName();

	public function getDisplayName();

	public function getTriggers();

	public function addTrigger(TriggerInterface $trigger);
}