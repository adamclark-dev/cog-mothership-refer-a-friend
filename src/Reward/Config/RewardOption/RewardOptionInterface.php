<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\RewardOption;

use Message\Mothership\ReferAFriend\Reward\RewardEntityInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Interface RewardOptionInterface
 * @package Message\Mothership\ReferAFriend\Reward\Config\RewardOption
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Interface representing a reward option against a reward configuration. These can be used for assigning values to the
 * reward that will be created if the referred user fulfills what is required by the constraints and triggers.
 */
interface RewardOptionInterface extends RewardEntityInterface
{
	/**
	 * @param $value
	 */
	public function setValue($value);

	/**
	 * @return mixed
	 */
	public function getValue();

	/**
	 * @return string | FormBuilderInterface
	 */
	public function getFormType();

	/**
	 * @return array
	 */
	public function getFormOptions();
}