<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Cog\DB\Entity\EntityLoaderCollection;
use Message\Cog\Localisation\Translator;

/**
 * Class ConfigFactory
 * @package Message\Mothership\ReferAFriend\Reward\Config
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 */
class ConfigFactory
{
	private $_loaders;
	private $_translator;

	public function __construct(EntityLoaderCollection $loaders, Translator $translator)
	{
		$this->_loaders    = $loaders;
		$this->_translator = $translator;
	}

	public function getConfig()
	{
		return new Config($this->_translator);
	}

	public function getConfigProxy()
	{
		$config = new ConfigProxy($this->_translator);
		$config->setLoaders($this->_loaders);

		return $config;
	}
}