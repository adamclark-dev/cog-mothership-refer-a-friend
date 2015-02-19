<?php

namespace Message\Mothership\ReferAFriend\Controller;

use Message\Cog\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;

class Create extends Controller
{
	public function create()
	{
		$form = $this->createForm($this->get('refer.form.type_select'));

		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:create', [
			'form'          => $form,
			'referralTypes' => $this->get('refer.referral.types'),
		]);
	}

	public function setOptions($type)
	{
		if (!$this->get('refer.referral.types')->exists($type)) {
			throw new HttpException(404, 'No referral with type `' . $type . '` found!');
		}

		$form = $this->createForm($this->get('refer.referral.types')->get($type)->getForm());

		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:set_options', [
			'form' => $form,
		]);
	}
}