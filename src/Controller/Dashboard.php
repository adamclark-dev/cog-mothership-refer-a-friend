<?php

namespace Message\Mothership\ReferAFriend\Controller;

use Message\Cog\Controller\Controller;

class Dashboard extends Controller
{
	public function index()
	{
		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:dashboard');
	}

	public function referralTable()
	{
		$referrals = $this->get('refer.referral.loader')->getAll();

		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:referral_table', [
			'referrals' => $referrals,
		]);
	}
}