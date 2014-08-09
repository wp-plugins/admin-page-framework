<?php
/**
 * Admin Page Framework
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2014 Michael Uno; Licensed MIT
 * 
 */
if ( ! class_exists( 'AdminPageFramework_Link_Page' ) ) :
/**
 * Provides methods for HTML link elements for admin pages created by the framework, except the pages of custom post types.
 *
 * Embeds links in the footer and plugin's listing table etc.
 * 
 * @since			2.0.0
 * @since			3.0.0			Changed the name to AdminPageFramework_Link_Page_Page from AdminPageFramework_Link_Page.
 * @extends			AdminPageFramework_Link_Base
 * @package			AdminPageFramework
 * @subpackage		Link
 * @internal
 */
class AdminPageFramework_Link_Page extends AdminPageFramework_Link_Base {
	
	/**
	 * The property object, commonly shared.
	 * @since			2.0.0
	 */ 
	private $oProp;
	
	public function __construct( &$oProp, $oMsg=null ) {
		
		if ( ! $oProp->bIsAdmin ) return;
		
		$this->oProp = $oProp;
		$this->oMsg = $oMsg;
		$this->oProp->sLabelPluginSettingsLink = $this->oMsg->__( 'settings' );
		
		// Add script info into the footer 
		add_filter( 'update_footer', array( $this, '_replyToAddInfoInFooterRight' ), 11 );
		add_filter( 'admin_footer_text' , array( $this, '_replyToAddInfoInFooterLeft' ) );	
		$this->_setFooterInfoLeft( $this->oProp->aScriptInfo, $this->oProp->aFooterInfo['sLeft'] );
		$_aLibraryData = AdminPageFramework_Property_Base::_getLibraryData();
		$_aLibraryData['sVersion'] = $this->oProp->bIsMinifiedVersion ? $_aLibraryData['sVersion'] . '.min' : $_aLibraryData['sVersion'];
		$this->_setFooterInfoRight( $_aLibraryData, $this->oProp->aFooterInfo['sRight'] );
	
		if ( 'plugin' == $this->oProp->aScriptInfo['sType'] ) {
			add_filter( 'plugin_action_links_' . plugin_basename( $this->oProp->aScriptInfo['sPath'] ) , array( $this, '_replyToAddSettingsLinkInPluginListingPage' ) );
		}

	}
			
	/*
	 * Methods for embedding links 
	 * 
	 */
	/**
	 * @internal
	 */
	public function _addLinkToPluginDescription( $linkss ) {
		
		if ( !is_array( $linkss ) )
			$this->oProp->aPluginDescriptionLinks[] = $linkss;
		else
			$this->oProp->aPluginDescriptionLinks = array_merge( $this->oProp->aPluginDescriptionLinks , $linkss );
	
		add_filter( 'plugin_row_meta', array( $this, '_replyToAddLinkToPluginDescription' ), 10, 2 );

	}
	public function _addLinkToPluginTitle( $linkss ) {
		
		if ( !is_array( $linkss ) )
			$this->oProp->aPluginTitleLinks[] = $linkss;
		else
			$this->oProp->aPluginTitleLinks = array_merge( $this->oProp->aPluginTitleLinks, $linkss );
		
		add_filter( 'plugin_action_links_' . plugin_basename( $this->oProp->aScriptInfo['sPath'] ), array( $this, '_replyToAddLinkToPluginTitle' ) );

	}
	
	/**
	 * 
	 * @since			2.0.0
	 * @remark			A callback for the filter hook, <em>admin_footer_text</em>.
	 */ 
	public function _replyToAddInfoInFooterLeft( $sLinkHTML='' ) {

		if ( ! isset( $_GET['page'] ) || ! $this->oProp->isPageAdded( $_GET['page'] )  ) 
			return $sLinkHTML;	// $sLinkHTML is given by the hook.
		
		if ( empty( $this->oProp->aScriptInfo['sName'] ) ) return $sLinkHTML;
		
		return $this->oProp->aFooterInfo['sLeft'];

	}
	public function _replyToAddInfoInFooterRight( $sLinkHTML='' ) {

		if ( ! isset( $_GET['page'] ) || ! $this->oProp->isPageAdded( $_GET['page'] )  ) 
			return $sLinkHTML;	// $sLinkTHML is given by the hook.
			
		return $this->oProp->aFooterInfo['sRight'];
			
	}
	
	public function _replyToAddSettingsLinkInPluginListingPage( $aLinks ) {
 		
		if ( count( $this->oProp->aPages ) < 1 ) return $aLinks;	// if the sub-pages are not added, do nothing.
		
		// For a custom root slug,
		$sLinkURL = preg_match( '/^.+\.php/', $this->oProp->aRootMenu['sPageSlug'] ) 
			? add_query_arg( array( 'page' => $this->oProp->sDefaultPageSlug ), admin_url( $this->oProp->aRootMenu['sPageSlug'] ) )
			: "admin.php?page={$this->oProp->sDefaultPageSlug}";
		
		// If the user disables the settings link, the label property is empty. If so, do not add it.
		if ( ! $this->oProp->sLabelPluginSettingsLink ) {
			return $aLinks;
		}
		
		array_unshift(	
			$aLinks,
			'<a href="' . $sLinkURL . '">' . $this->oProp->sLabelPluginSettingsLink . '</a>'
		); 
		return $aLinks;
		
	}	
	
	public function _replyToAddLinkToPluginDescription( $aLinks, $sFile ) {

		if ( $sFile != plugin_basename( $this->oProp->aScriptInfo['sPath'] ) ) return $aLinks;
		
		// Backward compatibility sanitization.
		$aAddingLinks = array();
		foreach( $this->oProp->aPluginDescriptionLinks as $linksHTML )
			if ( is_array( $linksHTML ) )	// should not be an array
				$aAddingLinks = array_merge( $linksHTML, $aAddingLinks );
			else
				$aAddingLinks[] = ( string ) $linksHTML;
		
		return array_merge( $aLinks, $aAddingLinks );
		
	}			
	public function _replyToAddLinkToPluginTitle( $aLinks ) {

		// Backward compatibility sanitization.
		$aAddingLinks = array();
		foreach( $this->oProp->aPluginTitleLinks as $linksHTML )
			if ( is_array( $linksHTML ) )	// should not be an array
				$aAddingLinks = array_merge( $linksHTML, $aAddingLinks );
			else
				$aAddingLinks[] = ( string ) $linksHTML;
		
		return array_merge( $aLinks, $aAddingLinks );
		
	}		
}
endif;