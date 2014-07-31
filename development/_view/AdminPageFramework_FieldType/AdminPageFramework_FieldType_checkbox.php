<?php
/**
 * Admin Page Framework
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2014 Michael Uno; Licensed MIT
 * 
 */
if ( ! class_exists( 'AdminPageFramework_FieldType_checkbox' ) ) :
/**
 * Defines the checkbox field type.
 * 
 * @package			AdminPageFramework
 * @subpackage		FieldType
 * @since			2.1.5
 * @internal
 */
class AdminPageFramework_FieldType_checkbox extends AdminPageFramework_FieldType_Base {
	
	/**
	 * Defines the field type slugs used for this field type.
	 */
	public $aFieldTypeSlugs = array( 'checkbox' );
	
	/**
	 * Defines the default key-values of this field type. 
	 */
	protected $aDefaultKeys = array(
		// 'label'			=> array(),
		// 'attributes'	=> array(
		// ),
	);
	
	/**
	 * Loads the field type necessary components.
	 */ 
	public function _replyToFieldLoader() {
	}	
	
	/**
	 * Returns the field type specific JavaScript script.
	 */ 
	public function _replyToGetScripts() {
		return "";		
	}	

	/**
	 * Returns the field type specific CSS rules.
	 */ 
	public function _replyToGetStyles() {
		return "/* Checkbox field type */
			.admin-page-framework-field input[type='checkbox'] {
				margin-right: 0.5em;
			}			
			.admin-page-framework-field-checkbox .admin-page-framework-input-label-container {
				padding-right: 1em;
			}
			.admin-page-framework-field-checkbox .admin-page-framework-input-label-string  {
				display: inline;	/* Checkbox labels should not fold(wrap) after the check box */
			}
		";
	}
	
	/**
	 * Returns the output of the field type.
	 * 
	 * @since			2.1.5
	 * @since			3.0.0			Removed unnecessary parameters.
	 */
	public function _replyToGetField( $aField ) {
// var_dump( $aField['before_label'] );
		$aOutput = array();
		$asValue = $aField['attributes']['value'];
		// $aOutput[] = is_array( $aField['before_label'] ) ? '' : $aField['before_label'];
		foreach( ( array ) $aField['label'] as $sKey => $sLabel ) {
			
			$aInputAttributes = array(
				'type'	=> 'checkbox',	// needs to be specified since the postytpe field type extends this class. If not set, the 'posttype' will be passed to the type attribute.
				'id' => $aField['input_id'] . '_' . $sKey,
				'checked'	=> $this->getCorrespondingArrayValue( $asValue, $sKey, null ) == 1 ? 'checked' : '',
				'value' => 1,	// must be always 1 for the checkbox type; the actual saved value will be reflected with the above 'checked' attribute.
				'name'	=> is_array( $aField['label'] ) ? "{$aField['attributes']['name']}[{$sKey}]" : $aField['attributes']['name'],
			) 
			+ $this->getFieldElementByKey( $aField['attributes'], $sKey, $aField['attributes'] )
			+ $aField['attributes'];
		
			$aLabelAttributes = array(
				'for'	=>	$aInputAttributes['id'],
				'class'	=>	$aInputAttributes['disabled'] ? 'disabled' : '',
			);
			
			$aOutput[] =
				$this->getFieldElementByKey( $aField['before_label'], $sKey )
				. "<div class='admin-page-framework-input-label-container admin-page-framework-checkbox-label' style='min-width: " . $this->sanitizeLength( $aField['label_min_width'] ) . ";'>"
					. "<label " . $this->generateAttributes( $aLabelAttributes ) . ">"
						. $this->getFieldElementByKey( $aField['before_input'], $sKey )
						. "<span class='admin-page-framework-input-container'>"
							. "<input type='hidden' name='{$aInputAttributes['name']}' value='0' />"	// the unchecked value must be set prior to the checkbox input field.
							. "<input " . $this->generateAttributes( $aInputAttributes ) . " />"	// this method is defined in the base class	
						. "</span>"
						. "<span class='admin-page-framework-input-label-string'>"
							. $sLabel
						. "</span>"
						. $this->getFieldElementByKey( $aField['after_input'], $sKey )
					. "</label>"					
				. "</div>"
				. $this->getFieldElementByKey( $aField['after_label'], $sKey );
				
		}	
		// $aOutput[] = is_array( $aField['after_label'] ) ? '' : $aField['after_label'];
		
		return implode( PHP_EOL, $aOutput );
		
	}	
	
}
endif;