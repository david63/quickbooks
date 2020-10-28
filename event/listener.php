<?php
/**
*
* @package Quick Link Bookmarks Extension
* @copyright (c) 2020 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\quickbooks\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use phpbb\config\config;
use phpbb\template\template;
use phpbb\controller\helper;
use david63\quickbooks\core\functions;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config*/
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $controller_helper;

	/** @var \david63\quickbooks\core\functions */
	protected $functions;

	/**
	* Constructor for listener
	*
	* @param \phpbb\config\config					$config				Config object
	* @param \phpbb\template\template				$template			Template object
	* @param \phpbb\controller\helper				$controller_helper	Controller helper object
	* @param \david63\quickbooks\core\functions		$functions			Functions for the extension
	*
	* @access public
	*/
	public function __construct(config $config, template $template, helper $controller_helper, functions $functions)
	{
		$this->config				= $config;
		$this->template				= $template;
		$this->controller_helper	= $controller_helper;
		$this->functions			= $functions;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'	=> 'load_language_on_setup',
			'core.page_header'	=> 'page_header',
		);
	}

	/**
	* Load common quick link bookmarks language files during user setup
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function load_language_on_setup($event)
	{
		$lang_set_ext	= $event['lang_set_ext'];
		$lang_set_ext[]	= array(
			'ext_name' => $this->functions->get_ext_namespace(),
			'lang_set' => 'quick_books_common',
		);

		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	* Add the required template variables
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function page_header($event)
	{
		if ($this->config['allow_bookmarks'])
		{
			$this->template->assign_var('U_QUICK_BOOKS', $this->controller_helper->route('david63_quickbooks_main_controller'));
		}
	}
}
