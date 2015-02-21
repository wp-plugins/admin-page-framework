<?php
/**
 * Admin Page Framework
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2015 Michael Uno; Licensed MIT
 * 
 */

/**
 * Handles routing of function calls and instantiation of associated classes.
 *
 * @abstract
 * @since           3.5.0
 * @package         AdminPageFramework
 * @subpackage      TaxonomyField
 * @internal
 */
abstract class AdminPageFramework_TaxonomyField_Router extends AdminPageFramework_Factory {
    
    
    /**
     * Sets up hooks.
     * 
     * @since       3.5.0
     */
    public function __construct( $oProp ) {
                
        parent::__construct( $oProp );

        if ( $this->oProp->bIsAdmin ) {
            add_action( 'wp_loaded', array( $this, '_replyToDetermineToLoad' ) );
        }        
        
        
    }
  
    /**
     * Determines whether the taxonomy fields belong to the loading page.
     * 
     * @internal
     * @since       3.0.3
     * @since       3.2.0       Changed the scope to public from protected as the head tag object will access it.
     * @since       3.5.0       Moved from `AdminPageFramework_TaxonomyField`.
     */
    public function _isInThePage() {

        if ( 'admin-ajax.php' == $this->oProp->sPageNow ) {
            return true;
        }    
        
        if ( 'edit-tags.php' != $this->oProp->sPageNow ) { 
            return false; 
        }
        
        if ( isset( $_GET['taxonomy'] ) && ! in_array( $_GET['taxonomy'], $this->oProp->aTaxonomySlugs ) ) {
            return false;
        }        
        
        return true;
  
    }    
    
    /**
     * Determines whether the meta box should be loaded in the currently loading page.
     * 
     * @since       3.0.3
     * @since       3.5.0       Moved from `AdminPageFramework_TaxonomyField`.
     * @internal
     */
    public function _replyToDetermineToLoad( $oScreen ) {
        
        if ( ! $this->_isInThePage() ) { return; }
        
        // @todo introduce "set_up_pre_{ class name }" action hook.
        $this->_setUp();
        
        // This action hook must be called AFTER the _setUp() method as there are callback methods that hook into this hook and assumes required configurations have been made.
        $this->oUtil->addAndDoAction( $this, "set_up_{$this->oProp->sClassName}", $this );
        
        $this->oProp->_bSetupLoaded = true;
        
        add_action( 'current_screen', array( $this, '_replyToRegisterFormElements' ), 20 ); // the screen object should be established to detect the loaded page. 
        
        foreach( $this->oProp->aTaxonomySlugs as $__sTaxonomySlug ) {     
            
            /* Validation callbacks need to be set regardless of whether the current page is edit-tags.php or not */
            add_action( "created_{$__sTaxonomySlug}", array( $this, '_replyToValidateOptions' ), 10, 2 );
            add_action( "edited_{$__sTaxonomySlug}", array( $this, '_replyToValidateOptions' ), 10, 2 );

            // if ( $GLOBALS['pagenow'] != 'admin-ajax.php' && $GLOBALS['pagenow'] != 'edit-tags.php' ) continue;
            add_action( "{$__sTaxonomySlug}_add_form_fields", array( $this, '_replyToPrintFieldsWOTableRows' ) );
            add_action( "{$__sTaxonomySlug}_edit_form_fields", array( $this, '_replyToPrintFieldsWithTableRows' ) );
            
            add_filter( "manage_edit-{$__sTaxonomySlug}_columns", array( $this, '_replyToManageColumns' ), 10, 1 );
            add_filter( "manage_edit-{$__sTaxonomySlug}_sortable_columns", array( $this, '_replyToSetSortableColumns' ) );
            add_action( "manage_{$__sTaxonomySlug}_custom_column", array( $this, '_replyToPrintColumnCell' ), 10, 3 );
            
        }     
        
    }      
  
}