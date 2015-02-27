<?php

namespace Message\Mothership\ReferAFriend\Referral;

interface EntityCollectionBuilderInterface
{
	public function getCollection();

	public function getCollectionFromType(Type\TypeInterface $type);
}