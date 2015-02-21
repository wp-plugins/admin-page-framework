<?php
/**
 * Admin Page Framework
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2014 Michael Uno; Licensed MIT
 * 
 */

/**
 * Provides utility methods which do not use WordPress functions.
 *
 * @since       2.0.0
 * @extends     AdminPageFramework_Utility_SystemInformation
 * @package     AdminPageFramework
 * @subpackage  Utility
 * @internal
 */
abstract class AdminPageFramework_Utility extends AdminPageFramework_Utility_SystemInformation {
        
    /**
     * Returns the width for HTML attributes.
     * 
     * When a value may be a number with a unit like, '100%', it returns the value itself.
     * When a value misses a unit like '60', it returns with the unit such as '60%'.
     * 
     * @since       3.1.1
     * @return      string
     */
    static public function sanitizeLength( $sLength, $sUnit='px' ) {
        return is_numeric( $sLength ) 
            ? $sLength . $sUnit
            : $sLength;
    }
        
    /**
     * Retrieves the query value from the given URL with a key.
     * 
     * @since       2.0.0
     * @return      string|null
     */ 
    static public function getQueryValueInURLByKey( $sURL, $sQueryKey ) {
        
        $aURL = parse_url( $sURL ) + array( 'query' => '' );
        parse_str( $aURL['query'], $aQuery );     
        return self::getElement(
            $aQuery,  // subject array
            $sQueryKey, // key
            null      // default
        );                    
    }
    
    /**
     * Generates inline CSS rules from the given array.
     * 
     * For example,
     * <code>
     * array(
     *      'width' => '32px',
     *      'height' => '32px',
     * )
     * </code>
     * will be
     * <code>
     * 'width: 32px; height: 32px;'
     * </code>
     * 
     * @since       3.2.0
     * @return      string
     */
    static public function generateInlineCSS( array $aCSSRules ) {
        $_aOutput = array();
        foreach( $aCSSRules as $_sProperty => $_sValue ) {
            $_aOutput[] = $_sProperty . ': ' . $_sValue;
        }
        return implode( '; ', $_aOutput );
    }
    
    /**
     * Generates a string of inline styles for the style attribute value from multiple arguments.
     * 
     * Duplicated items will be merged.
     * 
     * For example,
     * <code>generateStyleAttribute( array( 'margin-top' => '10px', 'display: inline-block' ), 'float:right; display: none;' )</code>
     * will generate
     * <code>margin-top: 10px; display: inline-block; float:right;</code>
     * @since       3.3.1
     * @return      string
     */
    static public function generateStyleAttribute( $asInlineCSSes ) {
        
        $_aCSSRules = array();
        foreach( array_reverse( func_get_args() ) as $_asCSSRules ) {
            
            // For array, store in the container.
            if ( is_array( $_asCSSRules ) ) {
                $_aCSSRules = array_merge( $_asCSSRules, $_aCSSRules );
                continue;
            }
            
            // At this point, it is a string. Break them down to array elements.
            $__aCSSRules = explode( ';', $_asCSSRules );
            foreach( $__aCSSRules as $_sPair ) {
                $_aCSSPair = explode( ':', $_sPair );
                if ( ! isset( $_aCSSPair[ 0 ], $_aCSSPair[ 1 ] ) ) {
                    continue;
                }
                $_aCSSRules[ $_aCSSPair[ 0 ] ] = $_aCSSPair[ 1 ];
            }
            
        }
        return self::generateInlineCSS( array_unique( $_aCSSRules ) );
        
    }
    
    /**
     * Generates a string of class selectors from multiple arguments.
     * 
     * For example, 
     * <code>
     * $sClasses = generateClassAttribute( array( 'button, button-primary' ), 'remove_button button' );
     * </code>
     * Will generates
     * <code>
     *  button button-primary remove_button
     * </code>
     * 
     * @remark      Duplicated items will be merged.
     * @since       3.2.0
     * @todo        Fix an issue that when a multidimentinal array is passed, which causes a warning:  Notice: Array to string conversion.
     * @return      string
     */
    static public function generateClassAttribute( /* $asClassSelectors1, $asClassSelectors12 */ ) {
        
        $_aClasses  = array();
        foreach( func_get_args() as $_asClasses ) {
            if ( ! in_array( gettype( $_asClasses ), array( 'array', 'string' ) ) ) {
                continue;
            }            
            $_aClasses = array_merge( 
                $_aClasses,
                is_array( $_asClasses )
                    ? $_asClasses
                    : explode( ' ', $_asClasses )
            );
        }
        $_aClasses  = array_unique( $_aClasses );
        return trim( implode( ' ', $_aClasses ) );
        
    }
    
    /**
     * Returns an array for generating a data attribute from the given associative array.
     * 
     * @since       3.4.0
     * @return      array
     */
    static public function getDataAttributeArray( array $aArray ) {
        
        $_aNewArray = array();
        foreach( $aArray as $sKey => $v ) {
            if ( in_array( gettype( $v ), array( 'array', 'object' ) ) ) {
                continue;
            }            
            $_aNewArray[ "data-{$sKey}" ] = $v ? $v : '0';
        }
        return $_aNewArray;
        
    }
    
    /**
     * Returns one or the other.
     * 
     * Saves one conditional statement.
     * 
     * @remark      Use this only when the performance is not critical.
     * @since       3.5.3
     * @param       boolean|integer|double|string|array|object|resource|NULL        $mValue     The value to evaluate.
     * @param       boolean|integer|double|string|array|object|resource|NULL        $mTrue      The value to return when the first parameter value yields true.
     * @param       boolean|integer|double|string|array|object|resource|NULL        $mTrue      The value to return when the first parameter value yields false.
     * @return      mixed
     */
    static public function getAOrB( $mValue, $mTrue=null, $mFalse=null ) {
        return $mValue ? $mTrue : $mFalse;
    }    
    
    /**
     * Checks the given value is numeric and can be resolved as an integer.
     * 
     * Saves one conditional statement.
     * Used to determine sub-sections and sub-fields elements.
     * 
     * <code>
     * var_dump( is_int( '0' ) );  // false 
     * var_dump( isNumericInteger( '0' ) );    // true
     * var_dump( is_int( '' + 0 ) );  // true
     * var_dump( isNumericInteger( '' ) ); // false
     * </code>
     * 
     * @since       3.5.3
     * @return      boolean
     */
    static public function isNumericInteger( $mValue ) {
        return is_numeric( $mValue ) && is_int( $mValue + 0 );
    }
    
}