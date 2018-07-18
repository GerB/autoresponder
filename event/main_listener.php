<?php
/**
 *
 * Auto responder. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, Ger
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace ger\autoresponder\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Auto responder Event listener.
 */
class main_listener implements EventSubscriberInterface
{
    
    private $user;
	private $auth;
	private $db;
    private $autorespond_user_id = 2;
    
    /**
	 * Constructor
	 *
	 * @param \phpbb\user								$user					User object
	 * @param \phpbb\auth\auth							$auth					Auth object
	 * @param \phpbb\db\driver\driver_interface			$db						DB object
	 */
	public function __construct(\phpbb\user $user, \phpbb\auth\auth $auth, \phpbb\db\driver\driver_interface $db)
	{
		$this->user = $user;
		$this->auth = $auth;
		$this->db = $db;
	}
    
	static public function getSubscribedEvents()
	{
		return array(
			'core.posting_modify_submit_post_after'	=> 'post_autorespond_message',
		);
	}

    
	/**
	 * Post a message
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function post_autorespond_message($event)
	{
        $post_text = 'This message is only a placeholder now for prove of concept';
        
        // Only for new topics
        if ($event['mode'] == 'post')
        {
            // Get correct topic id
            $redirect_url = $event['redirect_url'];
            if (strpos($redirect_url, 'sid=') !== false)
            {
                $redirect_url = substr($redirect_url, 0, strpos($redirect_url, 'sid='));
            }
            $topic_id = str_replace('&amp;t=', '', strstr($redirect_url, '&amp;t='));
            
            // Prep posting
            $poll = $uid = $bitfield = $options = '';
            $allow_bbcode = $allow_urls = $allow_smilies = true;
            generate_text_for_storage($post_text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

            $data = array(
                // General Posting Settings
                'forum_id'			 => $event['forum_id'], // The forum ID in which the post will be placed. (int)
                'topic_id'			 => $topic_id, // Post a new topic or in an existing one? Set to 0 to create a new one, if not, specify your topic ID here instead.
                'icon_id'			 => false, // The Icon ID in which the post will be displayed with on the viewforum, set to false for icon_id. (int)
                // Defining Post Options
                'enable_bbcode'		 => true, // Enable BBcode in this post. (bool)
                'enable_smilies'	 => true, // Enabe smilies in this post. (bool)
                'enable_urls'		 => true, // Enable self-parsing URL links in this post. (bool)
                'enable_sig'		 => true, // Enable the signature of the poster to be displayed in the post. (bool)
                // Message Body
                'message'			 => $post_text, // Your text you wish to have submitted. It should pass through generate_text_for_storage() before this. (string)
                'message_md5'		 => md5($post_text), // The md5 hash of your message
                // Values from generate_text_for_storage()
                'bbcode_bitfield'	 => $bitfield, // Value created from the generate_text_for_storage() function.
                'bbcode_uid'		 => $uid, // Value created from the generate_text_for_storage() function.    
                // Other Options
                'post_edit_locked'	 => 0, // Disallow post editing? 1 = Yes, 0 = No
                'topic_title'		 => $event['post_data']['post_subject'],
                'notify_set'		 => true, // (bool)
                'notify'			 => true, // (bool)
                'post_time'			 => 0, // Set a specific time, use 0 to let submit_post() take care of getting the proper time (int)
                'forum_name'		 => $this->get_forum_name($event['forum_id']), // For identifying the name of the forum in a notification email. (string)    // Indexing
                'enable_indexing'	 => true, // Allow indexing the post? (bool)    // 3.0.6
            );
            
            // Post as designated user and then switch back to original one
            $actual_user_id = $this->user->data['user_id'];
            $this->switch_user($this->autorespond_user_id);
            submit_post('reply', $event['post_data']['post_subject'], $this->user->data['username'], POST_NORMAL, $poll, $data);
            $this->switch_user($actual_user_id);   
        }    
	}
    
    /**
	 * Get forum name by id (for notifications)
	 * @param int $id
	 * @return string
	 */
	private function get_forum_name($id)
	{
		$sql = 'SELECT forum_name
				FROM ' . FORUMS_TABLE . '
				WHERE forum_id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		return empty($row['forum_name']) ? '' : $row['forum_name'];
	}
    
 	/**
	 * Switch to the autorespond user
	 * @param int $new_user_id
	 * @return bool
	 */
	private function switch_user($new_user_id)
	{
        if ($this->user->data['user_id'] == $new_user_id)
        {
            // Nothing to do
            return true;
        }
        
        $sql = 'SELECT *
				FROM ' . USERS_TABLE . '
				WHERE user_id = ' . (int) $new_user_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);
        
        $row['is_registered'] = true;
        $this->user->data = array_merge($this->user->data, $row);
        $this->auth->acl($this->user->data);
		
        return true;
	}
}
