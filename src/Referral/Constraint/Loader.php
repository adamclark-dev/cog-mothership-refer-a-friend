<?php

namespace Message\Mothership\ReferAFriend\Referral\Constraint;

use Message\Mothership\ReferAFriend\Referral\EntityLoaderInterface;
use Message\Mothership\ReferAFriend\Referral\ReferralProxy;

use Message\Cog\DB\QueryBuilderFactory;

class Loader implements EntityLoaderInterface
{
	private $_constraints;
	private $_columns = [];

	final public function __construct(QueryBuilderFactory $qbFactory, Collection $constraints)
	{
		$this->_qbFactory   = $qbFactory;
		$this->_constraints = $constraints;
	}

	public function getName()
	{
		return 'constraint';
	}

	public function load(ReferralProxy $referral)
	{
		$result = $this->_qbFactory
			->getQueryBuilder()
			->select($this->_columns)
			->from('referral_constraint')
			->getQuery()
			->run()
		;

		$constraints = new Collection;

		foreach ($result as $row) {
			$constraints->add($this->_constraints->get($row->name));
		}

		return $constraints;

	}

    protected function _getSelect()
    {
        return
        ;
    }
}