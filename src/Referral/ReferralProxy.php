<?php

namespace Message\Mothership\ReferAFriend\Referral;

use Message\Cog\DB\Entity\EntityLoaderCollection;

/**
 * Class ReferralProxy
 * @package Message\Mothership\ReferAFriend\Referral
 *
 * @author Thomas Marchant <thomas@message.co.uk>
 *
 * Class for handling lazy loading of constraints and triggers on referrals
 */
class ReferralProxy extends Referral
{
	/**
	 * @var EntityLoaderCollection
	 */
	private $_loaders;

	/**
	 * @var int
	 */
	private $_referrerID;

	/**
	 * @param EntityLoaderCollection $loaders
	 */
	public function setLoaders(EntityLoaderCollection $loaders)
	{
		$this->_loaders = $loaders;
	}

	/**
	 * Set the user ID of the referrer
	 *
	 * @param $id
	 * @throws \LogicException
	 */
	public function setReferrerID($id)
	{
		$id = (int) $id;

		if (0 === $id) {
			throw new \LogicException('Referrer cannot have an ID of zero!');
		}

		$this->_referrerID = $id;
	}

	/**
	 * Get the user ID of the referrer
	 *
	 * @return int
	 */
	public function getReferrerID()
	{
		return $this->_referrerID;
	}

	/**
	 * Load constraints on request
	 *
	 * @return Constraint\Collection
	 */
	public function getConstraints()
	{
		if (parent::getConstraints()->count() === 0) {
			$constraints = $this->_loaders->get('constraint')->load($this);

			foreach ($constraints as $constraint) {
				$this->addConstraint($constraint);
			}
		}

		return parent::getConstraints();
	}

	/**
	 * Load triggers on request
	 *
	 * @return Trigger\Collection
	 */
	public function getTriggers()
	{
		if (parent::getTriggers()->count() === 0) {
			$triggers = $this->_loaders->get('trigger')->load($this);

			foreach ($triggers as $trigger) {
				$this->addTrigger($trigger);
			}
		}

		return parent::getTriggers();
	}

	/**
	 * Load referrer on request
	 *
	 * @throws \LogicException               Throws exception if no user can be found
	 *
	 * @return \Message\User\UserInterface
	 */
	public function getReferrer()
	{
		if (null === $this->_referrer) {
			if (null === $this->_referrerID) {
				throw new \LogicException('No referrer ID set on ReferralProxy object!');
			}

			$referrer = $this->_loaders->get('referrer')->load($this);

			if (!$referrer) {
				throw new \LogicException('Could not load referrer user with ID `' . $this->_referrerID . '`');
			}

			$this->setReferrer($referrer);
		}

		return parent::getReferrer();
	}
}