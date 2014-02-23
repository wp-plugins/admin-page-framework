<?php
/**
 * Admin Page Framework
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2014 Michael Uno; Licensed MIT
 * 
 */
if ( ! class_exists( 'AdminPageFramework_PageLoadInfo_Base' ) ) :
/**
 * Collects data of page loads in admin pages.
 *
 * @since			2.1.7
 * @package			AdminPageFramework
 * @subpackage		Debug
 * @internal
 */
abstract class AdminPageFramework_PageLoadInfo_Base {
	
	function __construct( $oProp, $oMsg ) {
		
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			
			$this->oProp = $oProp;
			$this->oMsg = $oMsg;
			$this->nInitialMemoryUsage = memory_get_usage();
			add_action( 'admin_menu', array( $this, '_replyToSetPageLoadInfoInFooter' ), 999 );	// must be loaded after the sub pages are registered
						
		}

	}
	
	/**
	 * @remark			Should be overridden in an extended class.
	 */
	public function _replyToSetPageLoadInfoInFooter() {}
		
	/**
	 * Display gathered information.
	 *
	 * @access			public
	 * @internal
	 */
	public function _replyToGetPageLoadInfo( $sFooterHTML ) {
		
		// Get values we're displaying
		$nSeconds 				= timer_stop( 0 );
		$nQueryCount 			= get_num_queries();
		$nMemoryUsage 			= round( $this->_convertBytesToHR( memory_get_usage() ), 2 );
		$nMemoryPeakUsage 		= round( $this->_convertBytesToHR( memory_get_peak_usage() ), 2 );
		$nMemoryLimit 			= round( $this->_convertBytesToHR( $this->_convertToNumber( WP_MEMORY_LIMIT ) ), 2 );
		$sInitialMemoryUsage	= round( $this->_convertBytesToHR( $this->nInitialMemoryUsage ), 2 );
				
		$sOutput = 
			"<div id='admin-page-framework-page-load-stats'>"
				. "<ul>"
					. "<li>" . sprintf( $this->oMsg->__( 'queries_in_seconds' ), $nQueryCount, $nSeconds ) . "</li>"
					. "<li>" . sprintf( $this->oMsg->__( 'out_of_x_memory_used' ), $nMemoryUsage, $nMemoryLimit, round( ( $nMemoryUsage / $nMemoryLimit ), 2 ) * 100 . '%' ) . "</li>"
					. "<li>" . sprintf( $this->oMsg->__( 'peak_memory_usage' ), $nMemoryPeakUsage ) . "</li>"
					. "<li>" . sprintf( $this->oMsg->__( 'initial_memory_usage' ), $sInitialMemoryUsage ) . "</li>"
				. "</ul>"
			. "</div>";
		return $sFooterHTML . $sOutput;
		
	}

	/**
	 * Transforms the php.ini notation for numbers (like '2M') to an integer
	 *
	 * @access			private
	 * @param			$size
	 * @return			int
	 * @remark			This is influenced by the work of Mike Jolley.
	 * @see				http://mikejolley.com/projects/wp-page-load-stats/
	 * @internal
	 */
	private function _convertToNumber( $size ) {
		$l 		= substr( $size, -1 );
		$ret 	= substr( $size, 0, -1 );
		switch( strtoupper( $l ) ) {
			case 'P':
				$ret *= 1024;
			case 'T':
				$ret *= 1024;
			case 'G':
				$ret *= 1024;
			case 'M':
				$ret *= 1024;
			case 'K':
				$ret *= 1024;
		}
		return $ret;
	}

	/**
	 * Converts bytes to HR.
	 *
	 * @access			private
	 * @param			mixed			$bytes
	 * @remark			This is influenced by the work of Mike Jolley.
	 * @see				http://mikejolley.com/projects/wp-page-load-stats/
	 */
	private function _convertBytesToHR( $bytes ) {
		$units = array( 0 => 'B', 1 => 'kB', 2 => 'MB', 3 => 'GB' );
		$log = log( $bytes, 1024 );
		$power = ( int ) $log;
		$size = pow( 1024, $log - $power );
		return $size . $units[ $power ];
	}

}
endif;