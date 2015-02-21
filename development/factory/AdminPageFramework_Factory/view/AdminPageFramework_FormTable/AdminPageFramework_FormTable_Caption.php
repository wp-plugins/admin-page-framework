<?php
/**
 * Admin Page Framework
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2014 Michael Uno; Licensed MIT
 * 
 */

/**
 * Provides methods to render section table captions.
 * 
 * @package     AdminPageFramework
 * @subpackage  Form
 * @since       3.4.0
 * @internal
 */
abstract class AdminPageFramework_FormTable_Caption extends AdminPageFramework_FormTable_Row {
 
    /**
     * Returns the output of the table caption block.
     * 
     * @since       3.4.0
     */
    protected function _getCaption( array $aSection, $hfSectionCallback, $iSectionIndex, $aFields, $hfFieldCallback ) {
        
        if ( ! $aSection['description'] && ! $aSection['title'] ) {
            return "<caption class='admin-page-framework-section-caption' style='display:none;'></caption>";
        }    
        $_abCollapsible = $this->_getCollapsibleArgument( array( $aSection ) );
        $_bShowTitle    = empty( $_abCollapsible ) && ! $aSection['section_tab_slug'];
        return 
            "<caption " . $this->generateAttributes( 
                array(
                    'class'             => 'admin-page-framework-section-caption',
                    // data-section_tab is referred by the repeater script to hide/show the title and the description
                    'data-section_tab'  => $aSection['section_tab_slug'],
                ) 
            ) . ">"
                . $this->_getCollapsibleSectionTitleBlock( $_abCollapsible, 'section', $aFields, $hfFieldCallback )
                . $this->getAOrB(
                    $_bShowTitle,
                    $this->_getCaptionTitle( $aSection, $iSectionIndex, $aFields, $hfFieldCallback ),
                    ''
                )
                . $this->_getCaptionDescription( $aSection, $hfSectionCallback )
                . $this->_getSectionError( $aSection )
            . "</caption>";
        
    }   
        /**
         * Returns the section validation error message.
         * 
         * @since       3.4.0
         * @todo        avoid calling the property but pass it from a parameter.
         * @return      string
         */
        private function _getSectionError( $aSection ) {
      
            $_sSectionError = isset( $this->aFieldErrors[ $aSection['section_id'] ] ) && is_string( $this->aFieldErrors[ $aSection['section_id'] ] )
                ? $this->aFieldErrors[ $aSection['section_id'] ]
                : '';          
            return $_sSectionError  
                ? "<div class='admin-page-framework-error'><span class='section-error'>* "
                        . $_sSectionError 
                    .  "</span></div>"
                : '';  
                
        }
        /**
         * Returns the section title block for the section table caption block.
         * 
         * @since       3.4.0
         */
        private function _getCaptionTitle( $aSection, $iSectionIndex, $aFields, $hfFieldCallback ) {
            return "<div " . $this->generateAttributes(
                    array(
                        'class' => 'admin-page-framework-section-title',
                        'style' => $this->getAOrB(
                            $this->_shouldShowCaptionTitle( $aSection, $iSectionIndex ),
                            '',
                            'display: none;'
                        ),
                    )
                ). ">" 
                    .  $this->_getSectionTitle( $aSection['title'], 'h3', $aFields, $hfFieldCallback )    
                . "</div>";                
        }
        /**
         * Returns the section description for the section table caption block.
         * @since       3.4.0
         */
        private function _getCaptionDescription( $aSection, $hfSectionCallback ) {
            
            if ( $aSection['collapsible'] ) {
                return '';
            }
            if ( ! is_callable( $hfSectionCallback ) ) {
                return '';
            }
            
            // The class selector 'admin-page-framework-section-description' is referred by the repeatable section buttons
            // @todo        Use a different selector name other than 'admin-page-framework-section-description' as it is used in the inner <p> tag element as well.
            return "<div class='admin-page-framework-section-description'>"     
                . call_user_func_array(
                    $hfSectionCallback, 
                    array( 
                        $this->_getDescriptions( 
                            $aSection['description'], 
                            'admin-page-framework-section-description' 
                        ),
                        $aSection 
                    ) 
                )
            . "</div>";

        }
        /**
         * Returns whether the title in the caption block should be displayed or not.
         * 
         * @since   3.4.0
         * @return  boolean
         */
        private function _shouldShowCaptionTitle( $aSection, $iSectionIndex ) {
            
            if ( ! $aSection['title'] ){
                return false;
            }
            if ( $aSection['collapsible'] ) {
                return false;
            }
            if ( $aSection['section_tab_slug'] ) {
                return false;
            }
            if ( $aSection['repeatable'] && $iSectionIndex != 0 ) {
                return false;
            }
            return true;                
            
        }
            
}