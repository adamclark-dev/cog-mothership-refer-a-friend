<?php

use Message\Cog\Migration\Adapter\MySQL\Migration;

class _1424275225_SetUpReferAFriendDatabases extends Migration
{
	public function up()
	{
		$this->run("
			CREATE TABLE
				refer_a_friend_referral
				(
					referral_id INT(11) AUTO_INCREMENT,
					reward_config_id INT(11) NOT NULL,
					status VARCHAR(255) NOT NULL,
					referrer_id INT(11) NOT NULL,
					referred_email VARCHAR(255) NOT NULL,
					referred_name VARCHAR(255) NOT NULL,
					created_at INT(11) NOT NULL,
					created_by VARCHAR(255) NOT NULL,
					updated_at INT(11) NOT NULL,
					updated_by VARCHAR(255) NOT NULL,
					deleted_at INT(11) DEFAULT NULL,
					deleted_by VARCHAR(255) DEFAULT NULL,
					PRIMARY KEY (referral_id)
				)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

		");

		$this->run("
			CREATE TABLE
				refer_a_friend_reward_config
				(
					reward_config_id INT(11) AUTO_INCREMENT,
					`name` VARCHAR(255) DEFAULT NULL,
					`type` VARCHAR(255) NOT NULL,
					message LONGTEXT NOT NULL,
					created_at INT(11) NOT NULL,
					created_by VARCHAR(255) NOT NULL,
					deleted_at INT(11) DEFAULT NULL,
					deleted_by VARCHAR(255) DEFAULT NULL,
					PRIMARY KEY (reward_config_id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");

		$this->run("
			CREATE TABLE
				refer_a_friend_reward_trigger
				(
					reward_config_id INT(11) NOT NULL,
					`name` VARCHAR(255) NOT NULL,
					PRIMARY KEY (reward_config_id, `name`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");

		$this->run("
			CREATE TABLE
				refer_a_friend_reward_constraint
				(
					reward_config_id INT(11) NOT NULL,
					`name` VARCHAR(255) NOT NULL,
					`value` VARCHAR(255) NOT NULL,
					PRIMARY KEY (reward_config_id, `name`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");

		$this->run("
			CREATE TABLE
				refer_a_friend_reward_option
				(
					reward_config_id INT(11) NOT NULL,
					`name` VARCHAR(255) NOT NULL,
					`value` VARCHAR(255) NOT NULL,
					PRIMARY KEY (reward_config_id, `name`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8
		");

		$this->run("
			INSERT INTO
				`refer_a_friend_reward_config`
				(
					`name`,
					`type`,
					`message`,
					`created_at`,
					`created_by`
				)
			VALUES
				(
					'Default configuration',
					'no_reward',
					'Hi {%toName%}" . PHP_EOL . PHP_EOL . "You have been referred to {%link%} by {%fromName%}.',
					UNIX_TIMESTAMP(),
					'0'
				);
");
	}

	public function down()
	{
		$this->run("DROP TABLE refer_a_friend_referral");
		$this->run("DROP TABLE refer_a_friend_reward_config");
		$this->run("DROP TABLE refer_a_friend_reward_trigger");
		$this->run("DROP TABLE refer_a_friend_reward_constraint");
		$this->run("DROP TABLE refer_a_friend_reward_option");
	}
}