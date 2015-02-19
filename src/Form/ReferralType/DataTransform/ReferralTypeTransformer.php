<?php

namespace Message\Mothership\ReferAFriend\Form\ReferralType\DataTransform;

use Message\Mothership\ReferAFriend\Referral\Type\Collection as ReferralTypes;
use Message\Mothership\ReferAFriend\Referral\Type\TypeInterface;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ReferralTypeTransformer implements DataTransformerInterface
{
	private $_referralTypes;

	public function __construct(ReferralTypes $referralTypes)
	{
		$this->_referralTypes = $referralTypes;
	}

	public function transform($referralType)
	{
		if (!$referralType instanceof TypeInterface) {
			throw new TransformationFailedException('Data can only transform an instance of TypeInterface');
		}

		return $referralType->getName();
	}

	public function reverseTransform($referralType)
	{
		return $this->_referralTypes->get($referralType);
	}
}