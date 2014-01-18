<?php
/* 
	Plugin Name: Admin Page Framework - Demo
	Plugin URI: http://en.michaeluno.jp/admin-page-framework
	Description: Demonstrates the features of the Admin Page Framework class.
	Author: Michael Uno
	Author URI: http://michaeluno.jp
	Version: 2.1.7.2
	Requirements: PHP 5.2.4 or above, WordPress 3.3 or above.
*/ 

if ( ! class_exists( 'AdminPageFramework' ) )
    include_once( dirname( __FILE__ ) . '/class/admin-page-framework.php' );
	
class APF_BasicUsage extends AdminPageFramework {
	
	public function setUp() {
		
		$this->setRootMenuPage( 
			'Admin Page Framework',
			version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ? 'dashicons-format-audio' : null	// dash-icons are supported since WordPress v3.8
		);
		$this->addSubMenuItems(
			array(
				'strPageTitle' => __( 'First Page', 'admin-page-framework-demo' ),
				'strPageSlug' => 'apf_first_page',
			),
			array(
				'strPageTitle' => __( 'Second Page', 'admin-page-framework-demo' ),
				'strPageSlug' => 'apf_second_page',
			)
		);
		
		$this->setPageHeadingTabsVisibility( true );		// disables the page heading tabs by passing false.
	}	
	
	public function do_apf_first_page() {	// do_ + {page slug}
		?>
			<h3><?php _e( 'do_ + {...} Action Hooks', 'admin-page-framework-demo' ); ?></h3>
			<p><?php _e( 'Hi there! This text is inserted by the <code>do_{page slug}</code> action hook and the callback method.', 'admin-page-framework-demo' ); ?></p>
		<?php

	}
	
	public function content_apf_second_page( $sContent ) {	// content_ + {page slug}
		
		return $sContent 
			. "<h3>" . __( 'content_ + {...} Filter Hooks', 'admin-page-framework-demo' ) . "</h3>"
			. "<p>" 
				. __( 'This message is inserted by the <code>content_{page slug}</code> filter.', 'admin-page-framework-demo' ) 
			. "</p>";
		
	}
	
}
new APF_BasicUsage;
	
class APF_Demo extends AdminPageFramework {

	public function start_APF_Demo() {	// start_{extended class name}
		
		/*
		 * ( Optional ) Register custom field types.
		 */			
		// 1. Include the file that defines the custom field type. 
		$arrFiles = array(
			dirname( __FILE__ ) . '/third-party/date-time-custom-field-types/DateCustomFieldType.php',
			dirname( __FILE__ ) . '/third-party/date-time-custom-field-types/TimeCustomFieldType.php',
			dirname( __FILE__ ) . '/third-party/date-time-custom-field-types/DateTimeCustomFieldType.php',
			dirname( __FILE__ ) . '/third-party/dial-custom-field-type/DialCustomFieldType.php',
			dirname( __FILE__ ) . '/third-party/font-custom-field-type/FontCustomFieldType.php',
		);
		foreach( $arrFiles as $strFilePath )
			if ( file_exists( $strFilePath ) )
				include_once( $strFilePath );
					
		// 2. Instantiate the classes - the $oMsg object is optional if you use the framework's messages.
		$oMsg = AdminPageFramework_Messages::instantiate( 'admin-page-framework-demo' );
		new DateCustomFieldType( 'APF_Demo', 'date', $oMsg );
		new TimeCustomFieldType( 'APF_Demo', 'time', $oMsg );
		new DateTimeCustomFieldType( 'APF_Demo', 'date_time', $oMsg );
		new DialCustomFieldType( 'APF_Demo', 'dial', $oMsg );
		new FontCustomFieldType( 'APF_Demo', 'font', $oMsg );			
		
	}

    public function setUp() {

		$this->setRootMenuPageBySlug( 'edit.php?post_type=apf_posts' );
		$this->addSubMenuItems(
			/* 	
			  for sub-menu pages, e.g.
			  	'strPageTitle' => 'Your Page Title',
				'strPageSlug'] => 'your_page_slug',		// avoid hyphen(dash), dots, and white spaces
				'strScreenIcon' => 'edit',
				'strCapability' => 'manage-options',
				'numOrder' => 10,
				
			  for sub-menu links, e.g.
				'strMenuTitle' => 'Google',
				'strURL' => 'http://www.google.com',
				
			*/
			array(
				'strPageTitle' => __( 'Built-in Field Types', 'admin-page-framework-demo' ),
				'strPageSlug' => 'apf_builtin_field_types',
				'strScreenIcon' => 'options-general',	// one of the screen type from the below can be used.
				/*	Screen Types:
					'edit', 'post', 'index', 'media', 'upload', 'link-manager', 'link', 'link-category', 
					'edit-pages', 'page', 'edit-comments', 'themes', 'plugins', 'users', 'profile', 
					'user-edit', 'tools', 'admin', 'options-general', 'ms-admin', 'generic',		 
				*/							
				'numOrder' => 1,	// optional
			),
			array(
				'strPageTitle' => __( 'Custom Field Types', 'admin-page-framework-demo' ),
				'strPageSlug' => 'apf_custom_field_types',
				'strScreenIcon' => 'options-general',
				'numOrder' => 2,	// optional
			),			
			array(
				'strPageTitle' => __( 'Manage Options', 'admin-page-framework-demo' ),
				'strPageSlug' => 'apf_manage_options',
				'strScreenIcon' => 'link-manager',	
				'numOrder' => 3,	// optional
			),
			array(
				'strPageTitle' => __( 'Sample Page', 'admin-page-framework-demo' ),
				'strPageSlug' => 'apf_sample_page',
				'strScreenIcon' => dirname( __FILE__ ) . '/asset/image/wp_logo_bw_32x32.png',	// the icon file path can be used
			),					
			array(
				'strPageTitle' => __( 'Hidden Page', 'admin-page-framework-demo' ),
				'strPageSlug' => 'apf_hidden_page',
				'strScreenIcon' => plugins_url( 'asset/image/wp_logo_bw_32x32.png', __FILE__ ),	// the icon url can be used
				'fShowInMenu' => false,
			),						
			array(
				'strPageTitle' => __( 'Read Me', 'admin-page-framework-demo' ),
				'strPageSlug' => 'apf_read_me',
				'strScreenIcon' => 'page',
			),			
			array(
				'strMenuTitle' => __( 'Documentation', 'admin-page-framework-demo' ),
				'strURL' => 'http://admin-page-framework.michaeluno.jp/en/v2/',
				'fPageHeadingTab' => false,
			)
		);
				
		$this->addInPageTabs(
			/*
			 * Built-in Field Types
			 * */
			array(
				'strPageSlug'	=> 'apf_builtin_field_types',
				'strTabSlug'	=> 'textfields',
				'strTitle'		=> 'Text Fields',
				'numOrder'		=> 1,				
			),		
			array(
				'strPageSlug'	=> 'apf_builtin_field_types',
				'strTabSlug'	=> 'selectors',
				'strTitle'		=> 'Selectors',
			),					
			array(
				'strPageSlug'	=> 'apf_builtin_field_types',
				'strTabSlug'	=> 'files',
				'strTitle'		=> __( 'Files', 'admin-page-framework-demo' ),
			),
			array(
				'strPageSlug'	=> 'apf_builtin_field_types',
				'strTabSlug'	=> 'checklist',
				'strTitle'		=> 'Checklist',
			),					
			array(
				'strPageSlug'	=> 'apf_builtin_field_types',
				'strTabSlug'	=> 'misc',
				'strTitle'		=> 'MISC',	
			),		
			array(
				'strPageSlug'	=> 'apf_builtin_field_types',
				'strTabSlug'	=> 'verification',
				'strTitle'		=> 'Verification',	
			)
		);
		$this->addInPageTabs(
			array(
				'strPageSlug'	=> 'apf_custom_field_types',
				'strTabSlug'	=> 'geometry',
				'strTitle'		=> __( 'Geometry', 'admin-page-framework-demo' ),	
			),
			array(
				'strPageSlug'	=> 'apf_custom_field_types',
				'strTabSlug'	=> 'date',
				'strTitle'		=> __( 'Date & Time', 'admin-page-framework-demo' ),	
			),
			array(
				'strPageSlug'	=> 'apf_custom_field_types',
				'strTabSlug'	=> 'dial',
				'strTitle'		=> __( 'Dials', 'admin-page-framework-demo' ),	
			),
			array(
				'strPageSlug'	=> 'apf_custom_field_types',
				'strTabSlug'	=> 'font',
				'strTitle'		=> __( 'Fonts', 'admin-page-framework-demo' ),	
			),			
			array()
		);
		$this->addInPageTabs(
			/*
			 * Manage Options
			 * */
			array(
				'strPageSlug'	=> 'apf_manage_options',
				'strTabSlug'	=> 'saved_data',
				'strTitle'		=> 'Saved Data',
			),
			array(
				'strPageSlug'	=> 'apf_manage_options',
				'strTabSlug'	=> 'properties',
				'strTitle'		=> __( 'Properties', 'admin-page-framework-demo' ),
			),
			array(
				'strPageSlug'	=> 'apf_manage_options',
				'strTabSlug'	=> 'messages',
				'strTitle'		=> __( 'Messages', 'admin-page-framework-demo' ),
			),			
			array(
				'strPageSlug'	=> 'apf_manage_options',
				'strTabSlug'	=> 'export_import',
				'strTitle'		=> __( 'Export / Import', 'admin-page-framework-demo' ),			
			),
			array(
				'strPageSlug'	=> 'apf_manage_options',
				'strTabSlug'	=> 'delete_options',
				'strTitle'		=> __( 'Reset', 'admin-page-framework-demo' ),
				'numOrder'		=> 99,	
			),						
			array(
				'strPageSlug'	=> 'apf_manage_options',
				'strTabSlug'	=> 'delete_options_confirm',
				'strTitle'		=> __( 'Reset Confirmation', 'admin-page-framework-demo' ),
				'fHide'			=> true,
				'strParentTabSlug' => 'delete_options',
				'numOrder'		=> 97,
			)
		);
		$this->addInPageTabs(
			/*
			 * Read Me
			 * */
			array(
				'strPageSlug'	=> 'apf_read_me',
				'strTabSlug'	=> 'description',
				'strTitle'		=> __( 'Description', 'admin-page-framework-demo' ),
			),				
			array(
				'strPageSlug'	=> 'apf_read_me',
				'strTabSlug'	=> 'installation',
				'strTitle'		=> __( 'Installation', 'admin-page-framework-demo' ),
			),	
			array(
				'strPageSlug'	=> 'apf_read_me',
				'strTabSlug'	=> 'frequently_asked_questions',
				'strTitle'		=> __( 'FAQ', 'admin-page-framework-demo' ),
			),		
			array(
				'strPageSlug'	=> 'apf_read_me',
				'strTabSlug'	=> 'other_notes',
				'strTitle'		=> __( 'Other Notes', 'admin-page-framework-demo' ),
			),					
			array(
				'strPageSlug'	=> 'apf_read_me',
				'strTabSlug'	=> 'changelog',
				'strTitle'		=> __( 'Change Log', 'admin-page-framework-demo' ),
			),						
			array()
		);			
		
		// Page style.
		$this->showPageHeadingTabs( false );		// disables the page heading tabs by passing false.
		$this->showPageTitle( false, 'apf_read_me' );	// disable the page title of a specific page.
		$this->setInPageTabTag( 'h2' );		
		// $this->showInPageTabs( false, 'apf_read_me' );	// in-page tabs can be disabled like so.
		
		// Enqueue styles - $this->enqueueStyle(  'stylesheet url / path to the WordPress directory here' , 'page slug (optional)', 'tab slug (optional)', 'custom argument array(optional)' );
		$strStyleHandle = $this->enqueueStyle(  dirname( __FILE__ ) . '/asset/css/code.css', 'apf_manage_options' );
		$strStyleHandle = $this->enqueueStyle(  plugins_url( 'asset/css/readme.css' , __FILE__ ) , 'apf_read_me' );
		
		// Enqueue scripts - $this->enqueueScript(  'script url / relative path to the WordPress directory here' , 'page slug (optional)', 'tab slug (optional)', 'custom argument array(optional)' );
		$this->enqueueScript(  
			plugins_url( 'asset/js/test.js' , __FILE__ ),	// source url or path
			'apf_read_me', 	// page slug
			'', 	// tab slug
			array(
				'strHandleID' => 'my_script',	// this handle ID also is used as the object name for the translation array below.
				'arrTranslation' => array( 
					'a' => 'hello world!',
					'style_handle_id' => $strStyleHandle,	// check the enqueued style handle ID here.
				),
			)
		);
			
		// Contextual help tabs.
		$this->addHelpTab( 
			array(
				'strPageSlug'				=> 'apf_builtin_field_types',	// ( mandatory )
				// 'strPageTabSlug'			=> null,	// ( optional )
				'strHelpTabTitle'			=> 'Admin Page Framework',
				'strHelpTabID'				=> 'admin_page_framework',	// ( mandatory )
				'strHelpTabContent'			=> __( 'This contextual help text can be set with the <em>addHelpTab()</em> method.', 'admin-page-framework' ),
				'strHelpTabSidebarContent'	=> __( 'This is placed in the sidebar of the help pane.', 'admin-page-framework' ),
			)
		);
		
		// Add setting sections
		$this->addSettingSections(
			array(
				'strSectionID'		=> 'text_fields',
				'strPageSlug'		=> 'apf_builtin_field_types',
				'strTabSlug'		=> 'textfields',
				'strTitle'			=> 'Text Fields',
				'strDescription'	=> 'These are text type fields.',
				'numOrder'			=> 10,
			),	
			array(
				'strSectionID'		=> 'selectors',
				'strPageSlug'		=> 'apf_builtin_field_types',
				'strTabSlug'		=> 'selectors',
				'strTitle'			=> 'Selectors and Checkboxes',
				'strDescription'	=> 'These are selector type options such as dropdown lists, radio buttons, and checkboxes',
			),
			array(
				'strSectionID'		=> 'sizes',
				'strPageSlug'		=> 'apf_builtin_field_types',
				'strTabSlug'		=> 'selectors',
				'strTitle'			=> 'Sizes',
			),			
			array(
				'strSectionID'		=> 'image_select',
				'strPageSlug'		=> 'apf_builtin_field_types',
				'strTabSlug'		=> 'files',
				'strTitle'			=> 'Image Selector',
				'strDescription'	=> 'Set an image url with jQuwey based image selector.',
			),
			array(
				'strSectionID'		=> 'color_picker',
				'strPageSlug'		=> 'apf_builtin_field_types',
				'strTabSlug'		=> 'misc',
				'strTitle'			=> __( 'Colors', 'admin-page-framework-demo' ),
			),
			array(
				'strSectionID'		=> 'media_upload',
				'strPageSlug'		=> 'apf_builtin_field_types',
				'strTabSlug'		=> 'files',
				'strTitle'			=> __( 'Media Uploader', 'admin-page-framework-demo' ),
				'strDescription'	=> __( 'Upload binary files in addition to images.', 'admin-page-framework-demo' ),
			),
			array(
				'strSectionID'		=> 'checklists',
				'strPageSlug'		=> 'apf_builtin_field_types',
				'strTabSlug'		=> 'checklist',
				'strTitle'			=> 'Checklists',
				'strDescription'	=> 'Post type and taxonomy checklists ( custom checkbox ).',
			),	
			array(
				'strSectionID'		=> 'hidden_field',
				'strPageSlug'		=> 'apf_builtin_field_types',
				'strTabSlug'		=> 'misc',
				'strTitle'			=> 'Hidden Fields',
				'strDescription'	=> 'These are hidden fields.',
			),								
			array(
				'strSectionID'		=> 'file_uploads',
				'strPageSlug'		=> 'apf_builtin_field_types',
				'strTabSlug'		=> 'files',
				'strTitle'			=> __( 'File Uploads', 'admin-page-framework-demo' ),
				'strDescription'	=> __( 'These are upload fields. Check the <code>$_FILES</code> variable in the validation callback method that indicates the temporary location of the uploaded files.', 'admin-page-framework-demo' ),
			),			
			array(
				'strSectionID'		=> 'submit_buttons',
				'strPageSlug'		=> 'apf_builtin_field_types',
				'strTabSlug'		=> 'misc',
				'strTitle'			=> __( 'Submit Buttons', 'admin-page-framework-demo' ),
				'strDescription'	=> __( 'These are custom submit buttons.', 'admin-page-framework-demo' ),
			),			
			array(
				'strSectionID'		=> 'verification',
				'strPageSlug'		=> 'apf_builtin_field_types',
				'strTabSlug'		=> 'verification',
				'strTitle'			=> __( 'Verify Submitted Data', 'admin-page-framework-demo' ),
				'strDescription'	=> __( 'Show error messages when the user submits improper option value.', 'admin-page-framework-demo' ),
			),					
			array()
		);
		
		$this->addSettingSections(	
			array(
				'strSectionID'		=> 'geometry',
				'strPageSlug'		=> 'apf_custom_field_types',
				'strTabSlug'		=> 'geometry',
				'strTitle'			=> __( 'Geometry Custom Field Type', 'admin-page-framework-demo' ),
				'strDescription'	=> __( 'This is a custom field type defined externally.', 'admin-page-framework-demo' ),
			),				
			array(
				'strSectionID'		=> 'date_pickers',
				'strPageSlug'		=> 'apf_custom_field_types',
				'strTabSlug'		=> 'date',
				'strTitle'			=> __( 'Date Custom Field Type', 'admin-page-framework' ),
				'strDescription'	=> __( 'These are date and time pickers.', 'admin-page-framework-demo' ),
			),
			array(
				'strSectionID'		=> 'dial',
				'strPageSlug'		=> 'apf_custom_field_types',
				'strTabSlug'		=> 'dial',
				'strTitle'			=> __( 'Dial Custom Field Type', 'admin-page-framework-demo' ),
			),
			array(
				'strSectionID'		=> 'font',
				'strPageSlug'		=> 'apf_custom_field_types',
				'strTabSlug'		=> 'font',
				'strTitle'			=> __( 'Font Custom Field Type', 'admin-page-framework-demo' ),
				'strDescription' => __( 'This is still experimental.', 'admin-page-framework-demo' ),				
			),
			array()
		);
		
		$this->addSettingSections(	
			array(
				'strSectionID'		=> 'submit_buttons_manage',
				'strPageSlug'		=> 'apf_manage_options',
				'strTabSlug'		=> 'delete_options',
				'strTitle'			=> 'Reset Button',
				'numOrder'			=> 10,
			),			
			array(
				'strSectionID'		=> 'submit_buttons_confirm',
				'strPageSlug'		=> 'apf_manage_options',
				'strTabSlug'		=> 'delete_options_confirm',
				'strTitle'			=> 'Confirmation',
				'strDescription'	=> "<div class='settings-error error'><p><strong>Are you sure you want to delete all the options?</strong></p></div>",
				'numOrder'			=> 10,
			),				
			array(
				'strSectionID'		=> 'exports',
				'strPageSlug'		=> 'apf_manage_options',
				'strTabSlug'		=> 'export_import',
				'strTitle'			=> 'Export Data',
				'strDescription'	=> 'After exporting the options, change and save new options and then import the file to see if the options get restored.',
			),				
			array(
				'strSectionID'		=> 'imports',
				'strPageSlug'		=> 'apf_manage_options',
				'strTabSlug'		=> 'export_import',
				'strTitle'			=> 'Import Data',
			),			
			array()			
		);
		
		// Add setting fields
		$this->addSettingFields(
			array(	// Single text field
				'strFieldID' => 'text',
				'strSectionID' => 'text_fields',
				'strTitle' => __( 'Text', 'admin-page-framework-demo' ),
				'strDescription' => __( 'Type something here.', 'admin-page-framework-demo' ),	// additional notes besides the form field
				'strHelp' => __( 'This is a text field and typed text will be saved.', 'admin-page-framework-demo' ),
				'strType' => 'text',
				'numOrder' => 1,
				'vDefault' => 123456,
				'vSize' => 40,
			),	
			array(	// Password Field
				'strFieldID' => 'password',
				'strSectionID' => 'text_fields',
				'strTitle' => __( 'Password', 'admin-page-framework-demo' ),
				'strTip' => __( 'This input will be masked.', 'admin-page-framework-demo' ),
				'strType' => 'password',
				'strHelp' => __( 'This is a password type field; the user\'s entered input will be masked.', 'admin-page-framework-demo' ),	//'
				'vSize' => 20,
			),			
			array(	// number Field
				'strFieldID' => 'number',
				'strSectionID' => 'text_fields',
				'strTitle' => __( 'Number', 'admin-page-framework-demo' ),
				'strType' => 'number',
			),					
			array(	// Multiple text fields
				'strFieldID' => 'text_multiple',
				'strSectionID' => 'text_fields',
				'strTitle' => __( 'Multiple Text Fields', 'admin-page-framework-demo' ),
				'strDescription' => 'These are multiple text fields.',	// additional notes besides the form field
				'strHelp' => __( 'Multiple text fields can be passed by setting an array to the vLabel key.', 'admin-page-framework-demo' ),
				'strType' => 'text',
				'vDefault' => array(
					'Hello World',
					'Foo bar',
					'Yes, we can.'
				),
				'vLabel' => array( 
					'First Item: ', 
					'Second Item: ', 
					'Third Item: ' 
				),
				'vSize' => array(
					20,
					40,
					60,
				),
				'vDelimiter' => '<br />',
			),		
			array(	// Repeatable text fields
				'strFieldID' => 'text_repeatable',
				'strSectionID' => 'text_fields',
				'strTitle' => __( 'Repeatable Text Fields', 'admin-page-framework-demo' ),
				'strDescription' => __( 'Press + / - to add / remove the fields.', 'admin-page-framework-demo' ),
				'strType' => 'text',
				'vDelimiter' => '',
				'vSize' => 60,
				'fRepeatable' => true,
				'vDefault' => array( 'a', 'b', 'c', ),
			),				
			array(	// Text Area
				'strFieldID' => 'textarea',
				'strSectionID' => 'text_fields',
				'strTitle' => __( 'Single Text Area', 'admin-page-framework-demo' ),
				'strDescription' => __( 'Type a text string here.', 'admin-page-framework-demo' ),
				'strType' => 'textarea',
				'vDefault' => 'Hello World! This is set as the default string.',
				'vRows' => 6,
				'vCols' => 60,
			),
			array(	// Repeatable Text Areas
				'strFieldID' => 'textarea_repeatable',
				'strSectionID' => 'text_fields',
				'strTitle' => __( 'Repeatable Text Areas', 'admin-page-framework-demo' ),
				'strType' => 'textarea',
				'fRepeatable' => true,
				'vDelimiter' => '',
				'vRows' => 3,
				'vCols' => 60,
			),			
			array(	// Rich Text Editors
				'strFieldID' => 'rich_textarea',
				'strSectionID' => 'text_fields',
				'strTitle' => 'Rich Text Area',
				'strType' => 'textarea',
				'vLabel' => array(
					'default' => '',
					'custom' => '',
				),
				'vRich' => array( 
					'default' => true,	// just pass non empty value for the default rich editor.
					'custom' => array( 'media_buttons' => false, 'tinymce' => false ),	// pass the setting array to customize the editor. For the setting argument, see http://codex.wordpress.org/Function_Reference/wp_editor.
				),
			),			
			array(	// Multiple text areas
				'strFieldID' => 'textarea_multiple',
				'strSectionID' => 'text_fields',
				'strTitle' => 'Multiple Text Areas',
				'strDescription' => 'These are multiple text areas.',
				'strType' => 'textarea',
				'vLabel' => array(
					'First Text Area: ',
					'Second Text Area: ',
					'Third Text Area: ',
				),
				'vDefault' => array( 
					'The first default text.',
					'The second default text.',
					'The third default text.',
				),
				'vRows' => array(
					5,
					3,
					2,
				),
				'vCols' => array(
					60,
					40,
					20,
				),
				'vDelimiter' => '<br />',
			)
		);
		$this->addSettingFields(
			array(	// Single Drop-down List
				'strFieldID' => 'select',
				'strSectionID' => 'selectors',
				'strTitle' => 'Dropdown List',
				'strDescription' => 'This is a drop down list.',
				'strHelp' => __( 'This is the <em>select</em> field type.', 'admin-page-framework' ),
				'strType' => 'select',
				'vDefault' => 2,
				'vLabel' => array( 'red', 'blue', 'yellow', 'orange' )
			),	
			array(	// Single Drop-down List with Multiple Options
				'strFieldID' => 'select_multiple_options',
				'strSectionID' => 'selectors',
				'strTitle' => __( 'Dropdown List with Multiple Options', 'admin-page-framework-demo' ),
				'strDescription' => __( 'Press the Shift key to select multiple items.', 'admin-page-framework-demo' ),
				'strHelp' => __( 'This is the <em>select</em> field type with multiple elements.', 'admin-page-framework' ),
				'strType' => 'select',
				'vMultiple' => true,
				'vDefault' => 2,
				'vSize' => 10,	
				'vWidth' => '200px',	// The width property value of CSS.
				'vLabel' => array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'November', 'October', 'December' )
			),			
			array(	// Drop-down Lists with Mixed Types
				'strFieldID' => 'select_mixed',
				'strSectionID' => 'selectors',
				'strTitle' => __( 'Multiple Dropdown Lists with Mixed Types', 'admin-page-framework-demo' ),
				'strDescription' => __( 'This is multiple sets of drop down list.', 'admin-page-framework-demo' ),
				'strType' => 'select',
				'vLabel' => array( 
					array( 'dark', 'light' ),
					array( 'river', 'mountain', 'sky', ),
					array( 'Monday', 'Tuesday', 'Wednessday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
				),
				'vSize' => array(
					1,
					1,
					5,
				),
				'vDefault' => array(
					1,
					2,
					0
				),
				'vMultiple' => array(
					false,	// normal
					false,	// normal
					true,	// multiple options
				),
			),					
			array(	// Single set of radio buttons
				'strFieldID' => 'radio',
				'strSectionID' => 'selectors',
				'strTitle' => 'Radio Button',
				'strDescription' => 'Choose one from the radio buttons.',
				'strType' => 'radio',
				'vLabel' => array( 'a' => 'apple', 'b' => 'banana', 'c' => 'cherry' ),
				'vDefault' => 'b',	// banana				
			),
			array(	// Multiple sets of radio buttons
				'strFieldID' => 'radio_multiple',
				'strSectionID' => 'selectors',
				'strTitle' => 'Multiple Sets of Radio Buttons',
				'strDescription' => 'Multiple sets of radio buttons.',
				'strType' => 'radio',
				'vLabel' => array( 
					array( 1 => 'one', 2 => 'two' ),
					array( 3 => 'three', 4 => 'four', 5 => 'five' ),
					array( 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine' ),
				),
				'vDefault' => array(
					2,
					4,
					8,
				),
				'vDelimiter' => '<br />',
			),			
			array(	// Single Checkbox
				'strFieldID' => 'checkbox',
				'strSectionID' => 'selectors',
				'strTitle' => 'Single Checkbox',
				'strTip' => 'The description key can be omitted though.',
				'strDescription' => 'Check box\'s label can be a string, not an array.',	//'
				'strType' => 'checkbox',
				'vLabel' => 'One',	// notice that the label key is not an array
				'vDefault' => False,
			),	
			array(	// Multiple Checkboxes
				'strFieldID' => 'checkboxes',
				'strSectionID' => 'selectors',
				'strTitle' => 'Multiple Checkboxes',
				'strDescription' => 'The description can be omitted.',
				'strType' => 'checkbox',
				'vLabel' => array( 'moon' => 'Moon', 'earth' => 'Earth', 'sun' => 'Sun', 'mars' => 'Mars' ),
				'vDefault' => array( 'moon' => True, 'earth' => False, 'sun' => True, 'mars' => False ),
			),
			array(	// Size
				'strFieldID'		=> 'size_filed',
				'strSectionID'		=> 'sizes',
				'strTitle'			=> __( 'Size', 'admin-page-framework-demo' ),
				'strHelp'			=> __( 'In order to set a default value for the size field type, an array with the \'size\' and the \'unit\' keys needs to be passed.', 'admin-page-framework-demo' ),
				'strDescription'	=> __( 'The default units are the lengths for CSS.', 'admin-page-framework-demo' ),
				'strType'			=> 'size',
				'vDefault'			=> array( 'size' => 5, 'unit' => '%' ),
			),			
			array(	// Size with custom units
				'strFieldID'		=> 'size_custom_unit_filed',
				'strSectionID'		=> 'sizes',
				'strTitle'			=> __( 'Size with Custom Units', 'admin-page-framework-demo' ),
				'strHelp'			=> __( 'The units can be specified so it can be quantity, length, or capacity etc.', 'admin-page-framework-demo' ),
				'strType'			=> 'size',
				'vSizeUnits'		=> array(
					'grain'	=> 'grains',
					'dram'	=> 'drams',
					'ounce'	=> 'ounces',
					'pounds'	=> 'pounds',
				),
				'vDefault'			=> array( 'size' => 200, 'unit' => 'ounce' ),
			),						
			array(	// Multiple Sizes
				'strFieldID' => 'sizes_filed',
				'strSectionID' => 'sizes',
				'strTitle' => __( 'Multiple Sizes', 'admin-page-framework-demo' ),
				'strType' => 'size',
				'vLabel' => array(
					'weight'	=> __( 'Weight', 'admin-page-framework-demo' ),
					'length'	=> __( 'Length', 'admin-page-framework-demo' ),
					'capacity'	=> __( 'File Size', 'admin-page-framework-demo' ),
				),
				'vSizeUnits' => array( 	// notice that the array key structure corresponds to the vLabel array's.
					'weight'	=> array( 'mg'=>'mg', 'g'=>'g', 'kg'=>'kg' ),
					'length'	=> array( 'cm'=>'cm', 'mm'=>'mm', 'm'=>'m' ),
					'capacity'	=> array( 'b'=>'b', 'kb'=>'kb', 'mb'=>'mb', 'gb' => 'gb', 'tb' => 'tb' ),
				),
				'vDefault' => array(
					'weight' => array( 'size' => 15, 'unit' => 'g' ),
					'length' => array( 'size' => 100, 'unit' => 'mm' ),
					'capacity' => array( 'size' => 30, 'unit' => 'mb' ),
				),		
				'vDelimiter' => '<br />',
			)
		);
		$this->addSettingFields(			
			array( // Image Selector
				'strFieldID' => 'image_select_field',
				'strSectionID' => 'image_select',
				'strTitle' => __( 'Select an Image', 'admin-page-framework-demo' ),
				'strType' => 'image',
				'vLabel' => array( 'First Image', 'Second Image', 'Third Image' ),
				'vDefault' => array( admin_url( 'images/wordpress-logo.png' ) ), 
				'fAllowExternalSource' => false,
			),		
			array( // Image selector with additional attributes
				'strFieldID' => 'image_with_attributes',
				'strSectionID' => 'image_select',
				'strTitle' => __( 'Save Image Attributes', 'admin-page-framework-demo' ),
				'strType' => 'image',
				'vDelimiter' => '',
				'arrCaptureAttributes' => array( 'alt', 'id', 'title', 'caption', 'width', 'height', 'align', 'link' ),	// some attributes cannot be captured with external URLs and the old media uploader.
			),					
			array(	// Repeatable Image Fields
				'strFieldID' => 'image_select_field_repeater',
				'strSectionID' => 'image_select',
				'strTitle' => __( 'Repeatable Image Fields', 'admin-page-framework-demo' ),
				'vDelimiter' => '',
				'fRepeatable' => true,
				'strType' => 'image',
			),
			array( // Media File
				'strFieldID' => 'media_field',
				'strSectionID' => 'media_upload',
				'strTitle' => __( 'Media File', 'admin-page-framework-demo' ),
				'strType' => 'media',
				'fAllowExternalSource' => false,
			),	
			array( // Media File with Attributes
				'strFieldID' => 'media_with_attributes',
				'strSectionID' => 'media_upload',
				'strTitle' => __( 'Media File with Attributes', 'admin-page-framework-demo' ),
				'strType' => 'media',
				'arrCaptureAttributes' => array( 'id', 'caption', 'description' ),
			),				
			array( // Media Files
				'strFieldID' => 'media_fields',
				'strSectionID' => 'media_upload',
				'strTitle' => __( 'Multiple Media Files', 'admin-page-framework-demo' ),
				'strType' => 'media',
				'fRepeatable' => true,
			),				
			array( // Single File Upload Field
				'strFieldID' => 'file_single',
				'strSectionID' => 'file_uploads',
				'strTitle' => __( 'Single File Upload', 'admin-page-framework-demo' ),
				'strType' => 'file',
				'vLabel' => 'Select the file:',
			),					
			array( // Multiple File Upload Fields
				'strFieldID' => 'file_multiple',
				'strSectionID' => 'file_uploads',
				'strTitle' => __( 'Multiple File Uploads', 'admin-page-framework-demo' ),
				'strType' => 'file',
				'vLabel' => array( 'Fist File:', 'Second File:', 'Third File:' ),
				'vDelimiter' => '<br />',
			),	
			array( // Single File Upload Field
				'strFieldID' => 'file_repeatable',
				'strSectionID' => 'file_uploads',
				'strTitle' => __( 'Repeatable File Uploads', 'admin-page-framework-demo' ),
				'strType' => 'file',
				'fRepeatable' => true,
			)
		);
		$this->addSettingFields(			
			array(
				'strFieldID' => 'post_type_checklist',
				'strSectionID' => 'checklists',
				'strTitle' => 'Post Types',
				'strType' => 'posttype',
			),											
			array(
				'strFieldID' => 'taxonomy_checklist',
				'strSectionID' => 'checklists',
				'strTitle' => __( 'Taxonomy Checklist', 'admin-page-framework-demo' ),
				'strType' => 'taxonomy',
				'strHeight' => '200px',
				'vTaxonomySlug' => array( 'category', 'post_tag' ),
			),				
			array(
				'strFieldID' => 'taxonomy_checklist_all',
				'strSectionID' => 'checklists',
				'strTitle' => __( 'All Taxonomies', 'admin-page-framework-demo' ),
				'strType' => 'taxonomy',
				'strHeight' => '200px',
				'vTaxonomySlug' => get_taxonomies( '', 'names' ),
			)
		);
		$this->addSettingFields(			
			array( // Color Picker
				'strFieldID' => 'color_picker_field',
				'strSectionID' => 'color_picker',
				'strTitle' => __( 'Color Picker', 'admin-page-framework-demo' ),
				'strType' => 'color',
			),					
			array( // Multiple Color Pickers
				'strFieldID' => 'multiple_color_picker_field',
				'strSectionID' => 'color_picker',
				'strTitle' => __( 'Multiple Color Pickers', 'admin-page-framework-demo' ),
				'strType' => 'color',
				'vLabel' => array( 'First Color', 'Second Color', 'Third Color' ),
				'vDelimiter' => '<br />',
			),				
			array( // Repeatable Color Pickers
				'strFieldID' => 'color_picker_repeatable_field',
				'strSectionID' => 'color_picker',
				'strTitle' => __( 'Repeatable Color Picker Fields', 'admin-page-framework-demo' ),
				'strType' => 'color',
				'fRepeatable' => true,
			),										
			array( // Single Hidden Field
				'strFieldID' => 'hidden_single',
				'strSectionID' => 'hidden_field',
				'strTitle' => __( 'Single Hidden Field', 'admin-page-framework-demo' ),
				'strType' => 'hidden',
				'vDefault' => 'test value',
				'vLabel' => 'Test label.',
			),			
			array( // Multiple Hidden Fields
				'strFieldID' => 'hidden_miltiple',
				'strSectionID' => 'hidden_field',
				'strTitle' => 'Multiple Hidden Field',
				'strType' => 'hidden',
				'vDefault' => array( 'a', 'b', 'c' ),
				'vLabel' => array( 'Hidden Field 1', 'Hidden Field 2', 'Hidden Field 3' ),
			),							
			array( // Submit button as a link
				'strFieldID' => 'submit_button_link',
				'strSectionID' => 'submit_buttons',
				'strTitle' => 'Link Button',
				'strType' => 'submit',
				'strDescription' => 'This button serves as a hyper link.',
				'vLabel' => array( 'Google', 'Yahoo', 'Bing' ),
				'vLink'	=> array( 'http://www.google.com', 'http://www.yahoo.com', 'http://www.bing.com' ),
				'vClassAttribute' => 'button button-secondary',
				'vDelimiter' => '',
			),			
			array( // Submit button as a redirect
				'strFieldID' => 'submit_button_redirect',
				'strSectionID' => 'submit_buttons',
				'strTitle' => 'Redirect Button',
				'strType' => 'submit',
				'strDescription' => 'Unlike the above link buttons, this button saves the options and then redirects to: ' . admin_url(),
				'vLabel' => 'Dashboard',
				'vRedirect'	=> admin_url(),
				'vClassAttribute' => 'button button-secondary',
			),
			array( // Reset Submit button
				'strFieldID' => 'submit_button_reset',
				'strSectionID' => 'submit_buttons',
				'strTitle' => 'Reset Button',
				'strType' => 'submit',
				'vLabel' => __( 'Reset', 'admin-page-framework-demo' ),
				'vReset' => true,
				// 'vClassAttribute' => 'button button-secondary',
			)
		);
		$this->addSettingFields(			
			array(
				'strFieldID' => 'verify_text_field',
				'strSectionID' => 'verification',
				'strTitle' => __( 'Verify Text Input', 'admin-page-framework-demo' ),
				'strType' => 'text',
				'strDescription' => __( 'Enter a non numeric value here.', 'admin-page-framework-demo' ),
			),
			array(
				'strFieldID' => 'verify_text_field_submit',	// this submit field ID can be used in a validation callback method
				'strSectionID' => 'verification',
				'strType' => 'submit',		
				'vLabel' => __( 'Verify', 'admin-page-framework-demo' ),
			)
		);	
		
		$this->addSettingFields(			
			array(
				'strFieldID' => 'geometrical_coordinates',
				'strSectionID' => 'geometry',
				'strTitle' => __( 'Geometrical Coordinates', 'admin-page-framework-demo' ),
				'strType' => 'geometry',
				'strDescription' => __( 'Get the coordinates from the map.', 'admin-page-framework-demo' ),
				'vDefault' => array(
					'latitude' => 20,
					'longitude' => 20,
				),
			)
		);
		$this->addSettingFields(
			array(	// Single date picker
				'strFieldID' => 'date',
				'strSectionID' => 'date_pickers',
				'strTitle' => __( 'Date', 'admin-page-framework-demo' ),
				'strType' => 'date',
				'vDateFormat' => 'yy/mm/dd',	// yy/mm/dd is the default format.
			),			
			array(	// Multiple date pickers
				'strFieldID' => 'dates',
				'strSectionID' => 'date_pickers',
				'strTitle' => __( 'Dates', 'admin-page-framework-demo' ),
				'strType' => 'date',
				'vLabel' => array( 
					'start' => __( 'Start Date: ', 'amin-page-framework-demo' ), 
					'end' => __( 'End Date: ', 'amin-page-framework-demo' ), 
				),
				'vDateFormat' => 'yy-mm-dd',	// yy/mm/dd is the default format.
				'vDelimiter' => '<br />',
			),	
			array(	// Single time picker
				'strFieldID' => 'time',
				'strSectionID' => 'date_pickers',
				'strTitle' => __( 'Time', 'admin-page-framework-demo' ),
				'strType' => 'time',
				'vTimeFormat' => 'H:mm',	// H:mm is the default format.
			),		
			array(	// Single date time picker
				'strFieldID' => 'date_time',
				'strSectionID' => 'date_pickers',
				'strTitle' => __( 'Date & Time', 'admin-page-framework-demo' ),
				'strType' => 'date_time',
				'vDateFormat' => 'yy-mm-dd',	// H:mm is the default format.
				'vTimeFormat' => 'H:mm',	// H:mm is the default format.
			),		
			array(	// Multiple date time pickers
				'strFieldID' => 'dates_time_multiple',
				'strSectionID' => 'date_pickers',
				'strTitle' => __( 'Multiple Date and Time', 'admin-page-framework-demo' ),
				'strDescription' => __( 'With different time formats', 'admin-page-framework-demo' ),
				'strType' => 'date_time',
				'vLabel' => array( 
					__( 'Default', 'amin-page-framework-demo' ), 
					__( 'AM PM', 'amin-page-framework-demo' ), 
					__( 'Time Zone', 'amin-page-framework-demo' ), 
				),
				'vTimeFormat' => array(
					'H:mm',
					'hh:mm tt',
					'hh:mm tt z',
				),
				'vDateFormat' => 'yy-mm-dd',	// yy/mm/dd is the default format.
				'vDelimiter' => '<br />',
			),				
			array()
		);
		$this->addSettingFields(			
			array(
				'strFieldID' => 'dials',
				'strSectionID' => 'dial',
				'strTitle' => __( 'Multiple Dials', 'admin-page-framework-demo' ),
				'strType' => 'dial',
				'vLabel' => array(
					__( 'Disable display input', 'admin-page-framework-demo' ),
					__( 'Cursor mode', 'admin-page-framework-demo' ),
					__( 'Display previous value (effect)', 'admin-page-framework-demo' ),				
					__( 'Angle offset', 'admin-page-framework-demo' ),				
					__( 'Angle offset and arc', 'admin-page-framework-demo' ),				
					__( '5-digit values, step 1000', 'admin-page-framework-demo' ),				
				),
				// For details, see https://github.com/aterrien/jQuery-Knob
				'vDataAttribute' => array( 
					array(
						'width' => 100,
						'displayInput' => 'false',
					),
					array(
						'width' => 150,
						'cursor' => 'true',
						'thickness'	=> '.3', 
						'fgColor' => '#222222',
					),					
					array(
						'width' => 200,
						'min'	=> -100, 
						'displayPrevious'	=> 'true', // a boolean value also needs to be passed as string
					),
					array(
						'angleOffset' => 90,
						'linecap' => 'round',
					),
					array(
						'fgColor' => '#66CC66',
						'angleOffset' => -125,
						'angleArc' => 250,
					),
					array(
						'step' => 1000,
						'min' => -15000,
						'max' => 15000,
						'displayPrevious' => true,
					),                        
				),
			),
			array(
				'strFieldID' => 'dial_big',
				'strSectionID' => 'dial',
				'strTitle' => __( 'Big', 'admin-page-framework-demo' ),
				'strType' => 'dial',
				'vDataAttribute' => array(
					'width' => 400,
					'height' => 400,
				),
			),
			array()
		);
		
		$this->addSettingFields(			
			array(
				'strFieldID' => 'font_field',
				'strSectionID' => 'font',
				'strTitle' => __( 'Font Upload', 'admin-page-framework-demo' ),
				'strType' => 'font',
				'strDescription' => __( 'Set the URL of the font.', 'admin-page-framework-demo' ),
			),
			array()
		);
		
		$this->addSettingFields(			
			array( // Delete Option Button
				'strFieldID' => 'submit_manage',
				'strSectionID' => 'submit_buttons_manage',
				'strTitle' => 'Delete Options',
				'strType' => 'submit',
				'vClassAttribute' => 'button-secondary',
				'vLabel' => 'Delete Options',
				'vLink'	=> admin_url( 'admin.php?page=apf_manage_options&tab=delete_options_confirm' )
			),			
			array( // Delete Option Confirmation Button
				'strFieldID' => 'submit_delete_options_confirmation',
				'strSectionID' => 'submit_buttons_confirm',
				'strTitle' => 'Delete Options',
				'strType' => 'submit',
				'vClassAttribute' => 'button-secondary',
				'vLabel' => 'Delete Options',
				'vRedirect'	=> admin_url( 'admin.php?page=apf_manage_options&tab=saved_data&settings-updated=true' )
			),			
			array(
				'strFieldID' => 'export_format_type',			
				'strSectionID' => 'exports',
				'strTitle' => 'Export Format Type',
				'strType' => 'radio',
				'strDescription' => 'Choose the file format. Array means the PHP serialized array.',
				'vLabel' => array( 'array' => 'Serialized Array', 'json' => 'JSON', 'text' => 'Text' ),
				'vDefault' => 'array',
			),			
			array(	// Single Export Button
				'strFieldID' => 'export_single',
				'strSectionID' => 'exports',
				// 'strTitle' => 'Single Export Button',
				'strType' => 'export',
				'strDescription' => __( 'Download the saved option data.', 'admin-page-framework-demo' ),
				'vLabel' => 'Export Options',
			),
			array(	// Multiple Export Buttons
				'strFieldID' => 'export_multiple',
				'strSectionID' => 'exports',
				'strTitle' => 'Multiple Export Buttons',
				'strType' => 'export',
				'strDescription' => __( 'Download the custom set data.', 'admin-page-framework-demo' ),
				'vLabel' => array( 'Pain Text', 'JSON', 'Serialized Array' ),
				'vExportFileName' => array( 'plain_text.txt', 'json.json', 'serialized_array.txt' ),
				'vExportFormat' => array( 'text', 'json', 'array' ),
				'vExportData' => array(
					'Hello World!',	// export plain text
					( array ) $this->oProps,	// export an object
					array( 'a', 'b', 'c' ),	// export a serialized array
				),
			),		
			array(
				'strFieldID' => 'import_format_type',			
				'strSectionID' => 'imports',
				'strTitle' => 'Import Format Type',
				'strType' => 'radio',
				'strDescription' => 'The text format type will not set the option values properly. However, you can see that the text contents are directly saved in the database.',
				'vLabel' => array( 'array' => 'Serialized Array', 'json' => 'JSON', 'text' => 'Text' ),
				'vDefault' => 'array',
			),
			array(	// Single Import Button
				'strFieldID' => 'import_single',
				'strSectionID' => 'imports',
				'strTitle' => 'Single Import Field',
				'strType' => 'import',
				'strDescription' => __( 'Upload the saved option data.', 'admin-page-framework-demo' ),
				'vLabel' => 'Import Options',
				// 'vImportFormat' => isset( $_POST[ $this->oProps->strClassName ]['apf_manage_options']['imports']['import_format_type'] ) ? $_POST[ $this->oProps->strClassName ]['apf_manage_options']['imports']['import_format_type'] : 'array',
			),			
			array()
		);
		
 		$this->addLinkToPluginDescription( 
			"<a href='http://www.google.com'>Google</a>",
			"<a href='http://www.yahoo.com'>Yahoo!</a>",
			"<a href='http://en.michaeluno.jp'>miunosoft</a>",
			"<a href='https://github.com/michaeluno/admin-page-framework' title='Contribute to the GitHub repository!' >Repository</a>"
		);
		$this->addLinkToPluginTitle(
			"<a href='http://www.wordpress.org'>WordPress</a>"
		);
		
    }
		
	/*
	 * Built-in Field Types
	 * */
	public function do_apf_builtin_field_types() {
		submit_button();
	}
	
	/*
	 * Custon Field Types
	 * */
	public function do_apf_custom_field_types() {
		submit_button();
	}
	
	/*
	 * Manage Options
	 * */
	public function do_apf_manage_options_saved_data() {	// do_ + page slug + _ + tab slug
		?>
		<h3>Saved Data</h3>
		<?php
			echo $this->oDebug->getArray( $this->oProps->arrOptions ); 
	}
	public function do_apf_manage_options_properties() {	// do_ + page slug + _ + tab slug
		?>
		<h3><?php _e( 'Framework Properties', 'admin-page-framework-demo' ); ?></h3>
		<p><?php _e( 'You can view and modify the property values stored in the framework.', 'admin-page-framework-demo' ); ?></p>
		<pre><code>$this-&gt;oDebug-&gt;getArray( get_object_vars( $this-&gt;oProps ) );</code></pre>		
		<?php
			$this->oDebug->dumpArray( get_object_vars( $this->oProps ) );
	}
	public function do_apf_manage_options_messages() {	// do_ + page slug + _ + tab slug
		?>
		<h3><?php _e( 'Framework Messages', 'admin-page-framework-demo' ); ?></h3>
		<p><?php _e( 'You can change the framework\'s defined internal messages by directly modifying the <code>$arrMessages</code> array in the oMsg object.', 'admin-page-framework-demo' ); // ' syntax fixer ?></p>
		<pre><code>echo $this-&gt;oDebug-&gt;getArray( $this-&gt;oMsg-&gt;arrMessages );</code></pre>
		<?php
			echo $this->oDebug->getArray( $this->oMsg->arrMessages );
	}
	
	/*
	 * The sample page and the hidden page
	 */
	public function do_apf_sample_page() {
		
		echo "<p>" . __( 'This is a sample page that has a link to a hidden page created by the framework.', 'admin-page-framework-demo' ) . "</p>";
		$strLinkToHiddenPage = $this->oUtil->getQueryAdminURL( array( 'page' => 'apf_hidden_page' ) );
		echo "<a href='{$strLinkToHiddenPage}'>" . __( 'Go to Hidden Page', 'admin-page-framework-demo' ). "</a>";
	
	}
	public function do_apf_hidden_page() {
		
		echo "<p>" . __( 'This is a hidden page.', 'admin-page-framework-demo' ) . "</p>";
		echo "<p>" . __( 'It is useful when you have a setting page that requires a proceeding page.', 'admin-page-framework-demo' ) . "</p>";
		$strLinkToGoBack = $this->oUtil->getQueryAdminURL( array( 'page' => 'apf_sample_page' ) );
		echo "<a href='{$strLinkToGoBack}'>" . __( 'Go Back', 'admin-page-framework-demo' ). "</a>";
		
	}
	
	/*
	 * Import and Export Callbacks
	 * */
	public function export_format_APF_Demo_export_single( $strFormatType, $strFieldID ) {	// export_format_ + {extended class name} + _ + {field id}
		
		return isset( $_POST[ $this->oProps->strOptionKey ]['apf_manage_options']['exports']['export_format_type'] ) 
			? $_POST[ $this->oProps->strOptionKey ]['apf_manage_options']['exports']['export_format_type']
			: $strFormatType;
		
	}	
	public function import_format_apf_manage_options_export_import( $strFormatType, $strFieldID ) {	// import_format_ + page slug + _ + tab slug
		
		return isset( $_POST[ $this->oProps->strOptionKey ]['apf_manage_options']['imports']['import_format_type'] ) 
			? $_POST[ $this->oProps->strOptionKey ]['apf_manage_options']['imports']['import_format_type']
			: $strFormatType;
		
	}
	public function import_APF_Demo_import_single( $vData, $arrOldOptions, $strFieldID, $strInputID, $strImportFormat, $strOptionKey ) {	// import_ + {extended class name} + _ + {field id}

		if ( $strImportFormat == 'text' ) {
			$this->setSettingNotice( __( 'The text import type is not supported.', 'admin-page-framework-demo' ) );
			return $arrOldOptions;
		}
		
		$this->setSettingNotice( __( 'Importing options are validated.', 'admin-page-framework-demo' ), 'updated' );
		return $vData;
		
	}
	
	/*
	 * Validation Callbacks
	 * */
	public function validation_APF_Demo_verify_text_field_submit( $arrNewInput, $arrOldOptions ) {	// validation_ + {extended class name} + _ + {field ID}
		
		// Set a flag.
		$fVerified = true;
		
		// We store values that have an error in an array and pass it to the setFieldErrors() method.
		// It internally stores the error array in a temporary area of the database called transient.
		// The used name of the transient is a md5 hash of 'instantiated class name' + '_' + 'page slug'. 
		// The library class will search for this transient when it renders the form fields 
		// and if it is found, it will display the error message set in the field array. 
		$arrErrors = array();

		// Check if the submitted value meets your criteria. As an example, here a numeric value is expected.
		if ( ! is_numeric( $arrNewInput['apf_builtin_field_types']['verification']['verify_text_field'] ) ) {
			
			// Start with the section key in $arrErrors, not the key of page slug.
			$arrErrors['verification']['verify_text_field'] = 'The value must be numeric: ' . $arrNewInput['apf_builtin_field_types']['verification']['verify_text_field'];	
			$fVerified = false;
			
		}
		
		// An invalid value is found.
		if ( ! $fVerified ) {
		
			// Set the error array for the input fields.
			$this->setFieldErrors( $arrErrors );		
			$this->setSettingNotice( 'There was an error in your input.' );
			return $arrOldOptions;
			
		}
				
		return $arrNewInput;		
		
	}
	public function validation_apf_builtin_field_types_files( $arrInput, $arrOldPageOptions ) {	// validation_ + page slug + _ + tab slug

		// Display the uploaded file information.
		$arrFileErrors = array();
		$arrFileErrors[] = $_FILES[ $this->oProps->strOptionKey ]['error']['apf_builtin_field_types']['file_uploads']['file_single'];
		$arrFileErrors[] = $_FILES[ $this->oProps->strOptionKey ]['error']['apf_builtin_field_types']['file_uploads']['file_multiple'][0];
		$arrFileErrors[] = $_FILES[ $this->oProps->strOptionKey ]['error']['apf_builtin_field_types']['file_uploads']['file_multiple'][1];
		$arrFileErrors[] = $_FILES[ $this->oProps->strOptionKey ]['error']['apf_builtin_field_types']['file_uploads']['file_multiple'][2];
		foreach( $_FILES[ $this->oProps->strOptionKey ]['error']['apf_builtin_field_types']['file_uploads']['file_repeatable'] as $arrFile )
			$arrFileErrors[] = $arrFile;
			
		if ( in_array( 0, $arrFileErrors ) ) 
			$this->setSettingNotice( '<h3>File(s) Uploaded</h3>' . $this->oDebug->getArray( $_FILES ), 'updated' );
		
		return $arrInput;
		
	}
	
	public function validation_APF_Demo( $arrInput, $arrOldOptions ) {
		
		// If the delete options button is pressed, return an empty array that will delete the entire options stored in the database.
		if ( isset( $_POST[ $this->oProps->strOptionKey ]['apf_manage_options']['submit_buttons_confirm']['submit_delete_options_confirmation'] ) ) 
			return array();
			
		return $arrInput;
		
	}
			
	/*
	 * Read Me
	 * */ 
	public function do_before_apf_read_me() {		// do_before_ + page slug 

		include_once( dirname( __FILE__ ) . '/third-party/wordpress-plugin-readme-parser/parse-readme.php' );
		$this->oWPReadMe = new WordPress_Readme_Parser;
		$this->arrWPReadMe = $this->oWPReadMe->parse_readme( dirname( __FILE__ ) . '/readme.txt' );
	
	}
	public function do_apf_read_me_description() {		// do_ + page slug + _ + tab slug
		echo $this->arrWPReadMe['sections']['description'];
		// var_dump( $this->arrWPReadMe );
	}
	public function do_apf_read_me_installation() {		// do_ + page slug + _ + tab slug
		// echo htmlspecialchars( $this->arrWPReadMe['sections']['installation'], ENT_QUOTES, bloginfo( 'charset' ) );
		echo $this->arrWPReadMe['sections']['installation'];
	}
	public function do_apf_read_me_frequently_asked_questions() {	// do_ + page slug + _ + tab slug
		echo $this->arrWPReadMe['sections']['frequently_asked_questions'];
	}
	public function do_apf_read_me_other_notes() {
		echo $this->arrWPReadMe['remaining_content'];
	}
	public function do_apf_read_me_screenshots() {		// do_ + page slug + _ + tab slug
		echo $this->arrWPReadMe['sections']['screenshots'];
	}	
	public function do_apf_read_me_changelog() {		// do_ + page slug + _ + tab slug
		echo $this->arrWPReadMe['sections']['changelog'];
	}
	
	/*
	 * Custom field types - This is another way to register a custom field type. 
	 * This method gets fired when the framework tries to define field types. 
	 */
 	public function field_types_APF_Demo( $arrFieldTypeDefinitions ) {	// field_types_ + {extended class name}
				
		// 1. Include the file that defines the custom field type. 
		// This class should extend the predefined abstract class that the library prepares already with necessary methods.
		$strFilePath = dirname( __FILE__ ) . '/third-party/geometry-custom-field-type/GeometryCustomFieldType.php';
		if ( file_exists( $strFilePath ) ) include_once( $strFilePath );
		
		// 2. Instantiate the class - use the getDefinitionArray() method to get the field type definition array.
		// Then assign it to the filtering array with the key of the field type slug. 
		$oFieldType = new GeometryCustomFieldType( 'APF_Demo', 'geometry', $this->oMsg );
		$arrFieldTypeDefinitions['geometry'] = $oFieldType->getDefinitionArray();
		
		// 3. Return the modified array.
		return $arrFieldTypeDefinitions;
		
	} 
	
}
// Instantiate the main framework class so that the pages and form fields will be created. 
if ( is_admin() )  
	new APF_Demo;			
	
class APF_PostType extends AdminPageFramework_PostType {
	
	public function start_APF_PostType() {	// start_ + extended class name
	
		// the setUp() method is too late to add taxonomies. So we use start_{class name} action hook.
	
		$this->setAutoSave( false );
		$this->setAuthorTableFilter( true );
		$this->addTaxonomy( 
			'apf_sample_taxonomy', // taxonomy slug
			array(			// argument - for the argument array keys, refer to : http://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments
				'labels' => array(
					'name' => 'Sample Genre',
					'add_new_item' => 'Add New Genre',
					'new_item_name' => "New Genre"
				),
				'show_ui' => true,
				'show_tagcloud' => false,
				'hierarchical' => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_table_filter' => true,	// framework specific key
				'show_in_sidebar_menus' => true,	// framework specific key
			)
		);
		$this->addTaxonomy( 
			'apf_second_taxonomy', 
			array(
				'labels' => array(
					'name' => 'Non Hierarchical',
					'add_new_item' => 'Add New Taxonomy',
					'new_item_name' => "New Sample Taxonomy"
				),
				'show_ui' => true,
				'show_tagcloud' => false,
				'hierarchical' => false,
				'show_admin_column' => true,
				'show_in_nav_menus' => false,
				'show_table_filter' => true,	// framework specific key
				'show_in_sidebar_menus' => false,	// framework specific key
			)
		);

		$this->setFooterInfoLeft( '<br />Custom Text on the left hand side.' );
		$this->setFooterInfoRight( '<br />Custom text on the right hand side' );
		
	}
	
	/*
	 * Extensible methods
	 */
	public function setColumnHeader( $arrColumnHeader ) {
		$arrColumnHeaders = array(
			'cb'			=> '<input type="checkbox" />',	// Checkbox for bulk actions. 
			'title'			=> __( 'Title', 'admin-page-framework' ),		// Post title. Includes "edit", "quick edit", "trash" and "view" links. If $mode (set from $_REQUEST['mode']) is 'excerpt', a post excerpt is included between the title and links.
			'author'		=> __( 'Author', 'admin-page-framework' ),		// Post author.
			// 'categories'	=> __( 'Categories', 'admin-page-framework' ),	// Categories the post belongs to. 
			// 'tags'		=> __( 'Tags', 'admin-page-framework' ),	// Tags for the post. 
			'comments' 		=> '<div class="comment-grey-bubble"></div>', // Number of pending comments. 
			'date'			=> __( 'Date', 'admin-page-framework' ), 	// The date and publish status of the post. 
			'samplecolumn'			=> __( 'Sample Column' ),
		);		
		return array_merge( $arrColumnHeader, $arrColumnHeaders );
	}
	// public function setSortableColumns( $arrColumns ) {
		// return array_merge( $arrColumns, $this->oProp->arrColumnSortable );		
	// }	
	
	/*
	 * Callback methods
	 */
	public function cell_apf_posts_samplecolumn( $strCell, $intPostID ) {	// cell_ + post type + column key
		
		return "the post id is : {$intPostID}";
		
	}

	
}
new APF_PostType( 
	'apf_posts', 	// post type slug
	array(			// argument - for the array structure, refer to http://codex.wordpress.org/Function_Reference/register_post_type#Arguments
		'labels' => array(
			'name' => 'Admin Page Framework',
			'all_items' => __( 'Sample Posts', 'admin-page-framework-demo' ),
			'singular_name' => 'Admin Page Framework',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New APF Post',
			'edit' => 'Edit',
			'edit_item' => 'Edit APF Post',
			'new_item' => 'New APF Post',
			'view' => 'View',
			'view_item' => 'View APF Post',
			'search_items' => 'Search APF Post',
			'not_found' => 'No APF Post found',
			'not_found_in_trash' => 'No APF Post found in Trash',
			'parent' => 'Parent APF Post'
		),
		'public' => true,
		'menu_position' => 110,
		// 'supports' => array( 'title', 'editor', 'comments', 'thumbnail' ),	// 'custom-fields'
		'supports' => array( 'title' ),
		'taxonomies' => array( '' ),
		'has_archive' => true,
		'show_admin_column' => true,	// ( framework specific key ) this is for custom taxonomies to automatically add the column in the listing table.
		'menu_icon' => plugins_url( 'asset/image/wp-logo_16x16.png', __FILE__ ),
		// ( framework specific key ) this sets the screen icon for the post type.
		'screen_icon' => dirname( __FILE__  ) . '/asset/image/wp-logo_32x32.png', // a file path can be passed instead of a url, plugins_url( 'asset/image/wp-logo_32x32.png', __FILE__ )
	)		
);	// should not use "if ( is_admin() )" for the this class because posts of custom post type can be accessed from the front-end pages.
	
	

class APF_MetaBox extends AdminPageFramework_MetaBox {
	
	public function start_APF_MetaBox() {
		
		add_filter( 'the_content', array( $this, 'printMetaFieldValues' ) );
		
	}
	
	public function setUp() {
		
		$this->addHelpText( 
			__( 'This text will appear in the contextual help pane.', 'admin-page-framework-demo' ), 
			__( 'This description goes to the sidebar of the help pane.', 'admin-page-framework-demo' )
		);
		
		$this->addSettingFields(
			array(
				'strFieldID'		=> 'sample_metabox_text_field',
				'strTitle'			=> 'Text Input',
				'strDescription'	=> 'The description for the field.',
				'strType'			=> 'text',
				'strHelp'			=> 'This is help text.',
				'strHelpAside'		=> 'This is additional help text which goes to the side bar of the help pane.',
			),
			array(
				'strFieldID'		=> 'sample_metabox_textarea_field',
				'strTitle'			=> 'Textarea',
				'strDescription'	=> 'The description for the field.',
				'strHelp'			=> __( 'This a <em>text area</em> input field, which is larger than the <em>text</em> input field.', 'admin-page-framework-demo' ),
				'strType'			=> 'textarea',
				'vCols'				=> 60,
				'vDefault'			=> 'This is a default text.',
			),
			array(	// Rich Text Editor
				'strFieldID' 		=> 'sample_rich_textarea',
				'strTitle' 			=> 'Rich Text Editor',
				'strType' 			=> 'textarea',
				'vRich' 			=> true,	// array( 'media_buttons' => false )  <-- a setting array can be passed. For the specification of the array, see http://codex.wordpress.org/Function_Reference/wp_editor
			),				
			array(
				'strFieldID'		=> 'checkbox_field',
				'strTitle'			=> 'Checkbox Input',
				'strDescription'	=> 'The description for the field.',
				'strType'			=> 'checkbox',
				'vLabel'			=> 'This is a check box.',
			),
			array(
				'strFieldID'		=> 'select_filed',
				'strTitle'			=> 'Select Box',
				'strDescription'	=> 'The description for the field.',
				'strType'			=> 'select',
				'vLabel' => array( 
					'one' => __( 'One', 'demo' ),
					'two' => __( 'Two', 'demo' ),
					'three' => __( 'Three', 'demo' ),
				),
				'vDefault' 			=> 'one',	// 0 means the first item
			),		
			array (
				'strFieldID'		=> 'radio_field',
				'strTitle'			=> 'Radio Group',
				'strDescription'	=> 'The description for the field.',
				'strType'			=> 'radio',
				'vLabel' => array( 
					'one' => __( 'Option One', 'demo' ),
					'two' => __( 'Option Two', 'demo' ),
					'three' => __( 'Option Three', 'demo' ),
				),
				'vDefault' => 'one',
			),
			array (
				'strFieldID'		=> 'checkbox_group_field',
				'strTitle'			=> 'Checkbox Group',
				'strDescription'	=> 'The description for the field.',
				'strType'			=> 'checkbox',
				'vLabel' => array( 
					'one' => __( 'Option One', 'admin-page-framework-demo' ),
					'two' => __( 'Option Two', 'admin-page-framework-demo' ),
					'three' => __( 'Option Three', 'admin-page-framework-demo' ),
				),
				'vDefault' => array(
					'one' => true,
					'two' => false,
					'three' => false,
				),
			),			
			array (
				'strFieldID'		=> 'image_field',
				'strTitle'			=> 'Image',
				'strDescription'	=> 'The description for the field.',
				'strType'			=> 'image',
			),		
			array (
				'strFieldID'		=> 'color_field',
				'strTitle'			=> __( 'Color', 'admin-page-framework-demo' ),
				'strType'			=> 'color',
			),	
			array (
				'strFieldID'		=> 'size_field',
				'strTitle'			=> __( 'Size', 'admin-page-framework-demo' ),
				'strType'			=> 'size',
				'vDefault'			=> array( 'size' => 5, 'unit' => '%' ),
			),						
			array (
				'strFieldID'		=> 'sizes_field',
				'strTitle'			=> __( 'Multiple Sizes', 'admin-page-framework-demo' ),
				'strType'			=> 'size',
				'vLabel' => array(
					'weight'	=> __( 'Weight', 'admin-page-framework-demo' ),
					'length'	=> __( 'Length', 'admin-page-framework-demo' ),
					'capacity'	=> __( 'File Size', 'admin-page-framework-demo' ),
				),
				'vSizeUnits' => array( 	// notice that the array key structure corresponds to the vLabel array's.
					'weight'	=> array( 'mg'=>'mg', 'g'=>'g', 'kg'=>'kg' ),
					'length'	=> array( 'cm'=>'cm', 'mm'=>'mm', 'm'=>'m' ),
					'capacity'	=> array( 'b'=>'b', 'kb'=>'kb', 'mb'=>'mb', 'gb' => 'gb', 'tb' => 'tb' ),
				),
				'vDefault' => array(
					'weight' => array( 'size' => 15, 'unit' => 'g' ),
					'length' => array( 'size' => 100, 'unit' => 'mm' ),
					'capacity' => array( 'size' => 30, 'unit' => 'mb' ),
				),		
				'vDelimiter' => '<br />',
			),		
			array (
				'strFieldID'		=> 'taxonomy_checklist',
				'strTitle'			=> __( 'Taxonomy Checklist', 'admin-page-framework-demo' ),
				'strType'			=> 'taxonomy',
				'vTaxonomySlug' => get_taxonomies( '', 'names' ),
			),				
			array()
		);		
	}
	
	public function printMetaFieldValues( $strContent ) {
		
		if ( ! isset( $GLOBALS['post']->ID ) || get_post_type() != 'apf_posts' ) return $strContent;
			
		// 1. To retrieve the meta box data	- get_post_meta( $post->ID ) will return an array of all the meta field values.
		// or if you know the field id of the value you want, you can do $value = get_post_meta( $post->ID, $field_id, true );
		$intPostID = $GLOBALS['post']->ID;
		$arrPostData = array();
		foreach( ( array ) get_post_custom_keys( $intPostID ) as $strKey ) 	// This way, array will be unserialized; easier to view.
			$arrPostData[ $strKey ] = get_post_meta( $intPostID, $strKey, true );
		
		// 2. To retrieve the saved options in the setting pages created by the framework - use the get_option() function.
		// The key name is the class name by default. This can be changed by passing an arbitrary string 
		// to the first parameter of the constructor of the AdminPageFramework class.		
		$arrSavedOptions = get_option( 'APF_Demo' );
			
		return "<h3>" . __( 'Saved Meta Field Values', 'admin-page-framework-demo' ) . "</h3>" 
			. $this->oDebug->getArray( $arrPostData )
			. "<h3>" . __( 'Saved Setting Options', 'admin-page-framework-demo' ) . "</h3>" 
			. $this->oDebug->getArray( $arrSavedOptions );

	}
	
	public function validation_APF_MetaBox( $arrInput, $arrOldInput ) {
		
		// You can check the passed values and correct the data by modifying them.
		// $this->oDebug->logArray( $arrInput );
		return $arrInput;
		
	}
	
}
new APF_MetaBox(
	'sample_custom_meta_box',
	'My Custom Meta Box',
	array( 'apf_posts' ),	// post, page, etc.
	'normal',
	'default'
);
		
/*
 * If you find this framework useful, include it in your project!
 * And please leave a nice comment in the review page, http://wordpress.org/support/view/plugin-reviews/admin-page-framework
 * 
 * If you have a suggestion, the GitHub repository is open to anybody so post an issue there.
 * https://github.com/michaeluno/admin-page-framework/issues
 * 
 * Happy coding!
 */