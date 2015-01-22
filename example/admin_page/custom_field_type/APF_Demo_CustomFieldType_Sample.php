<?php
/**
 * Admin Page Framework Loader
 * 
 * Demonstrates the usage of Admin Page Framework.
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2015 Michael Uno; Licensed GPLv2
 * 
 */

/**
 * Adds a tab of the set page to the loader plugin.
 * 
 * @since       3.5.0    
 */
class APF_Demo_CustomFieldType_Sample {

    public function __construct( $oFactory, $sPageSlug, $sTabSlug ) {
    
        $this->oFactory     = $oFactory;
        $this->sClassName   = $oFactory->oProp->sClassName;
        $this->sPageSlug    = $sPageSlug; 
        $this->sTabSlug     = $sTabSlug;
        $this->sSectionID   = $this->sTabSlug;
                
        $this->_addTab();
    
    }
    
    private function _addTab() {
        
        $this->oFactory->addInPageTabs(    
            $this->sPageSlug, // target page slug
            array(
                'tab_slug'      => $this->sTabSlug,
                'title'         => __( 'Sample', 'admin-page-framework-loader' ),
            )
        );  
        
        // load + page slug + tab slug
        add_action( 'load_' . $this->sPageSlug . '_' . $this->sTabSlug, array( $this, 'replyToLoadTab' ) );
  
    }
    
    /**
     * Triggered when the tab is loaded.
     */
    public function replyToLoadTab( $oAdminPage ) {
        
        
        $this->registerFieldTypes( $this->sClassName );
        
        add_action( 'do_' . $this->sPageSlug . '_' . $this->sTabSlug, array( $this, 'replyToDoTab' ) );
        
         // Section
        $oAdminPage->addSettingSections(    
            $this->sPageSlug, // the target page slug                
            array(
                'section_id'    => $this->sSectionID,
                'tab_slug'      => $this->sTabSlug,
                'title'         => __( 'Sample Custom Field Type', 'admin-page-framework-demo' ),
                'description'   => __( 'This is just an example of creating a custom field type with Admin Page Framework.', 'admin-page-framework-demo' ),     
            )            
        );        
                    
        // Fields   
        $oAdminPage->addSettingFields(
            $this->sSectionID,
            array(
                'field_id'      => 'sample_field',
                'type'          => 'sample',
                'title'         => __( 'Sample', 'admin-page-framework-demo' ),
                'description'   => __( 'This sample custom field demonstrates how to display a certain element after selecting a radio button.', 'admin-page-framework-demo' ),
                // 'default' => 'red',
                'label'         => array(
                    'red'   => __( 'Red', 'admin-page-framework-demo' ),
                    'blue'  => __( 'Blue', 'admin-page-framework-demo' ),
                    'green' => __( 'Green', 'admin-page-framework-demo' ),
                ),
                'reveal'        => array( // the field type specific key. This is defined in the
                    'red'   => '<p style="color:red;">' . __( 'You selected red!', 'admin-page-framework-demo' ) . '</p>',
                    'blue'  => '<p style="color:blue;">' . __( 'You selected blue!', 'admin-page-framework-demo' ) . '</p>',
                    'green' => '<p style="color:green;">' . __( 'You selected green!', 'admin-page-framework-demo' ) . '</p>',
                ),
            ),
            array(
                'field_id'  => 'sample_field_repeatable',
                'type'      => 'sample',
                'title'     => __( 'Sample', 'admin-page-framework-demo' ),
                // 'default' => 'red',
                'label' => array(
                    'red'   => __( 'Red', 'admin-page-framework-demo' ),
                    'blue'  => __( 'Blue', 'admin-page-framework-demo' ),
                    'green' => __( 'Green', 'admin-page-framework-demo' ),
                ),
                'reveal' => array( // the field type specific key. This is defined in the
                    'red'   => '<p style="color:red;">' . __( 'You selected red!', 'admin-page-framework-demo' ) . '</p>',
                    'blue'  => '<p style="color:blue;">' . __( 'You selected blue!', 'admin-page-framework-demo' ) . '</p>',
                    'green' => '<p style="color:green;">' . __( 'You selected green!', 'admin-page-framework-demo' ) . '</p>',
                ),
                'repeatable' => true,
            )    
        );  
 
    }
    
        /**
         * Registers the field types.
         */
        private function registerFieldTypes( $sClassName ) {
            new SampleCustomFieldType( $sClassName );                             
            
        }    
            
    
    public function replyToDoTab() {        
        submit_button();
    }
    
}