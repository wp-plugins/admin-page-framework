<?php
/**
 * Admin Page Framework
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2014 Michael Uno; Licensed MIT
 * 
 */
if ( ! class_exists( 'AdminPageFramework_Factory_Router' ) ) :
/**
 * Provides the routing functionality to the Admin Page Framework factory object based on the fields type.
 * 
 * This class mainly deals with routing function calls and instantiation of objects based on the type.
 * 
 * @abstract
 * @since			3.0.4
 * @subpackage		Factory
 * @internal
 */
abstract class AdminPageFramework_Factory_Router {
	
	/**
	 * Stores the property object.
 	 * @internal
	 * @since			2.0.0
	 * @acess			public			The AdminPageFramework_Page_MetaBox class accesses it.
	 */ 	
	public $oProp;	
	/**
	* @internal
	* @access			public
	* @since			2.0.0
	* @since			3.1.0			Changed the scope to public from protected.
	*/ 	
	public $oDebug;
	/**
	* @internal
	* @since			2.0.0
	* @since			3.1.0			Changed the scope to public from protected.
	*/ 		
	public $oUtil;
	/**
	* @since			2.0.0
	* @access			public
	* @internal
	* @since			3.1.0			Changed the scope to public from protected.
	*/ 		
	public $oMsg;	
	
	/**
	* @internal
	* @since			3.0.0
	*/ 	
	protected $oForm;	
	
	/**
	 * Stores the page load info object
	 * 
	 */
	protected $oPageLoadInfo;
	
	/**
	 * 
	 */
	protected $oHeadTag;
	
	protected $oHelpPane;
	
	protected $oLink;
	
	/**
	 * Sets up built-in objects.
	 */
	function __construct( $oProp ) {

		// Objects - Utility
		$this->oUtil = new AdminPageFramework_WPUtility;
		$this->oDebug = new AdminPageFramework_Debug;
	
		// Objects - Model
		$this->oProp = $oProp;
		$this->oMsg = AdminPageFramework_Message::instantiate( $oProp->sTextDomain );

		if ( $this->_isInThePage() ) :
	
			// Objects - Model
			$this->oForm = $this->_getFormInstance( $oProp );
		
			// Objects - Control
			$this->oHeadTag = $this->_getHeadTagInstance( $oProp );
			$this->oHelpPane = $this->_getHelpPaneInstance( $oProp );
			
			// Objects - View
			$this->oLink = $this->_getLinkInstancce( $oProp, $this->oMsg );
			$this->oPageLoadInfo = $this->_getPageLoadInfoInstance( $oProp, $this->oMsg );
			
		endif;
		
		// Call the start method.
		$this->start();	// defined in the controller class.
		
	}	
	
	/**
	 * Determines whether the class object is instantiatable in the current page.
	 * 
	 * @since			3.1.0
	 * @internal
	 */ 
	protected function _isInstantiatabe() { return true; }
	
	/**
	 * Determines whether the instantiated object and its producing elements belong to the loading page.
	 * 
	 * This method should be redefined in the extended class.
	 * 
	 * @since			3.0.3
	 * @internal
	 */
	protected function _isInThePage() { return true; }			

	
	/*
	 * Route
	 */
	/**
	 * Instantiate a form object based on the type.
	 * 
	 * @since			3.1.0
	 */
	protected function _getFormInstance( $oProp ) {
		
		switch ( $oProp->sFieldsType ) {
			case 'page':
			case 'network_admin_page':
				return new AdminPageFramework_FormElement_Page( $oProp->sFieldsType, $oProp->sCapability );
			case 'post_meta_box':
			case 'page_meta_box':
			case 'post_type':
			case 'taxonomy':
				return new AdminPageFramework_FormElement( $oProp->sFieldsType, $oProp->sCapability );
	
		}		
		
	}
	
	/**
	 * Instantiate a head tag object based on the type.
	 * 
	 * @since			3.0.4
	 * @internal
	 */
	protected function _getHeadTagInstance( $oProp ) {
		
		switch ( $oProp->sFieldsType ) {
			case 'page':
			case 'network_admin_page':
				return new AdminPageFramework_HeadTag_Page( $oProp );
			case 'post_meta_box':
				return new AdminPageFramework_HeadTag_MetaBox( $oProp );
			case 'page_meta_box':
				return new AdminPageFramework_HeadTag_MetaBox_Page( $oProp );				
			case 'post_type':
				return new AdminPageFramework_HeadTag_PostType( $oProp );
			case 'taxonomy':
				return new AdminPageFramework_HeadTag_TaxonomyField( $oProp );
	
		}
		
	}
	
	/**
	 * Instantiates a help pane object based on the type.
	 * 
	 * @since			3.0.4
	 * @internal
	 */
	protected function _getHelpPaneInstance( $oProp ) {

		switch ( $oProp->sFieldsType ) {
			case 'page':
			case 'network_admin_page':
				return new AdminPageFramework_HelpPane_Page( $oProp );
			case 'post_meta_box':
				return new AdminPageFramework_HelpPane_MetaBox( $oProp );
			case 'page_meta_box':
				return new AdminPageFramework_HelpPane_MetaBox_Page( $oProp );
			case 'post_type':
				return null;	// no help pane class for the post type factory class.
			case 'taxonomy':
				return new AdminPageFramework_HelpPane_TaxonomyField( $oProp );
		}			
	}
	
	/**
	 * Instantiates a link object based on the type.
	 * 
	 * @since			3.0.4
	 * @internal
	 */
	protected function _getLinkInstancce( $oProp, $oMsg ) {
		
		switch ( $oProp->sFieldsType ) {
			case 'page':
			case 'network_admin_page':
				return new AdminPageFramework_Link_Page( $oProp, $oMsg );
			case 'post_meta_box':
				return null;
			case 'page_meta_box':
				return null;
			case 'post_type':
				return new AdminPageFramework_Link_PostType( $oProp, $oMsg );
			case 'taxonomy':
				return null;
		}		
		
	}
	
	/**
	 * Instantiates a page load object based on the type.
	 * 
	 * @since			3.0.4
	 * @internal
	 */
	protected function _getPageLoadInfoInstance( $oProp, $oMsg ) {
		
		switch ( $oProp->sFieldsType ) {
			case 'page':
				return AdminPageFramework_PageLoadInfo_Page::instantiate( $oProp, $oMsg );
			case 'network_admin_page':
				return AdminPageFramework_PageLoadInfo_NetworkAdminPage::instantiate( $oProp, $oMsg );
			case 'post_meta_box':
				return null;
			case 'page_meta_box':
				return null;
			case 'post_type':
				return AdminPageFramework_PageLoadInfo_PostType::instantiate( $oProp, $oMsg );
			case 'taxonomy':
				return null;
		}		
		
	}
	
	/**
	 * Redirects dynamic function calls to the pre-defined internal method.
	 * 
	 */
	function __call( $sMethodName, $aArgs=null ) {	

		// the start_ action hook.
		if ( $sMethodName == 'start_' . $this->oProp->sClassName ) return;

		// the section_{class name}_{...} filter. [3.0.0+]
		if ( substr( $sMethodName, 0, strlen( 'section_head_' . $this->oProp->sClassName . '_' ) ) == 'section_head_' . $this->oProp->sClassName . '_' ) return $aArgs[ 0 ];
		
		// the field_{class name}_{...} filter.
		if ( substr( $sMethodName, 0, strlen( 'field_' . $this->oProp->sClassName . '_' ) ) == 'field_' . $this->oProp->sClassName . '_' ) return $aArgs[ 0 ];

		// the options_ + class name filter. [3.1.0+]
		if ( substr( $sMethodName, 0, strlen( "options_{$this->oProp->sClassName}" ) ) == "options_{$this->oProp->sClassName}" ) return $aArgs[ 0 ];
		
		// the field_types_ + class name filter. [2.1.5+]
		if ( substr( $sMethodName, 0, strlen( "field_types_{$this->oProp->sClassName}" ) ) == "field_types_{$this->oProp->sClassName}" ) return $aArgs[ 0 ];

		// the field_types_ + class name filter. [3.0.2+]
		if ( substr( $sMethodName, 0, strlen( "field_definition_{$this->oProp->sClassName}" ) ) == "field_definition_{$this->oProp->sClassName}" ) return $aArgs[ 0 ];
		
		// the script_common + class name filter. [3.0.0+]
		if ( substr( $sMethodName, 0, strlen( "script_common_{$this->oProp->sClassName}" ) ) == "script_common_{$this->oProp->sClassName}" ) return $aArgs[ 0 ];

		// the script_ + class name filter.
		if ( substr( $sMethodName, 0, strlen( "script_{$this->oProp->sClassName}" ) ) == "script_{$this->oProp->sClassName}" ) return $aArgs[ 0 ];

		// the style_ie_common_ + class name filter. [3.0.0+]
		if ( substr( $sMethodName, 0, strlen( "style_ie_common_{$this->oProp->sClassName}" ) ) == "style_ie_common_{$this->oProp->sClassName}" ) return $aArgs[ 0 ];
			
		// the style_common + class name filter. [3.0.0+]
		if ( substr( $sMethodName, 0, strlen( "style_common_{$this->oProp->sClassName}" ) ) == "style_common_{$this->oProp->sClassName}" ) return $aArgs[ 0 ];

		// the style_ie + class name filter.
		if ( substr( $sMethodName, 0, strlen( "style_ie_{$this->oProp->sClassName}" ) ) == "style_ie_{$this->oProp->sClassName}" ) return $aArgs[ 0 ];
					
		// the style_ + class name filter.
		if ( substr( $sMethodName, 0, strlen( "style_{$this->oProp->sClassName}" ) ) == "style_{$this->oProp->sClassName}" ) return $aArgs[ 0 ];
						
		// the validation_ + class name	filter.
		if ( substr( $sMethodName, 0, strlen( "validation_{$this->oProp->sClassName}" ) ) == "validation_{$this->oProp->sClassName}" ) return $aArgs[ 0 ];

		// the content_{metabox id} filter. [3.0.0+]
		if ( substr( $sMethodName, 0, strlen( "content_{$this->oProp->sClassName}" ) ) == "content_{$this->oProp->sClassName}" ) return $aArgs[ 0 ];
			
		// do_{meta box id} [3.0.0.+]
		if ( substr( $sMethodName, 0, strlen( "do_{$this->oProp->sClassName}" ) ) == "do_{$this->oProp->sClassName}" ) return;
			
		trigger_error( 'Admin Page Framework: ' . ' : ' . sprintf( __( 'The method is not defined: %1$s', $this->oProp->sTextDomain ), $sMethodName ), E_USER_ERROR );
		
	}		
		
}
endif;