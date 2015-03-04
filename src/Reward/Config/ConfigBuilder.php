<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Mothership\ReferAFriend\Reward\Type;
use Message\Mothership\ReferAFriend\Form\RewardOptions;

class ConfigBuilder
{
	private $_factory;
	private $_constraints;
	private $_triggers;

	public function __construct(ConfigFactory $factory, Constraint\Collection $constraints, Trigger\Collection $triggers)
	{
		$this->_factory     = $factory;
		$this->_constraints = $constraints;
		$this->_triggers    = $triggers;
	}

	public function build(Type\TypeInterface $type, array $formData)
	{
		$config = $this->_factory->getConfig();
		$config->setType($type);

		if (!empty($formData['name'])) {
			$config->setName($formData['name']);
		}

		$this->_addConstraints($config, $formData);
		$this->_addTriggers($config, $formData);

		return $config;
	}

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

	private function _addTriggers(Config $config, array $formData)
	{
		if (empty($formData['triggers']) || $formData['triggers'] === RewardOptions::NONE) {
			return;
		}

		$trigger = $this->_triggers->get($formData['triggers']);
		$config->addTrigger($trigger);
	}
}