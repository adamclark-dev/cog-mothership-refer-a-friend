<?php

namespace Message\Mothership\ReferAFriend\Referral\Trigger;

use Message\Mothership\ReferAFriend\Referral\EntityLoaderInterface;
use Message\Mothership\ReferAFriend\Referral\ReferralProxy;

use Message\Cog\DB\QueryBuilderFactory;

class Loader implements EntityLoaderInterface
{
	private $_columns;

	public function __construct(QueryBuilderFactory $qbFactory, Collection $triggers)
	{
		$this->_qbFactory = $qbFactory;
		$this->_triggers  = $triggers;
	}

	public function getName()
	{
		return 'trigger';
	}

	public function getType()
	{
		return 'no_referral';
	}

	public function load(ReferralProxy $referral)
	{
		$result = $this->_qbFactory
			->getQueryBuilder()
			->select($this->_columns)
			->from('referral_trigger')
			->where('referral_id = :id?i', ['id' => $referral->getID()])
		;

		$triggers = new Collection;

		foreach ($result as $row) {
			$triggers->add($row->name);
		}
	}

	private function _getSelect()
	{
		return
		;
	}
}