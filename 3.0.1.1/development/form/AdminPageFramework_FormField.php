<?php
/**
 * Admin Page Framework
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2014 Michael Uno; Licensed MIT
 * 
 */
if ( ! class_exists( 'AdminPageFramework_FormField' ) ) :
/**
 * Provides methods for rendering form input fields.
 *
 * @since			2.0.0
 * @since			2.0.1			Added the <em>size</em> type.
 * @since			2.1.5			Separated the methods that defines field types to different classes.
 * @extends			AdminPageFramework_FormField_Base
 * @package			AdminPageFramework
 * @subpackage		Form
 * @internal
 */
class AdminPageFramework_FormField extends AdminPageFramework_FormField_Base {
			
	/**
	 * Returns the input tag name for the name attribute.
	 * 
	 * @since			2.0.0
	 * @since			3.0.0			Dropped the page slug dimension. Deprecated the 'name' field key to override the name attribute since the new 'attribute' key supports the functionality.
	 */
	private function _getInputName( $aField=null, $sKey='' ) {
		
		$sKey = ( string ) $sKey;	// this is important as 0 value may have been interpreted as false.
		$aField = isset( $aField ) ? $aField : $this->aField;
		$_sKey = $sKey !== '0' && empty( $sKey ) ? '' : "[{$sKey}]";
		$sSectionIndex = isset( $aField['section_id'], $aField['_section_index'] ) ? "[{$aField['_section_index']}]" : "";
		switch( $aField['_fields_type'] ) {
			default:
			case 'page':
				$sSectionDimension = isset( $aField['section_id'] ) && $aField['section_id'] && $aField['section_id'] != '_default'
					? "[{$aField['section_id']}]"
					: '';
				return "{$aField['option_key']}{$sSectionDimension}{$sSectionIndex}[{$aField['field_id']}]{$_sKey}";
			case 'page_meta_box':
			case 'post_meta_box':
				return isset( $aField['section_id'] ) && $aField['section_id'] && $aField['section_id'] != '_default'
					? "{$aField['section_id']}{$sSectionIndex}[{$aField['field_id']}]{$_sKey}"
					: "{$aField['field_id']}{$_sKey}";
			case 'taxonomy':	// taxonomy fields type do not support sections.
				return "{$aField['field_id']}{$_sKey}";
					
		}			
	}
	
	/**
	 * Retrieves the field name attribute whose dimensional elements are delimited by the pile character.
	 * 
	 * Instead of [] enclosing array elements, it uses the pipe(|) to represent the multi dimensional array key.
	 * This is used to create a reference to the submit field name to determine which button is pressed.
	 * 
	 * @remark			Used by the import and submit field types.
	 * @since			2.0.0
	 * @since			2.1.5			Made the parameter mandatory. Changed the scope to protected from private. Moved from AdminPageFramework_FormField.
	 * @since			3.0.0			Moved from the submit field type class. Dropped the page slug dimension.
	 */ 
	protected function _getFlatInputName( $aField, $sKey='' ) {	
		
		$sKey = ( string ) $sKey;	// this is important as 0 value may have been interpreted as false.
		$_sKey = $sKey !== '0' && empty( $sKey ) ? '' : "|{$sKey}";
		$sSectionIndex = isset( $aField['section_id'], $aField['_section_index'] ) ? "|{$aField['_section_index']}" : "";
		switch( $aField['_fields_type'] ) {
			default:
			case 'page':
				$sSectionDimension = isset( $aField['section_id'] ) && $aField['section_id'] && $aField['section_id'] != '_default'
					? "|{$aField['section_id']}"
					: '';
				return "{$aField['option_key']}{$sSectionDimension}{$sSectionIndex}|{$aField['field_id']}{$_sKey}";
			case 'page_meta_box':
			case 'post_meta_box':
				return isset( $aField['section_id'] ) && $aField['section_id'] && $aField['section_id'] != '_default'
					? "{$aField['section_id']}{$sSectionIndex}|{$aField['field_id']}{$_sKey}"
					: "{$aField['field_id']}{$_sKey}";
			case 'taxonomy':	// taxonomy fields type do not support sections.
				return "{$aField['field_id']}{$_sKey}";
					
		}	
	}
	
	/**
	 * Returns the stored field value.
	 * 
	 * @since			2.0.0
	 * @since			3.0.0			Removed the check of the 'value' and 'default' keys. Made it use the '_fields_type' internal key.
	 */
	private function _getInputFieldValue( $aField, $aOptions ) {	

		// Check if a previously saved option value exists or not. Regular setting pages and page meta boxes will be applied here.
		// It's important to return null if not set as the returned value will be checked later on whether it is set or not. If an empty value is returned, they will think it's set.
		switch( $aField['_fields_type'] ) {
			default:
			case 'page':
			case 'page_meta_box':
			case 'taxonomy':
			
				// If a section is not set, check the first dimension element.
				if ( ! isset( $aField['section_id'] ) || $aField['section_id'] == '_default' )
					return isset( $aOptions[ $aField['field_id'] ] )
						? $aOptions[ $aField['field_id'] ]
						: null;		
					
				// At this point, the section dimension is set.
				
				// If it belongs to a sub section,
				if ( isset( $aField['_section_index'] ) )
					return isset( $aOptions[ $aField['section_id'] ][ $aField['_section_index'] ][ $aField['field_id'] ] )
						? $aOptions[ $aField['section_id'] ][ $aField['_section_index'] ][ $aField['field_id'] ]
						: null;				
				
				// Otherwise, return the second dimension element.
				return isset( $aOptions[ $aField['section_id'] ][ $aField['field_id'] ] )
					? $aOptions[ $aField['section_id'] ][ $aField['field_id'] ]
					: null;
					
			case 'post_meta_box':
	
				if ( ! isset( $_GET['action'], $_GET['post'] ) ) return null;
			
				// If a section is not set,
				if ( ! isset( $aField['section_id'] ) || $aField['section_id'] == '_default' )
					return get_post_meta( $_GET['post'], $aField['field_id'], true );
					
				// At this point, the section dimension is set.
				$aSectionArray = get_post_meta( $_GET['post'], $aField['section_id'], true );
				
				// If it belongs to a sub section,
				if ( isset( $aField['_section_index'] ) )
					return isset( $aSectionArray[ $aField['_section_index'] ][ $aField['field_id'] ] )
						? $aSectionArray[ $aField['_section_index'] ][ $aField['field_id'] ]
						: null;								
						
				// Otherwise, return the second dimension element.
				return isset( $aSectionArray[ $aField['field_id'] ] )
					? $aSectionArray[ $aField['field_id'] ]
					: null;
					
		}
		return null;	
						
	}	
	
	/**
	 * Returns the input ID
	 * 
	 * e.g. "{$aField['field_id']}_{$sKey}";
	 * 
	 * @remark			The keys are prefixed with double-underscores.
	 */
	private function _getInputID( $aField, $sIndex ) {
		
		$sSectionIndex = isset( $aField['_section_index'] ) ? '__' . $aField['_section_index'] : '';	// double underscore
		$sFieldIndex = '__' . $sIndex; // double underscore
		return isset( $aField['section_id'] ) && $aField['section_id'] != '_default'
			? $aField['section_id'] . $sSectionIndex . '_' . $aField['field_id'] . $sFieldIndex
			: $aField['field_id'] . $sFieldIndex;
		
	}
	
	/**
	 * Returns the tag ID.
	 * 
	 * @remark			This is called from the fields table class to insert the row id.
	 */
	static public function _getInputTagID( $aField )  {
		
		$sSectionIndex = isset( $aField['_section_index'] ) ? '__' . $aField['_section_index'] : '';
		return isset( $aField['section_id'] ) && $aField['section_id'] != '_default'
			? $aField['section_id'] . $sSectionIndex . '_' . $aField['field_id']
			: $aField['field_id'];
					
	}		
	
	/** 
	 * Retrieves the input field HTML output.
	 * @since			2.0.0
	 * @since			2.1.6			Moved the repeater script outside the fieldset tag.
	 */ 
	public function _getFieldOutput() {
		
		$aFieldsOutput = array(); 
		$aExtraOutput = array();

		/* 1. Prepend the field error message. */
		if ( isset( $this->aField['section_id'], $this->aErrors[ $this->aField['section_id'] ], $this->aErrors[ $this->aField['section_id'] ][ $this->aField['field_id'] ] ) )	// if this field has a section and the error element is set
			$aFieldsOutput[] = "<span style='color:red;'>*&nbsp;{$this->aField['error_message']}" 
				. $this->aErrors[ $this->aField['section_id'] ][ $this->aField['field_id'] ] 
			. "</span><br />";
		else if ( isset( $this->aErrors[ $this->aField['field_id'] ] ) )	// if this field does not have a section and the error element is set,
			$aFieldsOutput[] = "<span style='color:red;'>*&nbsp;{$this->aField['error_message']}" 
				. $this->aErrors[ $this->aField['field_id'] ] 
			. "</span><br />";
					
		/* 2. Set new elements */
		$this->aField['tag_id'] = $this->_getInputTagID( $this->aField );
			
		/* 3. Compose fields array for sub-fields	*/
		$aFields = $this->_composeFieldsArray( $this->aField, $this->aOptions );

		/* 4. Get the field output. */
		foreach( $aFields as $sKey => $aField ) {

			/* 4-1. Retrieve the field definition for this type - this process enables to have mixed field types in sub-fields */ 
			$aFieldTypeDefinition = isset( $this->aFieldTypeDefinitions[ $aField['type'] ] )
				? $this->aFieldTypeDefinitions[ $aField['type'] ] 
				: $this->aFieldTypeDefinitions['default'];
				
			/* 4-2. Set some new elements */ 
			$aField['_index'] = $sKey;
			$aField['input_id'] = $this->_getInputID( $aField, $sKey );	//  ({section id}_){field_id}_{index}
			$aField['_input_name']	= $this->_getInputName( $this->aField, $aField['_is_multiple_fields'] ? $sKey : '' );	
			$aField['_input_name_flat']	= $this->_getFlatInputName( $this->aField, $aField['_is_multiple_fields'] ? $sKey : '' );	// used for submit, export, import field types			
			$aField['_field_container_id'] = "field-{$aField['input_id']}";	// used in the attribute below plus it is also used in the sample custom field type.
			$aField['_fields_container_id'] = "fields-{$this->aField['tag_id']}";
			$aField['_fieldset_container_id'] = "fieldset-{$this->aField['tag_id']}";
			
			$aField['attributes'] = $this->uniteArrays(
				( array ) $aField['attributes'],	// user set values
				array(	// the automatically generated values
					'id' => $aField['input_id'],
					'name' => $aField['_input_name'],
					'value' => $aField['value'],
					'type' => $aField['type'],	// text, password, etc.
					'disabled'	=> null,
				),
				( array ) $aFieldTypeDefinition['aDefaultKeys']['attributes']
			);

			/* 4-3. Callback the registered function to output the field */
			$_aFieldAttributes = array(
				'id'	=>	$aField['_field_container_id'],
				'class'	=>	"admin-page-framework-field admin-page-framework-field-{$aField['type']}" 
					. ( $aField['attributes']['disabled'] ? ' disabled' : '' ),
				'data-type'	=>	"{$aField['type']}",	// this is referred by the repeatable field JavaScript script.
			) + $aField['attributes']['field'];
			$aFieldsOutput[] = is_callable( $aFieldTypeDefinition['hfRenderField'] ) 
				? $aField['before_field']
					. "<div " . $this->generateAttributes( $_aFieldAttributes ) . ">"
						. call_user_func_array(
							$aFieldTypeDefinition['hfRenderField'],
							array( $aField )
						)
						. ( ( $sDelimiter = $aField['delimiter'] )
							? "<div " . $this->generateAttributes( array(
									'class'	=>	'delimiter',
									'id'	=>	"delimiter-{$aField['input_id']}",
									'style'	=>	$this->isLastElement( $aFields, $sKey ) ? "display:none;" : "",
								) ) . ">{$sDelimiter}</div>"

							: ""
						)
					. "</div>"
					. $aField['after_field']
				: "";

		}
				
		/* 5. Add the description */
		$aExtraOutput[] = ( isset( $this->aField['description'] ) && trim( $this->aField['description'] ) != '' ) 
			? "<p class='admin-page-framework-fields-description'><span class='description'>{$this->aField['description']}</span></p>"
			: '';
			
		/* 6. Add the repeater script */
		$aExtraOutput[] = $this->aField['repeatable']
			? $this->_getRepeaterFieldEnablerScript( 'fields-' . $this->aField['tag_id'], count( $aFields ), $this->aField['repeatable'] )
			: '';

		/* 7. Add the sortable script */
		$aExtraOutput[] = $this->aField['sortable'] && ( count( $aFields ) > 1 || $this->aField['repeatable'] )
			? $this->_getSortableFieldEnablerScript( 'fields-' . $this->aField['tag_id'] )
			: '';		
				
		/* 8. Return the entire output */
		$_aFieldsSetAttributes = array(
			'id'	=> 'fieldset-' . $this->aField['tag_id'],
			'class'	=> 'admin-page-framework-fieldset',
			'data-field_id'	=>	$this->aField['tag_id'],	// <-- don't remember what this was for...
		) + $this->aField['attributes']['fieldset'];
		$_aFieldsContainerAttributes = array(
			'id'	=> 'fields-' . $this->aField['tag_id'],
			'class'	=> 'admin-page-framework-fields'
				. ( $this->aField['repeatable'] ? ' repeatable' : '' )
				. ( $this->aField['sortable'] ? ' sortable' : '' ),
			'data-type'	=> $this->aField['type'],	// this is referred by the sortable field JavaScript script.
		) + $this->aField['attributes']['fields'];
		return 
			"<fieldset " . $this->generateAttributes( $_aFieldsSetAttributes ) . ">"
				. "<div " . $this->generateAttributes( $_aFieldsContainerAttributes ) . ">"
					. $this->aField['before_fields'] 
					. implode( PHP_EOL, $aFieldsOutput )
					. $this->aField['after_fields']
				. "</div>"
				. implode( PHP_EOL, $aExtraOutput )
			. "</fieldset>";
		
	}

		/**
		 * Returns the array of fields 
		 * 
		 * @since			3.0.0
		 */
		protected function _composeFieldsArray( &$aField, &$aOptions ) {

			/* Get the set value(s) */
			$vSavedValue = $this->_getInputFieldValue( $aField, $aOptions );

			/* Separate the first field and sub-fields */
			$aFirstField = array();
			$aSubFields = array();
			foreach( $aField as $nsIndex => $vFieldElement ) {
				if ( is_numeric( $nsIndex ) ) 
					$aSubFields[] = $vFieldElement;
				else 
					$aFirstField[ $nsIndex ] = $vFieldElement;
			}		
			
			/* Create the sub-fields of repeatable fields based on the saved values */
			if ( $aField['repeatable'] ) 
				foreach( ( array ) $vSavedValue as $iIndex => $vValue ) {
					if ( $iIndex == 0 ) continue;
					$aSubFields[ $iIndex - 1 ] = isset( $aSubFields[ $iIndex - 1 ] ) && is_array( $aSubFields[ $iIndex - 1 ] ) 
						? $aSubFields[ $iIndex - 1 ] 
						: array();			
				}
			
			/* Put the initial field and the sub-fields together in one array */
			foreach( $aSubFields as &$aSubField ) {
				
				/* Before merging recursively, evacuate the label element which should not be merged */
				$aLabel = isset( $aSubField['label'] ) 
					? $aSubField['label']
					: ( isset( $aFirstField['label'] )
						 ? $aFirstField['label'] 
						 : null
					);
				
				/* Do recursive array merging */
				$aSubField = $this->uniteArrays( $aSubField, $aFirstField );	// the 'attributes' array of some field types have more than one dimensions. // $aSubField = $aSubField + $aFirstField;
				
				/* Restore the label elemnet */
				$aSubField['label']	= $aLabel;
				
			}
			$aFields = array_merge( array( $aFirstField ), $aSubFields );
					
			/* Set the saved values */		
			if ( count( $aSubFields ) > 0 || $aField['repeatable'] || $aField['sortable'] ) {	// means the elements are saved in an array.
				foreach( $aFields as $iIndex => &$aThisField ) {
					$aThisField['_saved_value'] = isset( $vSavedValue[ $iIndex ] ) ? $vSavedValue[ $iIndex ] : null;
					$aThisField['_is_multiple_fields'] = true;
				}
			} else {
				$aFields[ 0 ]['_saved_value'] = $vSavedValue;
				$aFields[ 0 ]['_is_multiple_fields'] = false;
			} 

			/* Determine the value */
			unset( $aThisField );	// PHP requires this for a previously used variable as reference.
			foreach( $aFields as &$aThisField ) {
				$aThisField['_is_value_set_by_user'] = isset( $aThisField['value'] );
				$aThisField['value'] = isset( $aThisField['value'] ) 
					? $aThisField['value'] 
					: ( isset( $aThisField['_saved_value'] ) 
						? $aThisField['_saved_value']
						: ( isset( $aThisField['default'] )
							? $aThisField['default']
							: null
						)
					);					
			}

			return $aFields;
			
		}
	
}
endif;