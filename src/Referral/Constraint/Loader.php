<?php

namespace Message\Mothership\ReferAFriend\Referral\Constraint;

use Message\Mothership\ReferAFriend\Referral\EntityLoaderInterface;
use Message\Mothership\ReferAFriend\Referral\ReferralProxy;

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

	public function getType()
	{
		return 'no_referral';
	}

	public function load(ReferralProxy $referral)
	{

	}
}