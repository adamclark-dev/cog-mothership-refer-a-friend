<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Builder;

use Message\Mothership\ReferAFriend\Reward\Config\Config;
use Message\Cog\Localisation\Translator;

class NoRewardBuilder implements BuilderInterface
{
	private $_translator;

	public function __construct(Translator $translator)
	{
		$this->_translator = $translator;
	}

	public function getType()
	{
		return 'no_reward';
	}

	public function build(array $formData)
	{
		$config = new Config($this->_translator);

		if (!empty($formData['name'])) {
			$config->setName($formData['name']);
		}

		return $config;
	}
}