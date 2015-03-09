<?php

namespace Message\Mothership\ReferAFriend\Controller;

use Message\Cog\Controller\Controller;

/**
 * Class Dashboard
 * @package Message\Mothership\ReferAFriend\Controller
 *
 * @author Thomas Marchant <thomas@mothership.ec>
 *
 * Controller for handling data displays in the admin panel
 */
class Dashboard extends Controller
{
	/**
	 * Display dashboard
	 *
	 * @return \Message\Cog\HTTP\Response
	 */
	public function index()
	{
		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:dashboard');
	}

	/**
	 * display the current reward configuration
	 *
	 * @return \Message\Cog\HTTP\Response
	 */
	public function currentConfig()
	{
		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:current_config', [
			'rewardConfig' => $this->get('refer.reward.config.current')
		]);
	}

	/**
	 * Display table of referrals
	 *
	 * @return \Message\Cog\HTTP\Response
	 */
	public function referralTable()
	{
		$referrals = $this->get('refer.referral.loader')->getAll();

		return $this->render('Message:Mothership:ReferAFriend::refer_a_friend:cp:referral_table', [
			'referrals' => $referrals,
		]);
	}
}