<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\RewardOption;

use Message\Mothership\ReferAFriend\Reward\RewardEntityInterface;
use Symfony\Component\Form\FormBuilderInterface;

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