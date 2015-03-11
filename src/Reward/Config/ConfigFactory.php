<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Cog\DB\Entity\EntityLoaderCollection;
use Message\Cog\Localisation\Translator;

/**
 * Class ConfigFactory
 * @package Message\Mothership\ReferAFriend\Reward\Config
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Class for generating instances of Config
 */
class ConfigFactory
{
	/**
	 * @var EntityLoaderCollection
	 */
	private $_loaders;

	/**
	 * @var Translator
	 */
	private $_translator;

	public function __construct(EntityLoaderCollection $loaders, Translator $translator)
	{
		$this->_loaders    = $loaders;
		$this->_translator = $translator;
	}

	/**
	 * Create and return a new instance of Config
	 *
	 * @return Config
	 */
	public function getConfig()
	{
		return new Config($this->_translator);
	}

	/**
	 * Create and return a new instance of ConfigProxy, with the entity loaders set
	 *
	 * @return ConfigProxy
	 */
	public function getConfigProxy()
	{
		$config = new ConfigProxy($this->_translator);
		$config->setLoaders($this->_loaders);

		return $config;
	}
}