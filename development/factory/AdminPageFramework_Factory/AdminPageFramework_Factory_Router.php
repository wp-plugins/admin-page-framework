<?php
/**
 * Admin Page Framework
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2015 Michael Uno; Licensed MIT
 * 
 */

/**
 * Provides routing functionality to the Admin Page Framework factory object based on the fields type.
 * 
 * This class mainly deals with routing function calls and instantiation of objects based on the type.
 * 
 * @abstract
 * @since       3.0.4
 * @package     AdminPageFramework
 * @subpackage  Factory
 * @internal
 * @method      void    start()   User constructor. Defined in `AdminPageFramework_Factory_Controller`.
 * @method      void    _setUp()    
 */
abstract class AdminPageFramework_Factory_Router {
    
    /**
     * Stores the property object.
     * 
     * @since       2.0.0
     * @access      public      The AdminPageFramework_Page_MetaBox class accesses it.
     */     
    public $oProp;    
    
    /**
     * The object that provides the debug methods. 
     * 
     * @internal
     * @access      public
     * @since       2.0.0
     * @since       3.1.0   Changed the scope to public from protected.
     */     
    public $oDebug;
    /**
     * Provides the utility methods. 
     * 
     * @internal
     * @since       2.0.0
     * @since       3.1.0     Changed the scope to public from protected.
     */         
    public $oUtil;
    /**
     * Provides the methods for text messages of the framework. 
     * 
     * @since       2.0.0
     * @since       3.1.0     Changed the scope to public from protected.
     * @access      public
     * @internal
     */         
    public $oMsg;
    
    /**
     * The form object that provides methods to handle form sections and fields.
     * @internal
     * @since       3.0.0
     * @since       3.5.2       Changed the scope to public from protected as the widget class needs to initialize this object.
     */     
    public $oForm;
    
    /**
     * Inserts page load information into the footer area of the page. 
     * 
     */
    protected $oPageLoadInfo;
    
    /**
     * Provides the methods to insert head tag elements.
     * 
     * @since   3.3.0   Changed the name from $oHeadTag as it has become to deal with footer elements.
     */
    protected $oResource;
    
    /**
     * Provides the methods to insert head tag elements.
     * @deprecated
     */
    protected $oHeadTag;
    
    /**
     * Provides methods to manipulate contextual help pane.
     */
    protected $oHelpPane;
    
    /**
     * Provides the methods for creating HTML link elements. 
     * 
     */    
    protected $oLink;
    
    /**
     * Stores sub-class names.
     * 
     * Used in the __get() method to check whether a method with the name of the property should be called or not.
     * 
     * @since       3.5.3
     */
    protected $_aSubClassNames = array(
        'oDebug', 
        'oUtil',
        'oMsg',
        'oForm',
        'oPageLoadInfo',
        'oResource',
        'oHelpPane',
        'oLink',
    );
    
    /**
     * Sets up built-in objects.
     */
    public function __construct( $oProp ) {

        // Let them overload.
        unset( 
            $this->oDebug, 
            $this->oUtil, 
            $this->oMsg, 
            $this->oForm, 
            $this->oPageLoadInfo,
            $this->oResource,
            $this->oHelpPane,
            $this->oLink
        );
        
        // Property object
        $this->oProp = $oProp;
    
        if ( $this->oProp->bIsAdmin && ! $this->oProp->bIsAdminAjax ) {
            add_action( 'current_screen', array( $this, '_replyToLoadComponents' ) );
        }
        
        // Call the start method - defined in the controller class.
        $this->start();    
        
    }    
        
        /**
         * Determines whether the class component classes should be instantiated or not.
         * 
         * @internal
         * @callback    action      current_screen
         * @return      void
         */
        public function _replyToLoadComponents( /* $oScreen */ ) {

            if ( 'plugins.php' === $this->oProp->sPageNow ) {
                // triggers __get() if not set.
                $this->oLink = $this->oLink;
            }
    
            if ( ! $this->_isInThePage() ) { 
                return; 
            }
            
            // Do not load widget resources in the head tag because widgets can be loaded in any page unless it is in customize.php.
            if ( in_array( $this->oProp->_sPropertyType, array( 'widget' ) ) && 'customize.php' !== $this->oProp->sPageNow ) {
                return;
            }
            
            $this->_setSubClasses();
            
        }
            /**
             * Sets sub-classes.
             * 
             * This method forces the overload method __get() to be triggered if those sub-class objects
             * are not set.
             * 
             * @since       3.5.3
             * @internal
             * @return      void
             */
            private function _setSubClasses() {
                $this->oResource        = $this->oResource;
                $this->oHeadTag         = $this->oResource; // backward compatibility                
                $this->oLink            = $this->oLink;
                $this->oPageLoadInfo    = $this->oPageLoadInfo;
            }

    /**
     * Determines whether the class object is instantiatable in the current page.
     * 
     * This method should be redefined in the extended class.
     * 
     * @since       3.1.0
     * @internal
     */ 
    protected function _isInstantiatable() { return true; }
    
    /**
     * Determines whether the instantiated object and its producing elements belong to the loading page.
     * 
     * This method should be redefined in the extended class.
     * 
     * @since       3.0.3
     * @since       3.2.0   Changed the scope to public from protected as the head tag object will access it.
     * @internal
     */
    public function _isInThePage() { return true; }
    
    /**
     * Stores class names by fields type for form element objects.
     * @since       3.5.3
     */    
    protected $_aFormElementClassNameMap = array(
        'page'                  => 'AdminPageFramework_FormElement_Page',
        'network_admin_page'    => 'AdminPageFramework_FormElement_Page',
        'post_meta_box'         => 'AdminPageFramework_FormElement_Meta',
        'page_meta_box'         => 'AdminPageFramework_FormElement',
        'post_type'             => 'AdminPageFramework_FormElement',
        'taxonomy'              => 'AdminPageFramework_FormElement',
        'widget'                => 'AdminPageFramework_FormElement',
        'user_meta'             => 'AdminPageFramework_FormElement_Meta',
    );        
    /**
     * Instantiate a form object based on the type.
     * 
     * @since       3.1.0
     * @internal
     * @return      object|null
     */
    protected function _getFormInstance( $oProp ) {

        if ( 
            in_array( 
                $oProp->sFieldsType, 
                array( 'page', 'network_admin_page', 'post_meta_box', 'post_type' )
            ) 
            && $oProp->bIsAdminAjax 
        ) {
            return null;
        }
        return $this->_getInstanceByMap( 
            $this->_aFormElementClassNameMap,   // map
            $oProp->sFieldsType,    // key
            $oProp->sFieldsType,    // parameter 1
            $oProp->sCapability,    // parameter 2
            $this   // parameter 3
        );
        
    }
    
    /**
     * Stores class names by fields type for help pane objects.
     * @since       3.5.3
     */    
    protected $_aResourceClassNameMap = array(
        'page'                  => 'AdminPageFramework_Resource_Page',
        'network_admin_page'    => 'AdminPageFramework_Resource_Page',
        'post_meta_box'         => 'AdminPageFramework_Resource_MetaBox',
        'page_meta_box'         => 'AdminPageFramework_Resource_MetaBox_Page',
        'post_type'             => 'AdminPageFramework_Resource_PostType',
        'taxonomy'              => 'AdminPageFramework_Resource_TaxonomyField',
        'widget'                => 'AdminPageFramework_Resource_Widget',
        'user_meta'             => 'AdminPageFramework_Resource_UserMeta',
    );        
    /**
     * Instantiate a resource handler object based on the type.
     * 
     * @since       3.0.4
     * @internal
     */
    protected function _getResourceInstance( $oProp ) {
        return $this->_getInstanceByMap( $this->_aResourceClassNameMap, $oProp->sFieldsType, $oProp );    
    }
    
    /**
     * Stores class names by fields type for help pane objects.
     * @since       3.5.3
     */    
    protected $_aHelpPaneClassNameMap = array(
        'page'                  => 'AdminPageFramework_HelpPane_Page',
        'network_admin_page'    => 'AdminPageFramework_HelpPane_Page',
        'post_meta_box'         => 'AdminPageFramework_HelpPane_MetaBox',
        'page_meta_box'         => 'AdminPageFramework_HelpPane_MetaBox_Page',
        'post_type'             => null,    // no help pane class for the post type factory class.
        'taxonomy'              => 'AdminPageFramework_HelpPane_TaxonomyField',
        'widget'                => 'AdminPageFramework_HelpPane_Widget',
        'user_meta'             => 'AdminPageFramework_HelpPane_UserMeta',
    );    
    /**
     * Instantiates a help pane object based on the type.
     * 
     * @since       3.0.4
     * @internal
     */
    protected function _getHelpPaneInstance( $oProp ) {
        return $this->_getInstanceByMap( $this->_aHelpPaneClassNameMap, $oProp->sFieldsType, $oProp );
    }
    
    /**
     * Stores class names by fields type for link objects.
     * @since       3.5.3
     */
    protected $_aLinkClassNameMap = array(
        'page'                  => 'AdminPageFramework_Link_Page',
        'network_admin_page'    => 'AdminPageFramework_Link_NetworkAdmin',
        'post_meta_box'         => null,
        'page_meta_box'         => null,
        'post_type'             => 'AdminPageFramework_Link_PostType', 
        'taxonomy'              => null,
        'widget'                => null,
        'user_meta'             => null,
    );    
    /**
     * Instantiates a link object based on the type.
     * 
     * @since       3.0.4
     * @internal
     */
    protected function _getLinkInstancce( $oProp, $oMsg ) {
        return $this->_getInstanceByMap( $this->_aLinkClassNameMap, $oProp->sFieldsType, $oProp, $oMsg );
    }
    
    /**
     * Stores class names by fields type for page load objects.
     * @since       3.5.3
     */
    protected $_aPageLoadClassNameMap = array(
        'page'                  => 'AdminPageFramework_PageLoadInfo_Page',
        'network_admin_page'    => 'AdminPageFramework_PageLoadInfo_NetworkAdminPage',
        'post_meta_box'         => null,
        'page_meta_box'         => null,
        'post_type'             => 'AdminPageFramework_PageLoadInfo_PostType', 
        'taxonomy'              => null,
        'widget'                => null,
        'user_meta'             => null,
    );
    /**
     * Instantiates a page load object based on the type.
     * 
     * @since 3.0.4
     * @internal
     */
    protected function _getPageLoadInfoInstance( $oProp, $oMsg ) {
        
        if ( ! isset( $this->_aPageLoadClassNameMap[ $oProp->sFieldsType ] ) ) {
            return null;
        }
        $_sClassName = $this->_aPageLoadClassNameMap[ $oProp->sFieldsType ];
        return call_user_func_array( array( $_sClassName, 'instantiate' ), array( $oProp, $oMsg ) );

    }
    
    /**
     * Returns a class object instance by the given map array and the key, plus one or two arguments.
     * 
     * @remark      There is a limitation that only can accept up to 3 parameters at the moment. 
     * @internal
     * @since       3.5.3
     * @return      null|object
     */
    private function _getInstanceByMap( /* array $aClassNameMap, $sKey, $mParam1, $mParam2, $mParam3 */ ) {
        
        $_aParams       = func_get_args();
        $_aClassNameMap = array_shift( $_aParams );
        $_sKey          = array_shift( $_aParams );
        
        if ( ! isset( $_aClassNameMap[ $_sKey ] ) ) {
            return null;
        }
        
        $_iParamCount = count( $_aParams );
        
        // passing more than 3 arguments is not supported at the moment.
        if ( $_iParamCount > 3 ) {
            return null;
        }
        
        // Insert the class name at the beginning of the parameter array.
        array_unshift( $_aParams, $_aClassNameMap[ $_sKey ] );    
        
        // Instantiate the class and return the instance.
        return call_user_func_array( 
            array( $this, "_replyToGetClassInstanceByArgumentOf{$_iParamCount}" ), 
            $_aParams
        );
    
    }
        /**#@+
         * @internal
         * @return      object
         */      
        /**
         * Instantiate a class with zero parameter.
         * @since       3.5.3
         */
        private function _replyToGetClassInstanceByArgumentOf0( $sClassName ) {
            return new $sClassName;
        }    
        /**
         * Instantiate a class with one parameter.
         * @since       3.5.3
         */        
        private function _replyToGetClassInstanceByArgumentOf1( $sClassName, $mArg ) {
            return new $sClassName( $mArg );
        }
        /**
         * Instantiate a class with two parameters.
         * @since       3.5.3
         */             
        private function _replyToGetClassInstanceByArgumentOf2( $sClassName, $mArg1, $mArg2 ) {
            return new $sClassName( $mArg1, $mArg2 );
        }      
        /**
         * Instantiate a class with two parameters.
         * @since       3.5.3
         */             
        private function _replyToGetClassInstanceByArgumentOf3( $sClassName, $mArg1, $mArg2, $mArg3 ) {
            return new $sClassName( $mArg1, $mArg2, $mArg3 );
        }              
        /**#@-*/        
    
    /**
     * Responds to a request of an undefined property.
     * 
     * This is used to instantiate classes only when necessary, rather than instantiating them all at once.
     * 
     * @internal
     */
    public function __get( $sPropertyName ) {
            
        switch( $sPropertyName ) {
            case 'oHeadTag':    // 3.3.0+ for backward compatibility
                $sPropertyName = 'oResource';
                break;
        }     

        // Set and return the sub class object instance.
        if ( in_array( $sPropertyName, $this->_aSubClassNames ) ) {            
            return call_user_func( 
                array( $this, "_replyTpSetAndGetInstance_{$sPropertyName}"  )
            );
        }
        
    }
        /**#@+
         * @internal
         * @return      object
         * @callback    function    call_user_func
         */          
        /**
         * Sets and returns the `oUtil` property.
         * @since       3.5.3
         */
        public function _replyTpSetAndGetInstance_oUtil() {
            $this->oUtil = new AdminPageFramework_WPUtility;
            return $this->oUtil;
        }
        /**
         * Sets and returns the `oDebug` property.
         * @since       3.5.3
         */        
        public function _replyTpSetAndGetInstance_oDebug() {
            $this->oDebug = new AdminPageFramework_Debug;
            return $this->oDebug;
        }
        /**
         * Sets and returns the `oMsg` property.
         * @since       3.5.3
         */              
        public function _replyTpSetAndGetInstance_oMsg() {
            $this->oMsg = AdminPageFramework_Message::getInstance( $this->oProp->sTextDomain );
            return $this->oMsg;
        }
        /**
         * Sets and returns the `oForm` property.
         * @since       3.5.3
         */              
        public function _replyTpSetAndGetInstance_oForm() {
            $this->oForm = $this->_getFormInstance( $this->oProp );
            return $this->oForm;
        }
        /**
         * Sets and returns the `oResouce` property.
         * @since       3.5.3
         */            
        public function _replyTpSetAndGetInstance_oResource() {
            $this->oResource = $this->_getResourceInstance( $this->oProp );
            return $this->oResource;
        }
        /**
         * Sets and returns the `oHelpPane` property.
         * @since       3.5.3
         */
        public function _replyTpSetAndGetInstance_oHelpPane() {
            $this->oHelpPane = $this->_getHelpPaneInstance( $this->oProp );
            return $this->oHelpPane;
        }
        /**
         * Sets and returns the `oLink` property.
         * @since       3.5.3
         */
        public function _replyTpSetAndGetInstance_oLink() {
            $this->oLink = $this->_getLinkInstancce( $this->oProp, $this->oMsg );
            return $this->oLink;
        }
        /**
         * Sets and returns the `oPageLoadInfo` property.
         * @since       3.5.3
         */        
        public function _replyTpSetAndGetInstance_oPageLoadInfo() {
            $this->oPageLoadInfo = $this->_getPageLoadInfoInstance( $this->oProp, $this->oMsg );
            return $this->oPageLoadInfo;
        }
        /**#@-*/
        
    /**
     * Redirects dynamic function calls to the pre-defined internal method.
     * 
     * @internal
     * @todo        Introduce "set_up_pre_{ class name }" action hook.
     */
    public function __call( $sMethodName, $aArgs=null ) {    
         
        $_mFirstArg = $this->oUtil->getElement( $aArgs, 0 );
        
        switch ( $sMethodName ) {
            case 'validate':
            case 'content':
                return $_mFirstArg;
            case 'setup_pre':
                $this->_setUp();
                
                // This action hook must be called AFTER the _setUp() method as there are callback methods that hook into this hook and assumes required configurations have been made.
                $this->oUtil->addAndDoAction( 
                    $this, 
                    "set_up_{$this->oProp->sClassName}", 
                    $this 
                );
                $this->oProp->_bSetupLoaded = true;            
                return;
        }
        
        if ( has_filter( $sMethodName ) ) {
            return $_mFirstArg;
        }
                
        trigger_error( 
            'Admin Page Framework: ' . ' : ' . sprintf( 
                __( 'The method is not defined: %1$s', $this->oProp->sTextDomain ),
                $sMethodName 
            ), 
            E_USER_WARNING 
        );
        
    }     
    
    /**
     * Called when the object is called as a string.
     *
     * Field definition arrays contain the factory object reference and when the debug log method tries to dump it, the output gets too long.
     * So shorten it here.
     * 
     * @since       3.4.4
     */   
    public function __toString() {
        
        $_iCount     = count( get_object_vars( $this ) );
        $_sClassName = get_class( $this );
        return '(object) ' . $_sClassName . ': ' . $_iCount . ' properties.';
        
    }
 
}