<?php

namespace Message\Mothership\ReferAFriend\Referral;

/**
 * Class AbstractEntityCollectionBuilder
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 */
abstract class AbstractEntityCollectionBuilder implements EntityCollectionBuilderInterface
{
	/**
	 * @var ReferralEntityCollectionInterface
	 */
	protected $_completeCollection;

	public function __construct(ReferralEntityCollectionInterface $completeCollection)
	{
		$this->_completeCollection = $completeCollection;
	}
}