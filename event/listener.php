<?php
/**
 *
 * @package customusertitle
 * @copyright (c) 2015 David King (imkingdavid)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace imkingdavid\customusertitle\event;

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	const CUSTOM_USER_TITLE_BEFORE_RANK = 0;
	const CUSTOM_USER_TITLE_REPLACES_RANK = 1;
	const CUSTOM_USER_TITLE_AFTER_RANK = 2;

	/**
	 * User object
	 * @var \phpbb\user
	 */
	protected $user;

	/**
	 * Config object
	 * @var \phpbb\config\config
	 */
	protected $config;

	/**
	 * Auth object
	 * @var \phpbb\auth\auth
	 */
	protected $auth;

	/**
	 * Template object
	 * @var \phpbb\template\template
	 */
	protected $template;

	/**
	 * Request object
	 * @var \phpbb\requeest\requeest
	 */
	protected $requeest;

	/**
	 * Constructor
	 *
	 * @param \phpbb\user User object
	 */
	public function __construct(\phpbb\user $user, \phpbb\config\config $config, \phpbb\auth\auth $auth, \phpbb\template\template $template, \phpbb\request\request $request)
	{
		$this->user = $user;
		$this->config = $config;
		$this->auth = $auth;
		$this->template = $template;
		$this->request = $request;
	}

	/**
	 * Get subscribed events
	 *
	 * @return array
	 * @static
	 */
	static public function getSubscribedEvents()
	{
		return [
			// phpBB Core Events
			'core.viewtopic_modify_post_row'		=> 'show_custom_user_title_viewtopic',
			'core.viewtopic_cache_user_data'		=> 'get_custom_user_title_viewtopic',
			'core.memberlist_view_profile'			=> 'show_custom_user_title_profile',
			'core.ucp_pm_view_messsage'				=> 'show_custom_user_title_privmsg',
			'core.permissions'						=> 'add_permission',
			'core.acp_board_config_edit_add'		=> 'acp_board_settings',
			'core.ucp_profile_modify_profile_info'	=> 'ucp_setting_show',
			'core.ucp_profile_info_modify_sql_ary'	=> 'ucp_setting_update',
		];
	}

	/**
	 * @param Event $event Event object
	 */
	public function show_custom_user_title_viewtopic($event)
	{
		$user_title = $event['user_poster_data']['user_custom_title'];

		// leave if we don't have a user title to use or if the user doesn't have
		// permission to have a user title
		if (!$user_title || !$this->auth->acl_get('u_user_custom_title'))
		{
			return;
		}

		$post_row = $event['post_row'];


		$post_row['S_CUSTOM_USER_TITLE_BEFORE_RANK'] = (int) $this->config['customusertitle_location'] === self::CUSTOM_USER_TITLE_BEFORE_RANK;
		$post_row['S_CUSTOM_USER_TITLE_REPLACES_RANK'] = (int) $this->config['customusertitle_location'] === self::CUSTOM_USER_TITLE_REPLACES_RANK;
		$post_row['S_CUSTOM_USER_TITLE_AFTER_RANK'] = (int) $this->config['customusertitle_location'] === self::CUSTOM_USER_TITLE_AFTER_RANK;
		$post_row['CUSTOM_USER_TITLE'] = $user_title;

		if ((int) $this->config['customusertitle_location'] === self::CUSTOM_USER_TITLE_REPLACES_RANK)
		{
			$post_row['RANK_TITLE'] = '';

			// To ensure consistency if other extensions decide to use the cached value
			// let's remove it too.
			$user_poster_data = $event['user_poster_data'];
			$user_poster_data['rank_title'] = '';
			$event['user_poster_data'] = $user_poster_data;
		}

		$event['post_row'] = $post_row;
	}

	public function get_custom_user_title_viewtopic($event)
	{
		$user_cache_data = $event['user_cache_data'];
		$user_cache_data['user_custom_title'] = $event['row']['user_custom_title'];
		$event['user_cache_data'] = $user_cache_data;
	}

	/**
	 * @param Event $event Event object
	 */
	public function show_custom_user_title_profile($event)
	{
		$user_title = $event['member']['user_custom_title'];

		if (!$user_title)
		{
			return;
		}

		// We'll borrow from the UCP language file
		// Because I don't want to make a whole new one just to
		// define a single variable.
		$this->user->add_lang_ext('imkingdavid/customusertitle', 'customusertitle_ucp');

		$this->template->assign_vars([
			'S_CUSTOM_USER_TITLE_BEFORE_RANK' => (int) $this->config['customusertitle_location'] === self::CUSTOM_USER_TITLE_BEFORE_RANK,
			'S_CUSTOM_USER_TITLE_REPLACES_RANK' => (int) $this->config['customusertitle_location'] === self::CUSTOM_USER_TITLE_REPLACES_RANK,
			'S_CUSTOM_USER_TITLE_AFTER_RANK' => (int) $this->config['customusertitle_location'] === self::CUSTOM_USER_TITLE_AFTER_RANK,
			'CUSTOM_USER_TITLE' => $user_title,
		]);

		if ((int) $this->config['customusertitle_location'] === self::CUSTOM_USER_TITLE_REPLACES_RANK)
		{
			$member = $event['member'];
			$member['user_rank'] = '';
			$event['member'] = $member;
		}
	}

	/**
	 * @param Event $event Event object
	 */
	public function show_custom_user_title_privmsg($event)
	{
		$user_title = $event['user_info']['user_custom_title'];

		if (!$user_title)
		{
			return;
		}

		// We'll borrow from the UCP language file
		// Because I don't want to make a whole new one just to
		// define a single variable.
		$this->user->add_lang_ext('imkingdavid/customusertitle', 'customusertitle_ucp');

		$this->template->assign_vars([
			'S_CUSTOM_USER_TITLE_BEFORE_RANK' => (int) $this->config['customusertitle_location'] === self::CUSTOM_USER_TITLE_BEFORE_RANK,
			'S_CUSTOM_USER_TITLE_REPLACES_RANK' => (int) $this->config['customusertitle_location'] === self::CUSTOM_USER_TITLE_REPLACES_RANK,
			'S_CUSTOM_USER_TITLE_AFTER_RANK' => (int) $this->config['customusertitle_location'] === self::CUSTOM_USER_TITLE_AFTER_RANK,
			'CUSTOM_USER_TITLE' => $user_title,
		]);

		if ((int) $this->config['customusertitle_location'] === self::CUSTOM_USER_TITLE_REPLACES_RANK)
		{
			$msg_data = $event['msg_data'];
			$msg_data['RANK_TITLE'] = '';
			$event['msg_data'] = $msg_data;
		}
	}

	/**
	 * Set UCP setting
	 *
	 * @param Event $event The event object
	 * @return null
	 * @access public
	 */
	public function ucp_setting_show($event)
	{
		if (!$this->auth->acl_get('u_user_custom_title'))
		{
			return;
		}

		if ($event['submit'])
		{
			$data = $event['data'];
			$data['user_custom_title'] = $this->request->variable('custom_user_title', '');
			$event['data'] = $data;
		}

		$this->user->add_lang_ext('imkingdavid/customusertitle', 'customusertitle_ucp');
		$this->template->assign_vars([
			'CUSTOM_USER_TITLE' => $this->user->data['user_custom_title'],
			'S_CUSTOM_USER_TITLE' => true,
		]);
	}

		/**
	 * Set UCP setting
	 *
	 * @param Event $event The event object
	 * @return null
	 * @access public
	 */
	public function ucp_setting_update($event)
	{
		if (!$this->auth->acl_get('u_user_custom_title'))
		{
			return;
		}

		if (isset($event['data']['user_custom_title']))
		{
			$sql_ary = $event['sql_ary'];
			$sql_ary['user_custom_title'] = $event['data']['user_custom_title'];
			$event['sql_ary'] = $sql_ary;
		}
	}

	/**
	 * Set ACP board settings
	 *
	 * @param Event $event The event object
	 * @return null
	 * @access public
	 */
	public function acp_board_settings($event)
	{
		if ($event['mode'] == 'features')
		{
			$this->modify_acp_display_vars($event);
			$this->user->add_lang_ext('imkingdavid/customusertitle', 'customusertitle_acp');
		}
	}

	/**
	 * Add administrative permissions to manage board rules
	 *
	 * @param object $event The event object
	 * @return null
	 * @access public
	 */
	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$permissions['u_user_custom_title'] = array('lang' => 'ACL_U_USER_CUSTOM_TITLE', 'cat' => 'profile');
		$event['permissions'] = $permissions;
	}

	/**
	 * Add custom user title settings to acp settings by modifying the display vars
	 *
	 * @param object $event The event object
	 * @return null
	 * @access public
	 */
	public function modify_acp_display_vars($event)
	{
		$new_display_var = array(
			'title'	=> $event['display_vars']['title'],
			'vars'	=> array(),
		);
		foreach ($event['display_vars']['vars'] as $key => $content)
		{
			$new_display_var['vars'][$key] = $content;
			if ($key == 'allow_quick_reply')
			{
				$new_display_var['vars']['customusertitle_location'] = array(
					'lang'		=> 'CUSTOM_USER_TITLE_LOCATION',
					'validate'	=> 'int',
					'type'		=> 'custom',
					'function'	=> array('imkingdavid\customusertitle\event\listener', 'customusertitle_settings'),
					'explain' 	=> true,
				);
			}
		}
		$event->offsetSet('display_vars', $new_display_var);
	}

	/**
	 * Custom user title location setting
	 *
	 * @param int $value Value of location setting. 0 = before rank, 1 = replaces rank, 2 = after rank
	 * @param string $key The key of the setting
	 * @return string HTML for quickedit settings
	 * @access public
	 */
	static public function customusertitle_settings($value, $key)
	{
		// Called statically so can't use $this->user
		global $user;
		$user->add_lang_ext('imkingdavid/customusertitle', 'customusertitle_acp');
		$radio_ary = [
			0 => 'CUSTOM_USER_TITLE_BEFORE_RANK',
			1 => 'CUSTOM_USER_TITLE_REPLACES_RANK',
			2 => 'CUSTOM_USER_TITLE_AFTER_RANK',
		];
		return h_radio('config[customusertitle_location]', $radio_ary, $value);
	}
}
