<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Cog\DB\Entity\EntityLoaderInterface as BaseInterface;

interface EntityLoaderInterface extends BaseInterface
{
	public function getName();

	public function load(ReferralProxy $referral);
}