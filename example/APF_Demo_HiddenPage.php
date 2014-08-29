<?php
/**
 * This example demonstrates the use of the set_up_{instantiated class name} hook.
 * 
 * Note that this class does not extend any class unlike the other admin page classes in the demo plugin examples.
 */
class APF_Demo_HiddenPage {
    
    /**
     * Sets up hooks.
     */
    public function __construct() {
        
        // set_up_{instantiated class name} - 'APF_Demo' is the class name of the main class.
        add_action( "set_up_" . "APF_Demo", array( $this, 'replyToSetUpPages' ) );
        add_action( "do_" . "apf_sample_page", array( $this, 'replyToDoSamplePage' ) );
        add_action( "do_" . "apf_hidden_page", array( $this, 'replyToDoHiddenPage' ) );
        
    }
    
    /**
     * Sets up pages.
     */
    public function replyToSetUpPages( $oAdminPage ) {    
    
        /* ( required ) Add sub-menu items (pages or links) */
        $oAdminPage->addSubMenuItems(     
            array(
                'title' => __( 'Sample Page', 'admin-page-framework-demo' ),
                'page_slug' => 'apf_sample_page',
                'screen_icon' => dirname( APFDEMO_FILE ) . '/asset/image/wp_logo_bw_32x32.png', // ( for WP v3.7.1 or below ) the icon _file path_ can be used
            ),     
            array(
                'title' => __( 'Hidden Page', 'admin-page-framework-demo' ),
                'page_slug' => 'apf_hidden_page',
                'screen_icon' => version_compare( $GLOBALS['wp_version'], '3.8', '<' ) 
                    ? plugins_url( 'asset/image/wp_logo_bw_32x32.png', APFDEMO_FILE )
                    : null, // ( for WP v3.7.1 or below ) 
                'show_in_menu' => false,
            )
        );
                    
    }
    
    /*
     * The sample page and the hidden page
     */
    public function replyToDoSamplePage( $oAdminPage ) {
        
        echo "<p>" . __( 'This is a sample page that has a link to a hidden page created by the framework.', 'admin-page-framework-demo' ) . "</p>";
        $_sLinkToHiddenPage = $oAdminPage->oUtil->getQueryAdminURL( array( 'page' => 'apf_hidden_page' ) );
        echo "<a href='{$_sLinkToHiddenPage}'>" . __( 'Go to Hidden Page', 'admin-page-framework-demo' ). "</a>";
    
    }
    public function replyToDoHiddenPage( $oAdminPage ) {
        
        echo "<p>" . __( 'This is a hidden page.', 'admin-page-framework-demo' ) . "</p>";
        echo "<p>" . __( 'It is useful when you have a setting page that requires a proceeding page.', 'admin-page-framework-demo' ) . "</p>";
        $_sLinkToGoBack = $oAdminPage->oUtil->getQueryAdminURL( array( 'page' => 'apf_sample_page' ) );
        echo "<a href='{$_sLinkToGoBack}'>" . __( 'Go Back', 'admin-page-framework-demo' ). "</a>";
        
    }    

}