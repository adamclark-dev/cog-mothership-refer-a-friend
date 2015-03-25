<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Cog\DB\Entity\EntityLoaderInterface as BaseInterface;

/**
 * Interface EntityLoaderInterface
 * @package Message\Mothership\ReferAFriend\Reward\Config
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Interface for Config entity loaders.
 */
interface EntityLoaderInterface extends BaseInterface
{
	/**
	 * Get the name of the type of configuration entity
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Lazy load the appropriate entities for a particular configuration
	 *
	 * @param ConfigProxy $config
	 */
	public function load(ConfigProxy $config);
}