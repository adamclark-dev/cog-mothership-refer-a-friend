<?php

namespace Message\Mothership\ReferAFriend\Reward\Config;

use Message\Cog\DB\Entity\EntityLoaderInterface as BaseInterface;

interface EntityLoaderInterface extends BaseInterface
{
	public function getName();

	public function load(ConfigProxy $config);
}