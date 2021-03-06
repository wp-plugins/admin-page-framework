<?php
/**
 * Admin Page Framework Loader
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2015 Michael Uno; Licensed GPLv2
 * 
 */

/**
 * Adds the Tool page to the loader plugin.
 * 
 * @since       3.5.0       
 * @since       3.5.3       Extends `AdminPageFrameworkLoader_AdminPage_Page_Base`.
 * @extends     AdminPageFrameworkLoader_AdminPage_Page_Base
 */
class AdminPageFrameworkLoader_AdminPage_Tool extends AdminPageFrameworkLoader_AdminPage_Page_Base {


    /**
     * A user constructor.
     * 
     * @since       3.5.3
     * @return      void
     */
    public function construct( $oFactory ) {
        
        // Tabs
        new AdminPageFrameworkLoader_AdminPage_Tool_Generator( 
            $this->oFactory,
            $this->sPageSlug,
            array( 
                'tab_slug'  => 'generator',
                'title'     => __( 'Generator', 'admin-page-framework-loader' ),
            )
        );        
        // @deprecated 3.5.4    Use the component generator instead.
        // new AdminPageFrameworkLoader_AdminPage_Tool_Minifier( 
            // $this->oFactory,
            // $this->sPageSlug,
            // array( 
                // 'tab_slug'  => 'minifier',
                // 'title'     => __( 'Minifier', 'admin-page-framework-loader' ),
            // )
        // );
            
    }   
        
}
