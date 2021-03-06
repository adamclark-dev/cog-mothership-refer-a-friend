<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Mothership\ReferAFriend\Reward\Type;
use Message\Mothership\ReferAFriend\Form\RewardConfig;

/**
 * Class ConfigBuilder
 * @package Message\Mothership\ReferAFriend\Reward\Config
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class for building Config instances from form data
 */
class ConfigBuilder
{
	/**
	 * @var ConfigFactory
	 */
	private $_factory;

	/**
	 * @var Constraint\Collection
	 */
	private $_constraints;

	/**
	 * @var Trigger\Collection
	 */
	private $_triggers;

	/**
	 * @var RewardOption\Collection
	 */
	private $_rewardOptions;

	public function __construct(
		ConfigFactory $factory,
		Constraint\Collection $constraints,
		Trigger\Collection $triggers,
		RewardOption\Collection $rewardOptions
	)
	{
		$this->_factory       = $factory;
		$this->_constraints   = $constraints;
		$this->_triggers      = $triggers;
		$this->_rewardOptions = $rewardOptions;
	}

	/**
	 * Build a Config instance using form data and the reward type
	 *
	 * @param Type\TypeInterface $type
	 * @param array $formData
	 *
	 * @return Config
	 */
	public function build(Type\TypeInterface $type, array $formData)
	{
		$config = $this->_factory->getConfig();
		$config->setType($type);

		if (!empty($formData['name'])) {
			$config->setName($formData['name']);
		}

		$config->setMessage($formData['message']);

		$this->_addConstraints($config, $formData);
		$this->_addTriggers($config, $formData);
		$this->_addRewardOptions($config, $formData);

		return $config;
	}

	/**
	 * Load and populate constraints with values, and add them to the Config
	 *
	 * @param Config $config
	 *
	 * @param array $formData
	 */
	private function _addConstraints(Config $config, array $formData)
	{
		if (empty($formData['constraints'])) {
			return;
		}

		$constraintData = $formData['constraints'];

		if (!is_array($constraintData)) {
			$type = gettype($constraintData) === 'object' ? get_class($constraintData) : gettype($constraintData);
			throw new \LogicException('Constraint form data must be an array, ' . $type . ' given');
		}

		foreach ($constraintData as $name => $value) {

			if (null === $value) {
				continue;
			}

			$constraint = clone $this->_constraints->get($name);
			$constraint->setValue($value);
			$config->addConstraint($constraint);
		}
	}

	/**
	 * Load trigger and add it to the Config
	 *
	 * @param Config $config
	 *
	 * @param array $formData
	 */
	private function _addTriggers(Config $config, array $formData)
	{
		if (empty($formData['triggers']) || $formData['triggers'] === RewardConfig::NONE) {
			return;
		}

		$trigger = $this->_triggers->get($formData['triggers']);
		$config->addTrigger($trigger);
	}

	/**
	 * Load and populate reward options and add them to the Config
	 *
	 * @param Config $config
	 *
	 * @param array $formData
	 */
	private function _addRewardOptions(Config $config, array $formData)
	{
		if (empty($formData['reward_options'])) {
			return;
		}

		$rewardOptionsData = $formData['reward_options'];

		if (!is_array($rewardOptionsData)) {
			$type = gettype($rewardOptionsData) === 'object' ? get_class($rewardOptionsData) : gettype($rewardOptionsData);
			throw new \LogicException('Reward option form data must be an array, ' . $type . ' given');
		}

		foreach ($rewardOptionsData as $name => $value) {
			if (null === $value) {
				continue;
			}

			$rewardOption = clone $this->_rewardOptions->get($name);
			$rewardOption->setValue($value);
			$config->addRewardOption($rewardOption);
		}
	}
}