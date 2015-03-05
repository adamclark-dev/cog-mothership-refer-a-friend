<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Cog\DB;
use Message\User\UserInterface;

class Create
{
	private $_query;
	private $_currentUser;

	public function __construct(DB\Query $query, UserInterface $currentUser)
	{
		$this->_query       = $query;
		$this->_currentUser = $currentUser;
	}

	public function save(Config $config)
	{
		$result = $this->_query->run("
			INSERT INTO
				refer_a_friend_reward_config
				(
					`name`,
					`type`,
					message,
					created_at,
					created_by
				)
			VALUES
				(
					:name?s,
					:type?s,
					:message?s,
					:createdAt?d,
					:createdBy?i
				)
			;
		", [
			'name'      => $config->getName(),
			'type'      => $config->getType()->getName(),
			'message'   => $config->getMessage(),
			'createdAt' => new \DateTime,
			'createdBy' => $this->_currentUser->id,
		]);

		$config->setID($result->id());

		return $config;
	}
}