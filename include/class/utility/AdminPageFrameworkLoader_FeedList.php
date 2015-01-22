<?php
/**
 * @package         Admin Page Framework Loader
 * @copyright       Copyright (c) 2015, Michael Uno
 * @license         http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since           3.5.0
*/

/** 
 * Retrieves an array of RSS feed items.
 */
class AdminPageFrameworkLoader_FeedList {

    /**
     * A container array that stores fetched feed items.
     */
    protected $_aFeedItems = array();   
    
    /**
     * Stores the feed object. 
     */
    protected $_oFeed;    
    

    /**
     * Stores the target URLs.
     */
    protected $_aURLs = array();
    
    /**
     * Sets up properties.
     */
    public function __construct( $asURLs=array() ) {
        
        $this->_aURLs = is_array( $asURLs ) 
            ? $asURLs 
            : ( empty( $asURLs )
                ? array()
                : ( array ) $asURLs
            );

    }    
    
    /**
     * 
     */
    public function get( $iItems=0 ) {
        
        $_aOutput   = array();
        $asURLs     = empty( $asURLs ) ? $this->_aURLs : $asURLs;
        $_aURLs     = is_array( $asURLs ) ? $asURLs : ( array ) $asURLs ;
        
        if ( empty( $_aURLs ) ) {
            return $_aOutput;
        }
                                 
        $_oFeed = fetch_feed( $_aURLs );
        foreach ( $_oFeed->get_items() as $_oItem ) {
            $_aOutput[ $_oItem->get_title() ] = array( 
                'content'        => $_oItem->get_content(),
                'description'    => $_oItem->get_description(),
                'title'          => $_oItem->get_title(),
                'date'           => $_oItem->get_date( 'j F Y, g:i a' ),
                'author'         => $_oItem->get_author(),
                'link'           => $_oItem->get_permalink(),    // get_link() may be used as well        
            );
        }

        if ( $iItems  ) {
            array_splice( $_aOutput, $iItems );
        }
            
        return $_aOutput;
        
    }
    
}