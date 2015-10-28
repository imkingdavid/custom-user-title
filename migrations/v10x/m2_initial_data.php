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
 * Initial data changes needed for Extension installation
 */
class m2_initial_data extends \phpbb\db\migration\migration
{
	/**
	 * @inheritdoc
	 */
	static public function depends_on()
	{
		return ['\imkingdavid\customusertitle\migrations\v10x\m1_initial_schema'];
	}

	/**
	 * @inheritdoc
	 */
	public function update_data()
	{
		return [
			['permission.add', ['u_user_custom_title']],

			// 0 = before rank, 1 = replaces rank, 2 = after rank
			['config.add', ['customusertitle_location', 0]],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function revert_data()
	{
		return [
			['permission.remove', ['u_user_custom_title']],
			['config.remove', ['customusertitle_location']],
		];
	}
}
