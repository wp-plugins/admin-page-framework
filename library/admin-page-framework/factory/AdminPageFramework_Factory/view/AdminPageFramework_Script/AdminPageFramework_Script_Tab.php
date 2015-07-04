<?php
/**
 Admin Page Framework v3.5.10 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
class AdminPageFramework_Script_Tab extends AdminPageFramework_Script_Base {
    static public function getScript() {
        return <<<JAVASCRIPTS
( function( $ ) {
    
    $.fn.createTabs = function( asOptions ) {
        
        var _bIsRefresh = ( typeof asOptions === 'string' && asOptions === 'refresh' );
        if ( typeof asOptions === 'object' )
            var aOptions = $.extend( {
            }, asOptions );
        
        this.children( 'ul' ).each( function () {
            
            var bSetActive = false;
            $( this ).children( 'li' ).each( function( i ) {     
                
                var sTabContentID = $( this ).children( 'a' ).attr( 'href' );
                if ( ! _bIsRefresh && ! bSetActive && $( this ).is( ':visible' ) ) {
                    $( this ).addClass( 'active' );
                    bSetActive = true;
                }
                
                if ( $( this ).hasClass( 'active' ) ) {
                    $( sTabContentID ).show();
                } else {                            
                    $( sTabContentID ).css( 'display', 'none' );
                }
                
                $( this ).addClass( 'nav-tab' );
                $( this ).children( 'a' ).addClass( 'anchor' );
                
                $( this ).unbind( 'click' ); // for refreshing 
                $( this ).click( function( e ){
                         
                    e.preventDefault(); // Prevents jumping to the anchor which moves the scroll bar.
                    
                    // Remove the active tab and set the clicked tab to be active.
                    $( this ).siblings( 'li.active' ).removeClass( 'active' );
                    $( this ).addClass( 'active' );
                    
                    // Find the element id and select the content element with it.
                    var sTabContentID = $( this ).find( 'a' ).attr( 'href' );
                    var _oActiveContent = $( this ).parent().parent().find( sTabContentID ).css( 'display', 'block' ); 
                    _oActiveContent.siblings( ':not( ul )' ).css( 'display', 'none' );
                    
                });
            });
        });
                        
    };
}( jQuery ));
JAVASCRIPTS;
        
    }
}