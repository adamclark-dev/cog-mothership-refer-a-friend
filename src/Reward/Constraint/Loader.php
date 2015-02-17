<?php

namespace Message\Mothership\ReferAFriend\Reward\Constraint;

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
		return 'constraint';
	}

	public function load(RewardInterface $reward)
	{

	}
}