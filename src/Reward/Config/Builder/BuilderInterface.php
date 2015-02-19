<?php

namespace Message\Mothership\ReferAFriend\Reward\Config\Builder;

interface BuilderInterface
{
	public function getType();

	public function build(array $formData);
}