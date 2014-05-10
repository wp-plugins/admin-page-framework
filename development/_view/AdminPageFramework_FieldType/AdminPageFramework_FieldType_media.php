<?php
/**
 * Admin Page Framework
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2014 Michael Uno; Licensed MIT
 * 
 */
if ( ! class_exists( 'AdminPageFramework_FieldType_media' ) ) :
/**
 * Defines the media field type.
 * 
 * @package			AdminPageFramework
 * @subpackage		FieldType
 * @since			2.1.5
 * @internal
 */
class AdminPageFramework_FieldType_media extends AdminPageFramework_FieldType_image {
	
	/**
	 * Defines the field type slugs used for this field type.
	 */
	public $aFieldTypeSlugs = array( 'media', );
	
	/**
	 * Defines the default key-values of this field type. 
	 * 
	 * @remark			$_aDefaultKeys holds shared default key-values defined in the base class.
	 */
	protected $aDefaultKeys = array(
		'attributes_to_store'	=>	array(),	// ( array ) This is for the image and media field type. The attributes to save besides URL. e.g. ( for the image field type ) array( 'title', 'alt', 'width', 'height', 'caption', 'id', 'align', 'link' ).
		'show_preview'	=>	true,
		'allow_external_source'	=>	true,	// ( boolean ) Indicates whether the media library box has the From URL tab.
		'attributes'	=>	array(
			'input'		=> array(
				'size'	=>	40,
				'maxlength'	=>	400,			
			),
			'button'	=>	array(
			),
			'preview'	=>	array(
			),			
		),	
	);
	
	/**
	 * Loads the field type necessary components.
	 */ 
	public function _replyToFieldLoader() {
		parent::_replyToFieldLoader();
	}	
	
	/**
	 * Returns the field type specific JavaScript script.
	 */ 
	public function _replyToGetScripts() {
		return $this->_getScript_CustomMediaUploaderObject() . PHP_EOL	// defined in the parent class
			. $this->_getScript_MediaUploader(
				"admin_page_framework", 
				$this->oMsg->__( 'upload_file' ),
				$this->oMsg->__( 'use_this_file' )
			) . PHP_EOL
			. $this->_getScript_RegisterCallbacks();
	}	
	
		/**
		 * Returns the JavaScript script that handles repeatable events. 
		 * 
		 * @since			3.0.0
		 */
		protected function _getScript_RegisterCallbacks() {

			$aJSArray = json_encode( $this->aFieldTypeSlugs );
			/*	The below JavaScript functions are a callback triggered when a new repeatable field is added and removed. Since the APF repeater script does not
				renew the upload button (while it does on the input tag value), the renewal task must be dealt here separately. */
			return"
			jQuery( document ).ready( function(){
						
				jQuery().registerAPFCallback( {	
				
					added_repeatable_field: function( node, sFieldType, sFieldTagID, iCallType ) {
						
						/* 1. Return if it is not the type. */						
						if ( jQuery.inArray( sFieldType, {$aJSArray} ) <= -1 ) return;	/* If it is not the media field type, do nothing. */
						if ( node.find( '.select_media' ).length <= 0 )  return;	/* If the uploader buttons are not found, do nothing */
						
						/* 2. Increment the ids of the next all (including this one) uploader buttons  */
						var nodeFieldContainer = node.closest( '.admin-page-framework-field' );
						var iOccurence = iCallType === 1 ? 1 : 0;
						nodeFieldContainer.nextAll().andSelf().each( function( iIndex ) {

							/* 2-1. Increment the button ID */
							nodeButton = jQuery( this ).find( '.select_media' );
							
							// If it's for repeatable sections, updating the attributes is only necessary for the first iteration.
							if ( ! ( iCallType === 1 && iIndex !== 0 ) ) {
								nodeButton.incrementIDAttribute( 'id', iOccurence );
							}
							
							/* 2-2. Rebind the uploader script to each button. The previously assigned ones also need to be renewed; 
							 * otherwise, the script sets the preview image in the wrong place. */						
							var nodeMediaInput = jQuery( this ).find( '.media-field input' );
							if ( nodeMediaInput.length <= 0 ) return true;
							setAPFMediaUploader( nodeMediaInput.attr( 'id' ), true, jQuery( nodeButton ).attr( 'data-enable_external_source' ) );
							
						});						
					},
					removed_repeatable_field: function( node, sFieldType, sFieldTagID, iCallType ) {
						
						/* 1. Return if it is not the type. */
						if ( jQuery.inArray( sFieldType, {$aJSArray} ) <= -1 ) return;	/* If it is not the color field type, do nothing. */
						if ( node.find( '.select_media' ).length <= 0 )  return;	/* If the uploader buttons are not found, do nothing */
						
						/* 2. Decrement the ids of the next all (including this one) uploader buttons. ( the input values are already dealt by the framework repeater script ) */
						var nodeFieldContainer = node.closest( '.admin-page-framework-field' );
						var iOccurence = iCallType === 1 ? 1 : 0;	// the occurrence value indicates which part of digit to change 
						nodeFieldContainer.nextAll().andSelf().each( function( iIndex ) {
							
							/* 2-1. Decrement the button ID */
							nodeButton = jQuery( this ).find( '.select_media' );		

							// If it's for repeatable sections, updating the attributes is only necessary for the first iteration.
							if ( ! ( iCallType === 1 && iIndex !== 0 ) ) {										
								nodeButton.decrementIDAttribute( 'id', iOccurence );
							}
														
							/* 2-2. Rebind the uploader script to each button. */
							var nodeMediaInput = jQuery( this ).find( '.media-field input' );
							if ( nodeMediaInput.length <= 0 ) return true;
							setAPFMediaUploader( nodeMediaInput.attr( 'id' ), true, jQuery( nodeButton ).attr( 'data-enable_external_source' ) );
						});
					},
					
					sorted_fields : function( node, sFieldType, sFieldsTagID ) {	// on contrary to repeatable callbacks, the _fields_ container node and its ID will be passed.

						/* 1. Return if it is not the type. */
						if ( jQuery.inArray( sFieldType, {$aJSArray} ) <= -1 ) return;	/* If it is not the color field type, do nothing. */						
						if ( node.find( '.select_media' ).length <= 0 )  return;	/* If the uploader buttons are not found, do nothing */
						
						/* 2. Update the Select File button */
						var iCount = 0;
						node.children( '.admin-page-framework-field' ).each( function() {
							
							nodeButton = jQuery( this ).find( '.select_media' );
							
							/* 2-1. Set the current iteration index to the button ID */
							nodeButton.setIndexIDAttribute( 'id', iCount );	
							
							/* 2-2. Rebuind the uploader script to the button */
							var nodeMediaInput = jQuery( this ).find( '.media-field input' );
							if ( nodeMediaInput.length <= 0 ) return true;
							setAPFMediaUploader( nodeMediaInput.attr( 'id' ), true, jQuery( nodeButton ).attr( 'data-enable_external_source' ) );
	
							iCount++;
						});
					},
					
				});
			});" . PHP_EOL;	
			
		}
		
		/**
		 * Returns the media uploader JavaScript script to be loaded in the head tag of the created admin pages.
		 * 
		 * @since			2.1.3
		 * @since			2.1.5			Moved from ... Chaned the name from getMediaUploaderScript().
		 */
		private function _getScript_MediaUploader( $sReferrer, $sThickBoxTitle, $sThickBoxButtonUseThis ) {
			
			if ( ! function_exists( 'wp_enqueue_media' ) )	// means the WordPress version is 3.4.x or below
				return "
					jQuery( document ).ready( function(){
						
						/**
						 * Bind/rebinds the thickbox script the given selector element.
						 * The fMultiple parameter does not do anything. It is there to be consistent with the one for the WordPress version 3.5 or above.
						 */
						setAPFMediaUploader = function( sInputID, fMultiple, fExternalSource ) {
							jQuery( '#select_media_' + sInputID ).unbind( 'click' );	// for repeatable fields
							jQuery( '#select_media_' + sInputID ).click( function() {
								var sPressedID = jQuery( this ).attr( 'id' );
								window.sInputID = sPressedID.substring( 13 );	// remove the select_media_ prefix and set a property to pass it to the editor callback method.
								window.original_send_to_editor = window.send_to_editor;
								window.send_to_editor = hfAPFSendToEditorMedia;
								var fExternalSource = jQuery( this ).attr( 'data-enable_external_source' );
								tb_show( '{$sThickBoxTitle}', 'media-upload.php?post_id=1&amp;enable_external_source=' + fExternalSource + '&amp;referrer={$sReferrer}&amp;button_label={$sThickBoxButtonUseThis}&amp;type=image&amp;TB_iframe=true', false );
								return false;	// do not click the button after the script by returning false.									
							});	
						}			
														
						var hfAPFSendToEditorMedia = function( sRawHTML, param ) {

							var sHTML = '<div>' + sRawHTML + '</div>';	// This is for the 'From URL' tab. Without the wrapper element. the below attr() method don't catch attributes.
							var src = jQuery( 'a', sHTML ).attr( 'href' );
							var classes = jQuery( 'a', sHTML ).attr( 'class' );
							var id = ( classes ) ? classes.replace( /(.*?)wp-image-/, '' ) : '';	// attachment ID	
						
							// If the user wants to save relavant attributes, set them.
							var sInputID = window.sInputID;
							jQuery( '#' + sInputID ).val( src );	// sets the image url in the main text field. The url field is mandatory so it does not have the suffix.
							jQuery( '#' + sInputID + '_id' ).val( id );			
								
							// restore the original send_to_editor
							window.send_to_editor = window.original_send_to_editor;
							
							// close the thickbox
							tb_remove();	

						}
					});
				";
				
			return "
			jQuery( document ).ready( function(){		
				
				// Global Function Literal 
				/**
				 * Binds/rebinds the uploader button script to the specified element with the given ID.
				 */				
				setAPFMediaUploader = function( sInputID, fMultiple, fExternalSource ) {

					var fEscaped = false;
						
					jQuery( '#select_media_' + sInputID ).unbind( 'click' );	// for repeatable fields
					jQuery( '#select_media_' + sInputID ).click( function( e ) {
				
						// Reassign the input id from the pressed element ( do not use the passed parameter value to the caller function ) for repeatable sections.
						var sInputID = jQuery( this ).attr( 'id' ).substring( 13 );	// remove the select_image_ prefix and set a property to pass it to the editor callback method.

						window.wpActiveEditor = null;						
						e.preventDefault();
						
						// If the uploader object has already been created, reopen the dialog
						if ( media_uploader ) {
							media_uploader.open();
							return;
						}		
						
						// Store the original select object in a global variable
						oAPFOriginalMediaUploaderSelectObject = wp.media.view.MediaFrame.Select;
						
						// Assign a custom select object.
						wp.media.view.MediaFrame.Select = fExternalSource ? getAPFCustomMediaUploaderSelectObject() : oAPFOriginalMediaUploaderSelectObject;
						var media_uploader = wp.media({
							title: '{$sThickBoxTitle}',
							button: {
								text: '{$sThickBoxButtonUseThis}'
							},
							multiple: fMultiple  // Set this to true to allow multiple files to be selected
						});
			
						// When the uploader window closes, 
						media_uploader.on( 'escape', function() {
							fEscaped = true;
							return false;
						});	
						media_uploader.on( 'close', function() {

							var state = media_uploader.state();
							
							// Check if it's an external URL
							if ( typeof( state.props ) != 'undefined' && typeof( state.props.attributes ) != 'undefined' ) 
								var image = state.props.attributes;	
							
							// If the image variable is not defined at this point, it's an attachment, not an external URL.
							if ( typeof( image ) !== 'undefined'  ) {
								setPreviewElementWithDelay( sInputID, image );
							} else {
								
								var selection = media_uploader.state().get( 'selection' );
								selection.each( function( attachment, index ) {
									attachment = attachment.toJSON();
									if( index == 0 ){	
										// place first attachment in field
										setPreviewElementWithDelay( sInputID, attachment );
									} else{
										
										var field_container = jQuery( '#' + sInputID ).closest( '.admin-page-framework-field' );
										var new_field = jQuery( this ).addAPFRepeatableField( field_container.attr( 'id' ) );
										var sInputIDOfNewField = new_field.find( 'input' ).attr( 'id' );
										setPreviewElementWithDelay( sInputIDOfNewField, attachment );
			
									}
								});				
								
							}
							
							// Restore the original select object.
							wp.media.view.MediaFrame.Select = oAPFOriginalMediaUploaderSelectObject;	
							
						});
						
						// Open the uploader dialog
						media_uploader.open();											
						return false;       
					});	
				
				
					var setPreviewElementWithDelay = function( sInputID, oImage, iMilliSeconds ) {
						
						iMilliSeconds = iMilliSeconds === undefined ? 100 : iMilliSeconds;
						setTimeout( function (){
							if ( ! fEscaped ) {
								setPreviewElement( sInputID, oImage );
							}
							fEscaped = false;
						}, iMilliSeconds );
						
					}
					
					var setPreviewElement = function( sInputID, image ) {
									
						// If the user want the attributes to be saved, set them in the input tags.
						jQuery( '#' + sInputID ).val( image.url );		// the url field is mandatory so  it does not have the suffix.
						jQuery( '#' + sInputID + '_id' ).val( image.id );				
						jQuery( '#' + sInputID + '_caption' ).val( jQuery( '<div/>' ).text( image.caption ).html() );				
						jQuery( '#' + sInputID + '_description' ).val( jQuery( '<div/>' ).text( image.description ).html() );				
						
					}
				}		
				
			});";
		}
	/**
	 * Returns the field type specific CSS rules.
	 */ 
	public function _replyToGetStyles() {
		return "/* Media Uploader Button */
			.admin-page-framework-field-media input {
				margin-right: 0.5em;
				vertical-align: middle;	
			}
			.select_media.button.button-small {
				margin-top: 0.1em;
			}
		";
	}
	
	/**
	 * Returns the output of the field type.
	 * 
	 * @since			2.1.5
	 */
	public function _replyToGetField( $aField ) {
		return parent::_replyToGetField( $aField );
	}
		
		/**
		 * Returns the output of the preview box.
		 * @since			3.0.0
		 */
		protected function _getPreviewContainer( $aField, $sImageURL, $aPreviewAtrributes ) { return ""; }
		
		/**
		 * A helper function for the above getImageInputTags() method to add a image button script.
		 * 
		 * @since			2.1.3
		 * @since			2.1.5			Moved from AdminPageFramework_FormField.
		 */		
		protected function _getUploaderButtonScript( $sInputID, $bRpeatable, $bExternalSource, array $aButtonAttributes ) {
			
			$sButton = 
				"<a " . $this->generateAttributes( 
					array(
						'id'	=>	"select_media_{$sInputID}",
						'href'	=>	'#',
						'class'	=>	'select_media button button-small ' . ( isset( $aButtonAttributes['class'] ) ? $aButtonAttributes['class'] : '' ),
						'data-uploader_type'	=>	function_exists( 'wp_enqueue_media' ) ? 1 : 0,
						'data-enable_external_source' => $bExternalSource ? 1 : 0,
					) + $aButtonAttributes
				) . ">"
					. $this->oMsg->__( 'select_file' )
				."</a>";
				
			$sScript = "
				if ( jQuery( 'a#select_media_{$sInputID}' ).length == 0 ) {
					jQuery( 'input#{$sInputID}' ).after( \"{$sButton}\" );
				}
				jQuery( document ).ready( function(){			
					setAPFMediaUploader( '{$sInputID}', '{$bRpeatable}', '{$bExternalSource}' );
				});" . PHP_EOL;	
					
			return "<script type='text/javascript' class='admin-page-framework-media-uploader-button'>" . $sScript . "</script>". PHP_EOL;

		}
		
}
endif;