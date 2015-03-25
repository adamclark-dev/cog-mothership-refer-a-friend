<?php

namespace Message\Mothership\ReferAFriend\Reward;

/**
 * Interface EntityCollectionBuilderInterface
 * @package Message\Mothership\ReferAFriend\Reward
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Interface representing an entity collection builder. This creates a collection of reward entities depending on the type
 * of reward the configuration pertains to
 */
interface EntityCollectionBuilderInterface
{
	/**
	 * Get all registered entities of a certain type
	 *
	 * @return EntityCollectionInterface
	 */
	public function getCollection();

	/**
	 * Get registered entities that apply to a specific reward type
	 *
	 * @param Type\TypeInterface $type
	 *
	 * @return EntityCollectionInterface
	 */
	public function getCollectionFromType(Type\TypeInterface $type);
}