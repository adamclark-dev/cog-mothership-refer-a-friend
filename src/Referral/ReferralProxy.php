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
	 * @var int
	 */
	private $_rewardConfigID;

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
	 * Set the ID for the configuration of the reward
	 *
	 * @param $id
	 * @throws \LogicException
	 */
	public function setRewardConfigID($id)
	{
		$id = (int) $id;

		if (0 === $id) {
			throw new \LogicException('Reward configuration cannot have an ID of zero!');
		}

		$this->_rewardConfigID = $id;
	}

	/**
	 * Get the ID of the configuration for the reward
	 *
	 * @return int
	 */
	public function getRewardConfigID()
	{
		return $this->_rewardConfigID;
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

	/**
	 * @return \Message\Mothership\ReferAFriend\Reward\Config\Config
	 */
	public function getRewardConfig()
	{
		if (null === $this->_rewardConfig) {
			if (null === $this->_rewardConfigID) {
				throw new \LogicException('No reward config ID set on ReferralProxy object!');
			}

			$rewardConfig = $this->_loaders->get('reward_config')->load($this);

			if (!$rewardConfig) {
				throw new \LogicException('Could not load reward config with ID `' . $this->_rewardConfigID . '`');
			}

			$this->setRewardConfig($rewardConfig);
		}

		return parent::getRewardConfig();
	}
}