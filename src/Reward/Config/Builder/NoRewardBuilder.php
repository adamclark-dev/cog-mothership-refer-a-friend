<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Builder;

use Message\Mothership\ReferAFriend\Reward\Config\Config;

class NoRewardBuilder implements BuilderInterface
{
	public function getType()
	{
		return 'no_reward';
	}

	public function build(array $formData)
	{
		$config = new Config;

		if (!empty($formData['name'])) {
			$config->setName($formData['name']);
		}
	}
}