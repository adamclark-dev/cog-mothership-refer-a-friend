<?php

namespace Message\Mothership\ReferAFriend\Reward;

interface EntityCollectionBuilderInterface
{
	public function getCollection();

	public function getCollectionFromType(Type\TypeInterface $type);
}