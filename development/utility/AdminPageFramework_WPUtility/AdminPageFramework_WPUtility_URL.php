<?php
/**
 * Admin Page Framework
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2014 Michael Uno; Licensed MIT
 * 
 */
if ( ! class_exists( 'AdminPageFramework_WPUtility_URL' ) ) :
/**
 * Provides utility methods handing urls which use WordPress functions and classes.
 *
 * @since			2.0.0
 * @extends			AdminPageFramework_Utility
 * @package			AdminPageFramework
 * @subpackage		Utility
 * @internal
 */
class AdminPageFramework_WPUtility_URL extends AdminPageFramework_Utility {

	/**
	 * Retrieves the current URL in the admin page.
	 * 
	 * @since			2.1.1
	 */
	public function getCurrentAdminURL() {
		
		$sRequestURI = $GLOBALS['is_IIS'] ? $_SERVER['PATH_INFO'] : $_SERVER["REQUEST_URI"];
		$sPageURL = ( @$_SERVER["HTTPS"] == "on" ) ? "https://" : "http://";
		
		if ( $_SERVER["SERVER_PORT"] != "80" ) 
			$sPageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $sRequestURI;
		else 
			$sPageURL .= $_SERVER["SERVER_NAME"] . $sRequestURI;
		
		return $sPageURL;
		
	}
	
	/**
	 * Returns a url with modified query stings.
	 * 
	 * Identical to the getQueryURL() method except that if the third parameter is omitted, it will use the currently browsed admin url.
	 * 
	 * @since			2.1.2
	 * @param			array			$aAddingQueries			The appending query key value pairs e.g. array( 'page' => 'my_page_slug', 'tab' => 'my_tab_slug' )
	 * @param			array			$aRemovingQueryKeys		( optional ) The removing query keys. e.g. array( 'settings-updated', 'my-custom-admin-notice' )
	 * @param			string			$sSubjectURL				( optional ) The subject url to modify
	 * @return			string			The modified url.
	 */
	public function getQueryAdminURL( $aAddingQueries, $aRemovingQueryKeys=array(), $sSubjectURL='' ) {
		
		$sSubjectURL = $sSubjectURL ? $sSubjectURL : add_query_arg( $_GET, admin_url( $GLOBALS['pagenow'] ) );
		return $this->getQueryURL( $aAddingQueries, $aRemovingQueryKeys, $sSubjectURL );
		
	}
	/**
	 * Returns a url with modified query stings.
	 * 
	 * @since			2.1.2
	 * @param			array			$aAddingQueries			The appending query key value pairs
	 * @param			array			$aRemovingQueryKeys			The removing query key value pairs
	 * @param			string			$sSubjectURL				The subject url to modify
	 * @return			string			The modified url.
	 */
	public function getQueryURL( $aAddingQueries, $aRemovingQueryKeys, $sSubjectURL ) {
		
		// Remove Queries
		$sSubjectURL = empty( $aRemovingQueryKeys ) 
			? $sSubjectURL 
			: remove_query_arg( ( array ) $aRemovingQueryKeys, $sSubjectURL );
			
		// Add Queries
		$sSubjectURL = add_query_arg( $aAddingQueries, $sSubjectURL );
		
		return $sSubjectURL;
		
	}	

	/**
	 * Calculates the URL from the given path.
	 * 
	 * @since			2.1.5
	 * @static
	 * @access			public
	 * @return			string			The source url
	 */
	static public function getSRCFromPath( $sFilePath ) {
						
		$oWPStyles = new WP_Styles();	// It doesn't matter whether the file is a style or not. Just use the built-in WordPress class to calculate the SRC URL.
		$sRelativePath = AdminPageFramework_Utility::getRelativePath( ABSPATH, $sFilePath );		
		$sRelativePath = preg_replace( "/^\.[\/\\\]/", '', $sRelativePath, 1 );	// removes the heading ./ or .\ 
		$sHref = trailingslashit( $oWPStyles->base_url ) . $sRelativePath;
		unset( $oWPStyles );	// for PHP 5.2.x or below
		return esc_url( $sHref );		
		
	}	

	/**
	 * Resolves the given src.
	 * 
	 * Checks if the given string is a url, a relative path, or an absolute path and returns the url if it's not a relative path.
	 * 
	 * @since			2.1.5
	 * @since			2.1.6			Moved from the AdminPageFramework_HeadTag_Base class. Added the $bReturnNullIfNotExist parameter.
	 */
	static public function resolveSRC( $sSRC, $bReturnNullIfNotExist=false ) {	

		if ( ! $sSRC )	
			return $bReturnNullIfNotExist ? null : $sSRC;
			
		// It is a url
		if ( filter_var( $sSRC, FILTER_VALIDATE_URL ) )
			return $sSRC;

		// If the file exists, it means it is an absolute path. If so, calculate the URL from the path.
		if ( file_exists( realpath( $sSRC ) ) )
			return self::getSRCFromPath( $sSRC );
		
		if ( $bReturnNullIfNotExist )
			return null;
		
		// Otherwise, let's assume the string is a relative path 'to the WordPress installed absolute path'.
		return $sSRC;
		
	}	
	
}
endif;