<?php
/**
 *
 * @package customusertitle
 * @copyright (c) 2015 David King (imkingdavid)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace imkingdavid\customusertitle\migrations\v10x;

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
 * Initial schema changes needed for Extension installation
 */
class m1_initial_schema extends \phpbb\db\migration\migration
{
	/**
	 * @inheritdoc
	 */
	public function update_schema()
	{
		return [
			'add_columns'        => [
				$this->table_prefix . 'users'        => [
					'user_custom_title'    => ['VCHAR:255', ''],
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function revert_schema()
	{
		return [
			'drop_columns'	=> [
				$this->table_prefix . 'users' => [
					'user_custom_title',
				],
			],
		];
	}
}
