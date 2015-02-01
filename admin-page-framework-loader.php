<?php
/* 
    Plugin Name:    Admin Page Framework - Loader
    Plugin URI:     http://en.michaeluno.jp/admin-page-framework
    Description:    Loads Admin Page Framework which facilitates WordPress plugin and theme development.
    Author:         Michael Uno
    Author URI:     http://michaeluno.jp
    Version:        3.5.2
    Requirements:   PHP 5.2.4 or above, WordPress 3.3 or above.
*/ 

/**
 * The base registry information.
 * 
 * @since       3.5.0
 */
class AdminPageFrameworkLoader_Registry_Base {

	const Version        = '3.5.2';    // <--- DON'T FORGET TO CHANGE THIS AS WELL!!
	const Name           = 'Admin Page Framework - Loader'; // the name is not 'Admin Page Framework' because warning messages gets confusing.
    const ShortName      = 'Admin Page Framework';  // used for a menu title etc.
	const Description    = 'Loads Admin Page Framework which facilitates WordPress plugin and theme development.';
	const URI            = 'http://en.michaeluno.jp/';
	const Author         = 'miunosoft (Michael Uno)';
	const AuthorURI      = 'http://en.michaeluno.jp/';
	const Copyright      = 'Copyright (c) 2015, Michael Uno';
	const License        = 'GPL v2 or later';
	const Contributors   = '';
	
}
/**
 * Provides plugin information.
 * 
 * The plugin will refer to these information.
 * 
 * @since       3.5.0
 * @remark      
 */
final class AdminPageFrameworkLoader_Registry extends AdminPageFrameworkLoader_Registry_Base {
	        
    /**
     * The plugin option key used for the options table.
     */
    static public $aOptionKeys = array(
        'main'    => 'admin_page_framework_loader',
        'demo'    => array(
            'main'          => 'APF_Demo',
            'taxonomy'      => 'APF_TaxonomyField',
            'basic_usage'   => 'APF_BasicUsage',
        )
    );

    /**
     * The transient prefix. 
     * 
     * @remark      This is also accessed from uninstall.php so do not remove.
     * @remark      Up to 8 characters as transient name allows 45 characters or less ( 40 for site transients ) so that md5 (32 characters) can be added
     */
	const TransientPrefix         = 'APFL_';
    
    /**
     * The hook slug used for the prefix of action and filter hook names.
     * 
     * @remark      The ending underscore is not necessary.
     */
    const HookSlug                = 'admin_page_framework_loader';
        
    
    /**
     * The text domain slug and its path.
     * 
     * These will be accessed from the bootstrap script.
     */
	const TextDomain              = 'admin-page-framework-loader';
	const TextDomainPath          = '/language';    
    	    
	// These properties will be defined in the setUp() method.
	static public $sFilePath = '';
	static public $sDirPath  = '';
	
    /**
     * Requirements.
     */    
    static public $aRequirements = array(
        'php' => array(
            'version'   => '5.2.4',
            'error'     => 'The plugin requires the PHP version %1$s or higher.',
        ),
        'wordpress'         => array(
            'version'   => '3.3',
            'error'     => 'The plugin requires the WordPress version %1$s or higher.',
        ),
        'mysql'             => array(
            'version'   => '5.0',
            'error'     => 'The plugin requires the MySQL version %1$s or higher.',
        ),
        'functions'     =>  '', // disabled
        // array(
            // e.g. 'mblang' => 'The plugin requires the mbstring extension.',
        // ),
        'classes'       => '', // disabled
        // array(
            // e.g. 'DOMDocument' => 'The plugin requires the DOMXML extension.',
        // ),
        'constants'     => '',  // disabled
        // array(
            // e.g. 'THEADDONFILE' => 'The plugin requires the ... addon to be installed.',
            // e.g. 'APSPATH' => 'The script cannot be loaded directly.',
        // ),
        'files'         =>  '', // disabled
        // array(
            // e.g. 'home/my_user_name/my_dir/scripts/my_scripts.php' => 'The required script could not be found.',
        // ),
    );    
    
    /**
     * Used admin pages.
     */
    static public $aAdminPages = array(
        // key => 'page slug'
        'about'     => 'apfl_about',        // the welcome page
        'addon'     => 'apfl_addons',
        'tool'      => 'apfl_tools',
        'help'      => 'apfl_contact',
    );
    
    /**
     * Used post types.
     */
    static public $aPostTypes = array(
        'demo'      => 'apf_posts',
    );
    
    /**
     * Used taxonomies.
     */
    static public $aTaxonomies = array(
    );
    
	/**
	 * Sets up static properties.
	 */
	static function setUp( $sPluginFilePath=null ) {
	                    
		self::$sFilePath = $sPluginFilePath ? $sPluginFilePath : __FILE__;
		self::$sDirPath  = dirname( self::$sFilePath );
	    
	}    
	
	/**
	 * Returns the URL with the given relative path to the plugin path.
	 * 
	 * Example:  AdminPageFrameworkLoader_Registry::getPluginURL( 'asset/css/meta_box.css' );
     * @since       3.5.0
	 */
	public static function getPluginURL( $sRelativePath='' ) {
		return plugins_url( $sRelativePath, self::$sFilePath );
	}
    
    /**
     * Returns the information of this class.
     * 
     * @since       3.5.0
     */
    static public function getInfo() {
        $_oReflection = new ReflectionClass( __CLASS__ );
        return $_oReflection->getConstants()
            + $_oReflection->getStaticProperties()
        ;
    }    
    
    /**
     * Stores admin notices.
     * @since       3.5.0
     */
    static public $_aAdminNotices = array();
    /**
     * Sets an admin notice.
     * @since       3.5.0
     */ 
    static public function setAdminNotice( $sMessage, $sClassAttribute='error' ) {
        if ( ! is_admin() ) { return; }
        self::$_aAdminNotices[] = array(
            'message'           => $sMessage,
            'class_attribute'   => $sClassAttribute,
        );
        add_action( 'admin_notices', array( __CLASS__, '_replyToSetAdminNotice' ) );
    }
        /**
         * Displayes the set admin notices.
         * @since       3.5.0
         */
        static public function _replyToSetAdminNotice() {
            foreach( self::$_aAdminNotices as $_aAdminNotice ) {                
                echo "<div class='" . esc_attr( $_aAdminNotice['class_attribute'] ) . "'>"
                        ."<p>" 
                            . sprintf( 
                                '<strong>%1$s</strong>: ' . $_aAdminNotice['message'],
                                self::Name . ' ' . self::Version
                            )
                        . "</p>"
                    . "</div>";
            }
        }    
    
}
// Registry set-up.
AdminPageFrameworkLoader_Registry::setUp( __FILE__ );

// Initial checks. - Do no load if accessed directly, not exiting because the 'uninstall.php' or inclusion list generator will load this file.
if ( ! defined( 'ABSPATH' ) ) { return; }
if ( defined( 'DOING_UNINSTALL' ) ) { return; }

// Include the library file 
if ( ! class_exists( 'AdminPageFramework' ) ) {    
    include( 
        defined( 'WP_DEBUG' ) && WP_DEBUG
            ? dirname( __FILE__ ) . '/development/admin-page-framework.php' // use the development version when you need to do debugging.
            : dirname( __FILE__ ) . '/library/admin-page-framework.min.php' // use the minified version in your plugins or themes.
    );
} 

// Avoid version conflicts.
if ( ! class_exists( 'AdminPageFramework_Registry' ) || version_compare( AdminPageFramework_Registry::Version, AdminPageFrameworkLoader_Registry::Version, '<' ) ) {
    AdminPageFrameworkLoader_Registry::setAdminNotice(
        sprintf( 
            'The framework has been already loaded and its version is lesser than yours. Your framework will not be loaded to avoid unexpected results. Loaded Version: %1$s. Your Version: %2$s.',
            class_exists( 'AdminPageFramework_Registry' )
                ? AdminPageFramework_Registry::Version
                : 'unknown',
            AdminPageFrameworkLoader_Registry::Version            
        )
    );
}

// Include the framework loader plugin components.
include( AdminPageFrameworkLoader_Registry::$sDirPath . '/include/class/boot/AdminPageFrameworkLoader_Bootstrap.php' );
if ( class_exists( 'AdminPageFrameworkLoader_Bootstrap' ) ) {   // for backward compatibility
    new AdminPageFrameworkLoader_Bootstrap( 
        AdminPageFrameworkLoader_Registry::$sFilePath,
        AdminPageFrameworkLoader_Registry::HookSlug    // hook prefix
    );
}

/*
 * If you find this framework useful, include it in your project!
 * And please leave a nice comment in the review page, http://wordpress.org/support/view/plugin-reviews/admin-page-framework
 * 
 * If you have a suggestion, the GitHub repository is open to anybody so post an issue there.
 * https://github.com/michaeluno/admin-page-framework/issues
 * 
 * Happy coding!
 */