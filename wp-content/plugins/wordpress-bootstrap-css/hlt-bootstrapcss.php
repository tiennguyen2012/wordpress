<?php
/*
Plugin Name: WordPress Twitter Bootstrap CSS
Plugin URI: http://www.icontrolwp.com/wordpress-twitter-bootstrap-css-plugin-home/
Description: Link Twitter Bootstrap CSS and Javascript files before all others regardless of your theme.
Version: 2.3.1-2
Author: iControlWP
Author URI: http://icwp.io/v
*/

/**
 * Copyright (c) 2013 iControlWP <support@icontrolwp.com>
 * All rights reserved.
 *
 * "WordPress Twitter Bootstrap CSS" (formerly "WordPress Bootstrap CSS") is
 * distributed under the GNU General Public License, Version 2,
 * June 1991. Copyright (C) 1989, 1991 Free Software Foundation, Inc., 51 Franklin
 * St, Fifth Floor, Boston, MA 02110, USA
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

require_once( dirname(__FILE__).'/src/worpit-plugins-base.php' );
include_once( dirname(__FILE__).'/hlt-bootstrap-shortcodes.php' );
include_once( dirname(__FILE__).'/hlt-bootstrap-less.php' );

function _hlt_e( $insStr ) {
	_e( $insStr, 'hlt-wordpress-bootstrap-css' );
}
function _hlt__( $insStr ) {
	return __( $insStr, 'hlt-wordpress-bootstrap-css' );
}

if ( !class_exists('HLT_BootstrapCss') ):

class HLT_BootstrapCss extends HLT_Plugin {
	
	const InputPrefix				= 'hlt_bootstrap_';
	const OptionPrefix				= 'hlt_bootstrapcss_'; //ALL database options use this as the prefix.
	
	const TwitterVersion			= '2.3.1'; //should reflect the Bootstrap version folder name
	const TwitterVersionLegacy		= '1.4.0';
	const NormalizeVersion			= '2.1.1';
	const YUI3Version				= '3.10.0';
	
	const CdnjsStem					= '//cdnjs.cloudflare.com/ajax/libs/'; //All cdnjs libraries are under this path
	
	const CdnJqueryVersion			= '1.8.3';

	static public $VERSION			= '2.3.1-2'; //SHOULD BE UPDATED UPON EACH NEW RELEASE
	
	static public $BOOSTRAP_DIR;
	static public $BOOSTRAP_URL;
	
	protected $m_aAllPluginOptions;
	protected $m_aAllBootstrapLessOptions;
	
	protected $m_aBootstrapOptions;
	protected $m_aPluginOptions_BootstrapSection;
	protected $m_aPluginOptions_TwitterBootstrapSection;
	protected $m_aPluginOptions_ExtraTwitterSection;
	protected $m_aPluginOptions_MiscOptionsSection;
	
	public function __construct() {
		parent::__construct();

		register_activation_hook( __FILE__, array( &$this, 'onWpActivatePlugin' ) );
		register_deactivation_hook( __FILE__, array( &$this, 'onWpDeactivatePlugin' ) );
	//	register_uninstall_hook( __FILE__, array( &$this, 'onWpUninstallPlugin' ) );
		
		self::$PLUGIN_NAME	= basename(__FILE__);
		self::$PLUGIN_PATH	= plugin_basename( dirname(__FILE__) );
		self::$PLUGIN_DIR	= WP_PLUGIN_DIR.WORPIT_DS.self::$PLUGIN_PATH.WORPIT_DS;
		self::$PLUGIN_URL	= plugins_url( '/', __FILE__ ) ; //this seems to use SSL more reliably than WP_PLUGIN_URL
		self::$OPTION_PREFIX = self::BaseOptionPrefix . self::OptionPrefix;
		self::$OPTION_PREFIX = self::OptionPrefix;

		self::$BOOSTRAP_DIR = self::$PLUGIN_DIR.'resources'.WORPIT_DS.'bootstrap-'.self::TwitterVersion.WORPIT_DS;
		self::$BOOSTRAP_URL = plugins_url( 'resources/bootstrap-'.self::TwitterVersion.'/', __FILE__ ) ;

		$this->m_sParentMenuIdSuffix = 'wtb';
		
	}//__construct
	
	protected function initPluginOptions() {
		
		$this->m_aBootstrapOptions = array( 'select',
			array( 'none', 			'None' ),
			array( 'twitter',		'Twitter Bootstrap CSS v'.self::TwitterVersion ),
			array( 'yahoo-reset',	'Yahoo UI Reset CSS v2.9.0' ),
			array( 'yahoo-reset-3',	'Yahoo UI Reset CSS v'.self::YUI3Version ),
			array( 'normalize',		'Normalize CSS v'.self::NormalizeVersion )
		);

		$this->m_aPluginOptions_BootstrapSection = 	array(
			'section_title' => 'Choose Bootstrap CSS Options',
			'section_options' => array(
				array( 'option',				'',		'none', 	$this->m_aBootstrapOptions,		'Bootstrap Option', 'Choose Your Preferred Bootstrap Option', '' ),
				array( 'inc_responsive_css',	'',		'N', 		'checkbox',		'Responsive CSS', 'Include Bootstrap Responsive CSS', "Alone, this doesn't make your WordPress site 'responsive'." ),
				array( 'enq_using_wordpress',	'',		'N', 		'checkbox',		'Use WordPress System', "Not recommended. Use the WordPress CSS enqueue system to include the CSS links. This can't guarantee the file will be loaded first (which they should be)." ),
				array( 'customcss',				'',		'N', 		'checkbox',		'Custom Reset CSS', 'Enable custom CSS link', '(note: linked after any bootstrap/reset CSS selected above)' ),
				array( 'customcss_url',			'',		'http://', 	'text',			'Custom CSS URL', 'Provide the <strong>full</strong> URL path.', '' ),
			),
		);
		$this->m_aPluginOptions_TwitterBootstrapSection = 	array(
			'section_title' => 'Twitter Bootstrap Javascript Library Options',
			'section_options' => array(
				array( 'all_js',		'',		'none', 	'checkbox',		'All Javascript Libraries', 'Include ALL Bootstrap Javascript libraries', 'This will also include the jQuery library if it is not already included' ),
				array( 'js_head',		'',		'N', 		'checkbox',		'JavaScript Placement', 'Place Javascript in &lt;HEAD&gt;', 'Only check this option if know you need it.' ),
			),
		);
		$this->m_aPluginOptions_ExtraTwitterSection = 	array(
			'section_title' => 'Extra Twitter Bootstrap Options',
			'section_options' => array(
				array( 'useshortcodes',			'',		'N', 		'checkbox',		'Bootstrap Shortcodes', 'Enable Twitter Bootstrap Shortcodes', 'Loads WordPress shortcodes for fast use of Twitter Bootstrap Components.' ),
				array( 'use_minified_css',		'',		'N', 		'checkbox',		'Minified', 'Use Minified CSS/JS libraries', 'Uses minified CSS libraries where available.' ),
				array( 'use_compiled_css',		'',		'N', 		'checkbox',		'Enabled LESS', 'Enables LESS Compiler Section', 'Use the LESS Compiler to customize your Twitter Bootstrap CSS.' ),
				array( 'replace_jquery_cdn',	'',		'N', 		'checkbox',		'Replace JQuery', 'Replace JQuery library with JQuery from CDNJS', 'In case your WordPress version is too old and doesn\'t have the necessary JQuery version, this will replace your JQuery with a compatible version served from CDNJS.' ),
			),
		);
		$this->m_aPluginOptions_MiscOptionsSection = 	array(
			'section_title' => 'Miscellaneous Plugin Options',
			'section_options' => array(
				array( 'use_cdnjs',							'',		'N', 		'checkbox',		'Use CDNJS', 'Link to CDNJS libraries', 'Instead of serving libraries locally, use a dedicated CDN to serve files (<a href="http://wordpress.org/extend/plugins/cdnjs/" target="_blank">CDNJS</a>).' ),
				array( 'enable_shortcodes_sidebarwidgets',	'',		'N', 		'checkbox',		'Sidebar Shortcodes', 'Enable Shortcodes in Sidebar Widgets', 'Allows you to use Twitter Bootstrap (and any other) shortcodes in your Sidebar Widgets.' ),
				array( 'inc_bootstrap_css_in_editor',		'',		'N', 		'checkbox',		'CSS in Editor', 'Include Twitter Bootstrap CSS in the WordPress Post Editor', 'Only select this if you want to have Bootstrap styles show in the editor.' ),
				array( 'inc_bootstrap_css_wpadmin',			'',		'N', 		'checkbox',		'Admin Bootstrap CSS', 'Include Twitter Bootstrap CSS in the WordPress Admin', 'Not a standard Twitter Bootstrap CSS. <a href="http://bit.ly/HgwlZI" target="_blank"><span class="label label-info">more info</span></a>' ),
				array( 'hide_dashboard_rss_feed',			'',		'N', 		'checkbox',		'Hide RSS News Feed', 'Hide the iControlWP Blog news feed from the Dashboard', 'Hides our news feed from inside your Dashboard.' ),
				array( 'delete_on_deactivate',				'',		'N', 		'checkbox',		'Delete Plugin Settings', 'Delete All Plugin Setting Upon Plugin Deactivation', 'Careful: Removes all plugin options when you deactivite the plugin.' ),
				array( 'prettify',							'',		'N', 		'checkbox',		'Display Code Snippets', 'Include Google Prettify/Pretty Links Javascript', 'If you display code snippets or similar on your site, enabling this option will include the
											Google Prettify Javascript library for use with these code blocks.' ),
			),
		);

		$this->m_aAllPluginOptions = array( &$this->m_aPluginOptions_BootstrapSection,
											&$this->m_aPluginOptions_TwitterBootstrapSection,
											&$this->m_aPluginOptions_ExtraTwitterSection,
											&$this->m_aPluginOptions_MiscOptionsSection
		);

		return true;
		
	}//initPluginOptions
	
	public function onWpPluginsLoaded() {
		parent::onWpPluginsLoaded();
	}//onWpPluginsLoaded

	public function onWpInit() {
		parent::onWpInit();
		
		if ( !is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php')) && !isset( $_GET['thesis_editor'] ) ) {
			
			if ( self::getOption( 'enq_using_wordpress' ) !== 'Y' ) { //see end of file for the alternative (enqueueing)
				ob_start( array( &$this, 'onOutputBufferFlush' ) );
			}
		}

		add_action( 'wp_enqueue_scripts', array( &$this, 'onWpPrintStyles' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'onWpEnqueueScripts' ) );
		
		//if shortcodes are enabled, instantiate
		$sBootstrapOption = self::getOption( 'option' );
		if ( $sBootstrapOption == 'twitter' && self::getOption( 'useshortcodes' ) == 'Y' ) {
			$oShortCodes = new HLT_BootstrapShortcodes();
		}
		
		//if option to enable shortcodes in sidebar is on, add filter
		$sShortcodeSidebarOption = self::getOption( 'enable_shortcodes_sidebarwidgets' );
		if ( $sShortcodeSidebarOption == 'Y' ) {
			add_filter('widget_text', 'do_shortcode');
		}
		
	}//onWpInit
	
	public function onWpAdminInit() {
		parent::onWpAdminInit();
		
		global $pagenow;
		//Loads the news widget on the Dashboard (if it hasn't been disabled)
		if ( $pagenow == 'index.php' ) {
			$sDashboardRssOption = self::getOption( 'hide_dashboard_rss_feed' );
			if ( empty( $sDashboardRssOption ) || self::getOption( 'hide_dashboard_rss_feed' ) == 'N' ) {
				include_once( dirname(__FILE__).'/hlt-rssfeed-widget.php' );
				$oHLT_DashboardRssWidget = new HLT_DashboardRssWidget();
			}
		}
		
		if ( $this->isIcwpPluginAdminPage()) {
			
			//JS color picker for the Bootstrap LESS
			if ( $_GET['page'] == $this->getSubmenuId( 'bootstrap-less' ) ) {
				wp_register_style( 'miniColors', self::$PLUGIN_URL.'inc/miniColors/jquery.miniColors.css', false, self::$VERSION );
				wp_enqueue_style( 'miniColors' );
	
				wp_register_script( 'miniColors', self::$PLUGIN_URL.'inc/miniColors/jquery.miniColors.min.js', false, self::$VERSION, true );
				wp_enqueue_script( 'miniColors' );
			}
			
		}
		
		//Enqueues the WP Admin Twitter Bootstrap files if the option is set or we're in a iControlWP admin page.
		if ( $this->isIcwpPluginAdminPage() || ( is_admin() && self::getOption( 'inc_bootstrap_css_wpadmin' ) == 'Y' ) ) {
			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueueBootstrapAdminCss' ), 99 );
		}
		
		if ( is_admin() && self::getOption( 'inc_bootstrap_css_in_editor' ) == 'Y' ) {
			add_filter( 'mce_css', array( &$this, 'filter_include_bootstrap_in_editor' ) );
		}
		
		//Multilingual support.
		load_plugin_textdomain( 'hlt-wordpress-bootstrap-css', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}
	
	protected function createPluginSubMenuItems(){
		$this->m_aPluginMenu = array(
				//Menu Page Title => Menu Item name, page ID (slug), callback function for this page - i.e. what to do/load.
				$this->getSubmenuPageTitle( 'Bootstrap CSS' ) => array( 'Bootstrap CSS', $this->getSubmenuId('bootstrap-css'), 'onDisplayWtbCss' ),
				$this->getSubmenuPageTitle( 'Bootstrap LESS' ) => array( 'Bootstrap LESS', $this->getSubmenuId('bootstrap-less'), 'onDisplayWtbLess' ),
			);
	}//createPluginSubMenuItems
	
	/**
	 * Handles the upgrade from version 1 to version 2 of Twitter Bootstrap as well as any other plugin upgrade
	 */
	protected function handlePluginUpgrade() {
		
		$sCurrentPluginVersion = self::getOption( 'current_plugin_version' );
		
		// Forces a rebuild for the list of CSS includes
		if ( $sCurrentPluginVersion !== self::$VERSION ) {
			self::deleteOption( 'includes_list' );
		}
		
		//current_user_can( 'manage_options' ) ensure only valid users attempt this.
		if ( $sCurrentPluginVersion !== self::$VERSION && current_user_can( 'manage_options' ) ) {

			//Manages those users who are coming from a version pre-Twitter 2.0+
			if ( self::getOption( 'upgraded1to2' ) !== 'Y' ) {
				if ( self::getOption( 'alerts_js' ) === 'Y' || self::getOption( 'tabs_js' ) === 'Y'	|| self::getOption( 'twipsy_js' ) === 'Y' ) {
					self::updateOption( 'all_js', 'Y' );
				}
				self::addOption( 'upgraded1to2', 'Y' );
			}
			
			//Manages migration to version 2.0.3 where legacy twitter and individual Javascript libraries were removed
			if ( self::getOption( 'alert_js' ) == 'Y'
					|| self::getOption( 'button_js' ) == 'Y'
					|| self::getOption( 'dropdown_js' ) == 'Y'
					|| self::getOption( 'modal_js' ) == 'Y'
					|| self::getOption( 'tooltip_js' ) == 'Y'
					|| self::getOption( 'popover_js' ) == 'Y'
					|| self::getOption( 'scrollspy_js' ) == 'Y'
					|| self::getOption( 'tab_js' ) == 'Y'
					|| self::getOption( 'transition_js' ) == 'Y'
					|| self::getOption( 'collapse_js' ) == 'Y'
					|| self::getOption( 'carousel_js' ) == 'Y'
					|| self::getOption( 'typeahead_js' ) == 'Y'
					) {
				self::updateOption( 'all_js', 'Y' );
			}
			
			//Delete all old plugin options from all previous versions if they exist.
			$m_aAllOldPluginOptions = array(
				'hotlink',
				'alert_js',
				'button_js',
				'dropdown_js',
				'modal_js',
				'tooltip_js',
				'popover_js',
				'scrollspy_js',
				'tab_js',
				'transition_js',
				'collapse_js',
				'carousel_js',
				'typeahead_js',
				'alerts_js',	//upgrade from 1~2
				'tabs_js',		//upgrade from 1~2
				'twipsy_js'		//upgrade from 1~2
			);
			foreach ( $m_aAllOldPluginOptions as $sOldOptions ) {
				self::deleteOption( $sOldOptions );
			}
			
			//Recompile LESS CSS if applicable
			if ( self::getOption('use_compiled_css') == 'Y' ) {
				
				$oBoostrapLess = new HLT_BootstrapLess();

				//Get the array of plugin options
				$aBootstrapOptions = $this->getOption( HLT_BootstrapLess::$LESS_OPTIONS_DB_KEY );
				
				if ( empty($aBootstrapOptions) ) { //pre-2.0.4.2 version of the plugin
					
					$aBootstrapOptions = $oBoostrapLess->getAllBootstrapLessOptions();
					
					foreach ( $aBootstrapOptions as &$aLessSection ) {
						foreach ( $aLessSection['section_options'] as &$aOptionParams ) {
					
							list( $sOptionKey, $sOptionSaved, $sOptionDefault, $sOptionType, $sOptionHumanName ) = $aOptionParams;
							
							if ( $sOptionKey != 'spacer') { //FIX DEBUG: http://wordpress.org/support/topic/plugin-wordpress-twitter-bootstrap-css-noticeswarningsdb-option-usage
								$sCurrentOptionVal = HLT_BootstrapCss::getOption( $sOptionKey );
								HLT_BootstrapCss::deleteOption( $sOptionKey );
								$aOptionParams[1] = ($sCurrentOptionVal == '' )? $sOptionDefault : $sCurrentOptionVal;
							}
							
						}//foreach $aOptionParams
					}//foreach $aLessSection
					
					$this->updateOption( HLT_BootstrapLess::$LESS_OPTIONS_DB_KEY, $aBootstrapOptions );
				}
				
				if ( $oBoostrapLess->reWriteVariablesLess( self::$BOOSTRAP_DIR ) ) {
					$oBoostrapLess->compileAllBootstrapLess( self::$BOOSTRAP_DIR );
				}
				
			}//if: use_compiled_css == 'Y'
		
			//Set the flag so that this update handler isn't run again for this version.
			self::updateOption( 'current_plugin_version', self::$VERSION );
		}//if

		//Someone clicked the button to acknowledge the update
		if ( isset( $_POST['hlt_hide_update_notice'] ) && isset( $_POST['hlt_user_id'] ) ) {
			$result = update_user_meta( $_POST['hlt_user_id'], 'hlt_bootstrapcss_current_version', self::$VERSION );
			header( "Location: admin.php?page=".$this->getFullParentMenuId() );
		}
		
	}//handlePluginUpgrade
	
	public function onWpAdminNotices() {
		
		//Do we have admin priviledges?
		if ( !current_user_can( 'manage_options' ) ) {
			return;
		}
		
		$this->adminNoticeVersionUpgrade();
		$this->adminNoticeOptionsUpdated();
	}
	
	private function adminNoticeVersionUpgrade() {

		global $current_user;
		$user_id = $current_user->ID;

		$sCurrentVersion = get_user_meta( $user_id, self::$OPTION_PREFIX.'current_version', true );

		if ( $sCurrentVersion !== self::$VERSION ) {
			ob_start();
			?>
				<style>
					a#fromIcwp { padding: 0 5px; border-bottom: 1px dashed rgba(0,0,0,0.1); color: blue; font-weight: bold; }
				</style>
				<form id="IcwpUpdateNotice" method="post" action="admin.php?page=<?php echo $this->getSubmenuId('bootstrap-css'); ?>">
					<input type="hidden" value="1" name="hlt_hide_update_notice" id="hlt_hide_update_notice">
					<input type="hidden" value="<?php echo $user_id; ?>" name="hlt_user_id" id="hlt_user_id">
					<h4 style="margin:10px 0 3px;">Quick question: Do you manage multiple WordPress sites and need a better way to do it?</h4>
					<input type="submit" value="Cool, but just show me what's new with this update and hide this notice" name="submit" class="button" style="float:right;">
					<p>
						Free up your time today and do it all from 1 place in a few clicks.
						<a href="http://icwp.io/5" id="fromIcwp" title="iControlWP: Secure WordPress Management" target="_blank">Tell me how</a>!<br />
					</p>
					<div class=""></div>
				</form>
			<?php
			$sNotice = ob_get_contents();
			ob_end_clean();
			
			$this->getAdminNotice( $sNotice, 'updated', true );
		}
		
	}//adminNoticeVersionUpgrade
	
	private function adminNoticeOptionsUpdated() {
		
		$sAdminFeedbackNotice = $this->getOption( 'feedback_admin_notice' );
		if ( !empty( $sAdminFeedbackNotice ) ) {
			$sNotice = '<p>'.$sAdminFeedbackNotice.'</p>';
			$this->getAdminNotice( $sNotice, 'updated', true );
			$this->updateOption( 'feedback_admin_notice', '' );
		}
		
	}//adminNoticeOptionsUpdated
	
	public function onDisplayWtbCss() {
		
		//populates plugin options with existing configuration
		$this->readyAllPluginOptions();
		
		//Specify what set of options are available for this page
		$aAvailableOptions = array( &$this->m_aPluginOptions_BootstrapSection, &$this->m_aPluginOptions_TwitterBootstrapSection, &$this->m_aPluginOptions_ExtraTwitterSection, &$this->m_aPluginOptions_MiscOptionsSection ) ;

		$sAllFormInputOptions = $this->collateAllFormInputsForAllOptions($aAvailableOptions);
		
		$aData = array(
			'plugin_url'		=> self::$PLUGIN_URL,
			'var_prefix'		=> self::$OPTION_PREFIX,
			'aAllOptions'		=> $aAvailableOptions,
			'all_options_input'	=> $sAllFormInputOptions,
			'nonce_field'		=> $this->getSubmenuId('bootstrap-css').'_wtbcss',
			'form_action'		=> 'admin.php?page='.$this->getSubmenuId('bootstrap-css')
		);

		$this->display( 'bootstrapcss_index', $aData );
	}//onDisplayWtbCss
	
	public function onDisplayWtbLess() {
		
		$oBoostrapLess = new HLT_BootstrapLess();

		$aAvailableOptions = $oBoostrapLess->getAllBootstrapLessOptions(false);
		
		$aData = array(
			'plugin_url'				=> self::$PLUGIN_URL,
			'var_prefix'				=> self::$OPTION_PREFIX,
			'aAllOptions'				=> $aAvailableOptions,
			'compiler_enabled'			=> self::getOption( 'use_compiled_css' ) === 'Y',

			'less_prefix'				=> HLT_BootstrapLess::LessOptionsPrefix,
			'less_file_location'		=> array( self::$BOOSTRAP_DIR.'css'.WORPIT_DS.'bootstrap.less.css', self::$BOOSTRAP_URL.'css/bootstrap.less.css' ),
			'page_link_options'			=> $this->getSubmenuId('bootstrap-css'),
			
			'nonce_field'				=> $this->getSubmenuId('bootstrap-css').'_wtbless',
			'form_action'				=> 'admin.php?page='.$this->getSubmenuId('bootstrap-less')
		);
		
		$this->display( 'bootstrapcss_less', $aData );
		
	}//onDisplayLess
	
	protected function handlePluginFormSubmit() {
		
		if ( !isset( $_POST['worpit_plugin_form_submit'] ) ) {
			return;
		}
		
		$this->m_fSubmitCbcMainAttempt = true;
	
		if ( isset( $_GET['page'] ) ) {
			switch ( $_GET['page'] ) {
				case $this->getSubmenuId( 'bootstrap-css' ):
					$this->handleSubmit_BootstrapCssOptions();
					break;
					
				case $this->getSubmenuId( 'bootstrap-less' ):
					$this->handleSubmit_BootstrapLess();
					break;
			}
		}
		
		if ( !self::$m_fUpdateSuccessTracker ) {
			self::updateOption( 'feedback_admin_notice', 'Updating Twitter Bootstrap Settings <strong>Failed</strong>.' );
		}
		else {
			self::updateOption( 'feedback_admin_notice', 'Updating Twitter Bootstrap Settings <strong>Succeeded</strong>.' );
		}
		
		// Flush W3 Total Cache (compatible up to version 0.9.2.4)
		if ( class_exists( 'W3_Plugin_TotalCacheAdmin' ) ) {
			$oW3TotalCache =& w3_instance( 'W3_Plugin_TotalCacheAdmin' );
			$oW3TotalCache->flush_all();
		}
		
	}//handlePluginFormSubmit
	
	protected function handleSubmit_BootstrapCssOptions() {

		//Ensures we're actually getting this request from WP.
		check_admin_referer( $this->getSubmenuId('bootstrap-css').'_wtbcss' );
		
		if ( !isset($_POST[self::$OPTION_PREFIX.'all_options_input']) ) {
			return;
		}
		$this->updatePluginOptionsFromSubmit( $_POST[self::$OPTION_PREFIX.'all_options_input'] );
		
		self::deleteOption( 'includes_list' );

		//DEBUG error problem reported: http://wordpress.org/support/topic/plugin-wordpress-twitter-bootstrap-css-noticeswarningsdb-option-usage
		$sCustomUrl = (isset( $_POST[self::$OPTION_PREFIX.'customcss_url'] ))? $_POST[self::$OPTION_PREFIX.'customcss_url'] : '';
		$fCustomCss = ($this->getAnswerFromPost( 'customcss' ) === 'Y');
		if ( $fCustomCss && !empty( $sCustomUrl ) ) {
			if ( $this->checkUrlValid( $sCustomUrl ) ) {
				self::updateOption( 'customcss_url', $sCustomUrl );
			}
			else {
				self::updateOption( 'customcss_url', '' );
			}
		}
	}
	
	protected function handleSubmit_BootstrapLess() {

		//Ensures we're actually getting this request from WP.
		check_admin_referer( $this->getSubmenuId('bootstrap-css').'_wtbless' );
		
		//Compile LESS files
		$oBoostrapLess = new HLT_BootstrapLess();

		if ( isset( $_POST['submit_reset'] ) ) {
			$oBoostrapLess->resetToDefaultAllLessOptions( self::$BOOSTRAP_DIR );
			return;
		}

		if ( isset( $_POST['submit_preserve'] ) ) { //don't use the original variables.less
			$oBoostrapLess->processNewLessOptions( self::$OPTION_PREFIX, self::$BOOSTRAP_DIR, FALSE );
			return;
		}
		
		$oBoostrapLess->processNewLessOptions( self::$OPTION_PREFIX, self::$BOOSTRAP_DIR, TRUE );

	}//handleSubmit_BootstrapLess

	public function filter_include_bootstrap_in_editor( $mce_css ) {
		$mce_css = explode( ',', $mce_css);
		$mce_css = array_map( 'trim', $mce_css);
		array_unshift( $mce_css, self::$BOOSTRAP_URL.'css/bootstrap.min.css' );
		return implode( ',', $mce_css );
	}
	
	public function onWpPrintStyles() {
		if ( self::getOption( 'prettify' ) == 'Y' ) {
			$sUrl = $this->getCssUrl( 'google-code-prettify/prettify.css' );
			wp_register_style( 'prettify_style', $sUrl );
			wp_enqueue_style( 'prettify_style' );
		}
	}
	
	public function onWpEnqueueScripts() {
		
		$fJsInFooter = (self::getOption( 'js_head' ) == 'Y'? false : true);
		$sBootstrapOption = self::getOption( 'option' );
		
		if ( $sBootstrapOption == 'twitter' && self::getOption( 'all_js' ) == 'Y' ) {
			
			$sExtension = ( self::getOption( 'use_minified_css' ) == 'Y' )? '.min.js' : '.js';

			if ( self::getOption( 'use_cdnjs' ) == 'Y' ) {
				//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.2.2/bootstrap.min.js
				//Since version 2.3.0, now changed to:
				////cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/js/bootstrap.min.js
				$sUrlBootstrapJs = self::CdnjsStem.'twitter-bootstrap/'.self::TwitterVersion.'/js/bootstrap'.$sExtension;
			}
			else {
				$sUrlBootstrapJs = self::$BOOSTRAP_URL.'js/bootstrap'.$sExtension;
			}

			if ( self::getOption( 'replace_jquery_cdn' ) == 'Y' ) {
				wp_deregister_script('jquery');
				
				//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js
				$sJqueryCdnUri = self::CdnjsStem.'jquery/'.self::CdnJqueryVersion.'/jquery'.$sExtension;
				
				wp_register_script( 'jquery', $sJqueryCdnUri, '', self::CdnJqueryVersion, false );
			}
			
			wp_enqueue_script( 'jquery' );
			
			wp_register_script( 'bootstrap-all-min', $sUrlBootstrapJs, array('jquery'), self::$VERSION, $fJsInFooter );
			wp_enqueue_script( 'bootstrap-all-min' );
		}
		
		if ( self::getOption( 'prettify' ) == 'Y' ) {
			$sUrl = $this->getJsUrl( 'google-code-prettify/prettify.js' );
			wp_register_script( 'prettify_script', $sUrl, false, self::$VERSION, $fJsInFooter );
			wp_enqueue_script( 'prettify_script' );
		}

	}//onWpEnqueueScripts
	
	public function onEnqueueResetCss() {
		if ( is_admin()
				|| in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))
				|| isset( $_GET['thesis_editor'] )
				|| ( self::getOption( 'enq_using_wordpress' ) !== 'Y' )
			) {
			return true;
		}

		$aIncludesList = $this->getCssIncludeUrls();
		
		if ( empty( $aIncludesList ) ) {
			return true;
		}
		
		foreach( $aIncludesList as $sKey => $sCssLinkUrl ) {
			wp_register_style( $sKey, $sCssLinkUrl );
			wp_enqueue_style( $sKey );
		}

	}
	
	public function onOutputBufferFlush( $insContent ) {
		return $this->rewriteHead( $insContent );
	}
	
	/**
	 * Performs the actual rewrite of the <HEAD> to include the reset file(s)
	 *
	 * @param $insContents
	 */
	public function rewriteHead( $insContents ) {
		
		$aIncludesList = $this->getCssIncludeUrls();
		
		if ( empty( $aIncludesList ) ) {
			return $insContents;
		}
		
		//Add the CSS link
		$sReplace = '${1}';
		$sReplace .= "\n<!-- This site uses the WordPress Twitter Bootstrap CSS plugin v".self::$VERSION." from iControlWP http://icwp.io/w/ -->";
		
		foreach ( $aIncludesList as $sKey => $sIncludeLink ) {
			$sReplace .= "\n".'<link rel="stylesheet" type="text/css" href="'.$sIncludeLink.'" />';
		}

		$sReplace .= "\n<!-- / WordPress Twitter Bootstrap CSS Plugin from iControlWP. -->";
		
		$sRegExp = "/(<\bhead\b([^>]*)>)/i";
		return preg_replace( $sRegExp, $sReplace, $insContents, 1 );
	}
	
	protected function getCssIncludeUrls() {
		
		$aPossibleIncludeOptions = array( 'twitter', 'yahoo-reset', 'yahoo-reset-3', 'normalize' );
		$sIncludeOption = self::getOption( 'option' );
		
		// An unsupported option, so just return add the custom CSS.
		if ( !in_array( $sIncludeOption, $aPossibleIncludeOptions ) ) {
			
			$aIncludesList = array();
			$this->addCustomCssLink( $aIncludesList );
			return $aIncludesList;
		}

		// We save the inclusions list so we don't work it out every page load.
		$aIncludesList = self::getOption( 'includes_list' );

		if ( !is_array($aIncludesList) ) { //process the list of CSS to be included

			$aIncludesList = array();

			// 'twitter', 'yahoo-reset', 'yahoo-reset-3', 'normalize'
			switch ( $sIncludeOption ) {
				case 'twitter':
					$aIncludesList = $this->getTwitterCssUrls( self::getOption( 'use_minified_css' ) == 'Y' );
					break;
				case 'normalize':
					if ( self::getOption( 'use_cdnjs' ) == 'Y' ) {
						// cdnjs.cloudflare.com/ajax/libs/normalize/2.0.1/normalize.css
						$aIncludesList = array( 'normalize' => self::CdnjsStem.'normalize/'.self::NormalizeVersion.'/normalize.css' );
					}
					else {
						$aIncludesList = array( 'normalize' => $this->getCssURL( 'normalize.css' ) . '?ver='.self::NormalizeVersion );
					}
					break;
				case 'yahoo-reset':
					$aIncludesList = array( 'yahoo-reset-290' => $this->getCssURL( 'yahoo-2.9.0.min.css' ) );
					break;
				case 'yahoo-reset-3':
					$aIncludesList = array( 'yahoo-reset-3' => $this->getCssURL( 'yahoo-cssreset-min.css' ) . '?ver='.self::YUI3Version );
					break;
				default:
					break;
			}
			
			// At this point $aIncludesList should be an array of all the URLs to be included with their labels. 
				
			// Now add Custom/Reset CSS.
			$this->addCustomCssLink( $aIncludesList );
				
			self::updateOption( 'includes_list', $aIncludesList ); //update our cached list
		}

		return $aIncludesList;
	}
	
	protected function addCustomCssLink( &$inaCssList = array() ) {

		if ( self::getOption( 'customcss' ) == 'Y' ) {
			$sCustomCssUrl = self::getOption( 'customcss_url' );
			if ( !empty( $sCustomCssUrl ) ) {
				$inaCssList[ 'custom-reset' ] = $sCustomCssUrl;
			}
		}
	}
	
	/**
	 * Depending on the configuration options set, will provide an array of the Twitter URLs to be included
	 *  
	 * @param $infMinified
	 * @return Array
	 */
	protected function getTwitterCssUrls( $infMinified = false ) {

		$sCssFileExtension = $infMinified? '.min.css' : '.css';
		$fResponsive = self::getOption( 'inc_responsive_css' ) == 'Y';
				
		// link to the Twitter LESS-compiled CSS (only if the files exists)
		if ( self::getOption( 'use_compiled_css' ) == 'Y' ) {
			$aUrls = array();
			$fValid = false;
			$sLessStemDir = self::$BOOSTRAP_DIR.'css'.WORPIT_DS.'bootstrap';
			$sLessStemUrl = self::$BOOSTRAP_URL.'css/bootstrap';
			if ( file_exists( $sLessStemDir.'.less'.$sCssFileExtension ) ) {
				$aUrls[ 'twitter-bootstrap-less' ] = $sLessStemUrl.'.less'.$sCssFileExtension;
				$fValid = true;
				if ( $fResponsive ) {
//It seems the less compiler isn't (or never has?) created the responsive less files
// 					if ( file_exists( $sLessStemDir.'-responsive.less'.$sCssFileExtension ) ) {
// 						$aUrls[ 'twitter-bootstrap-responsive-less' ] = $sLessStemUrl.'-responsive.less'.$sCssFileExtension;
					if ( file_exists( $sLessStemDir.'-responsive'.$sCssFileExtension ) ) {
						$aUrls[ 'twitter-bootstrap-responsive-less' ] = $sLessStemUrl.'-responsive'.$sCssFileExtension;
						$fValid = true;
					}
					else {
						$fValid = false;
					}
				}
			}
			// If we were able to get all the URLs we were supposed to for LESS, return them now.
			if ( $fValid ) {
				return $aUrls;
			}
		}

		// Determine the Twitter URL stem based on local or if CDNJS selected
		if ( self::getOption( 'use_cdnjs' ) == 'Y' ) {
			$sTwitterStem = self::CdnjsStem.'twitter-bootstrap/'.self::TwitterVersion.'/css/bootstrap';
		}
		else {
			$sTwitterStem = self::$BOOSTRAP_URL.'css/bootstrap'; // default is to serve it "local"
		}

		$aUrls = array();
		// Add the Twitter URLs
		$aUrls[ 'twitter-bootstrap' ] = $sTwitterStem.$sCssFileExtension;
		if ( $fResponsive ) {
			$aUrls[ 'twitter-bootstrap-responsive' ] = $sTwitterStem.'-responsive'.$sCssFileExtension;
		}
		
		return $aUrls;
	}
	
	protected function checkUrlValid( $insUrl ) {
		$oCurl = curl_init();
		curl_setopt( $oCurl, CURLOPT_URL, $insUrl );
		curl_setopt( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $oCurl, CURLOPT_CONNECTTIMEOUT, 10 );
		
		$sContent = curl_exec( $oCurl );
		$sHttpCode = curl_getinfo( $oCurl, CURLINFO_HTTP_CODE );
		curl_close( $oCurl );
		
		return ( intval( $sHttpCode ) === 200 );
	}
	/*
	protected function getAnswerFromPost( $insKey, $insPrefix = null ) {
		if ( is_null( $insPrefix ) ) {
			$insKey = self::InputPrefix.$insKey;
		}
		return ( isset( $_POST[$insKey] )? 'Y': 'N' );
	}
	*/
	/**
	 * Not currently used, but could be useful once we work out what way the JS should be included.
	 * @param $insHandle	For example: 'prettify/prettify.css'
	 */
	protected function isRegistered( $insHandle ) {
		return (
			wp_script_is( $insHandle, 'registered' ) ||
			wp_script_is( $insHandle, 'queue' ) ||
			wp_script_is( $insHandle, 'done' ) ||
			wp_script_is( $insHandle, 'to_do' )
		);
	}
	
	public function onWpPluginActionLinks( $inaLinks, $insFile ) {
		if ( $insFile == plugin_basename( __FILE__ ) ) {
			$sSettingsLink = '<a href="'.admin_url( "admin.php" ).'?page='.$this->getSubmenuId('bootstrap-css').'">' . _hlt__( 'Settings' ) . '</a>';
			array_unshift( $inaLinks, $sSettingsLink );
		}
		return $inaLinks;
	}
	
	protected function deleteAllPluginDbOptions() {
		
		parent::deleteAllPluginDbOptions();
		
		if ( !current_user_can( 'manage_options' ) ) {
			return;
		}
		
		$oBoostrapLess = new HLT_BootstrapLess();
		$oBoostrapLess->processLessOptions( 'delete' );
		
	}//deleteAllPluginDbOptions
	
	public function onWpDeactivatePlugin() {
		
		if ( $this->getOption('delete_on_deactivate') == 'Y' ) {
			$this->deleteAllPluginDbOptions();
		}
		
		$this->deleteOption( 'current_plugin_version' );
		$this->deleteOption( 'feedback_admin_notice' );
		$this->deleteOption( 'upgraded1to2' );
		
	}//onWpDeactivatePlugin
	
	public function onWpActivatePlugin() { }
	
	public function enqueueBootstrapAdminCss() {
		wp_register_style( 'worpit_bootstrap_wpadmin_css', $this->getCssUrl( 'bootstrap-wpadmin.css' ), false, self::$VERSION );
		wp_enqueue_style( 'worpit_bootstrap_wpadmin_css' );
		wp_register_style( 'worpit_bootstrap_wpadmin_css_fixes', $this->getCssUrl('bootstrap-wpadmin-fixes.css'),  array('worpit_bootstrap_wpadmin_css'), self::$VERSION );
		wp_enqueue_style( 'worpit_bootstrap_wpadmin_css_fixes' );
	}//enqueueBootstrapAdminCss
	
}//HLT_BootstrapCss

endif;

$oHLT_BootstrapCss = new HLT_BootstrapCss();

// Trying to enque the style as early as possible.
add_action( 'wp_enqueue_scripts', array( $oHLT_BootstrapCss, 'onEnqueueResetCss' ), 0 );
