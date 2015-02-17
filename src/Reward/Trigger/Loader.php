<?php

namespace Message\Mothership\ReferAFriend\Reward\Trigger;

use Message\Mothership\ReferAFriend\Reward\EntityLoaderInterface;
use Message\Mothership\ReferAFriend\Reward\RewardInterface;

use Message\Cog\DB\QueryBuilderFactory;

class Loader implements EntityLoaderInterface
{
	final public function __construct(QueryBuilderFactory $qbFactory)
	{
		$this->_qbFactory = $qbFactory;
	}

	public function getName()
	{
		return 'trigger';
	}

	public function load(RewardInterface $reward)
	{

	}
}