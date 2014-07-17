=== Admin Page Framework ===
Contributors: Michael Uno, miunosoft
Donate link: http://michaeluno.jp/en/donate
Tags: admin, administration, admin panel, option, options, setting, settings, Settings API, API, framework, library, class, classes, developers, developer tool, meta box, custom post type, utility, utilities, field, fields, custom field, custom fields, tool, tools
Requires at least: 3.3
Tested up to: 3.9.1
Stable tag: 3.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides simpler means of building administration pages for plugin and theme developers. 

== Description ==
It provides plugin and theme developers with easier means of creating option pages. Extend the library class and pass your arrays that define the form elements to the predefined class methods. The library handles all the complex coding behind the scene and creates the pages and the forms for you. The package includes a demo plugin which helps you walk through necessary features.

**Notes:** this framework does not do anything by itself. If you are not a developer, you do not need this.

= Features =
* **Root Page, Sub Pages, and Tabs** - it allows you to instantly create a top level page and the sub pages of it, plus tabs inside the sub pages.
* **Extensible** - the created admin pages will become highly extensible with the automatically created hooks. In other words, it empowers other developers to customize your plugin or theme. That will result on making your projects grow.
* **Import and Export Options** - buttons that the user can import and export settings by uploading and downloading text files.
* **Image Upload** - it lets the user easily upload images to the site or the user can choose from existent urls or already uploaded files.
* **Color Picker** - it lets the user easily pick colors.
* **Rich Text Editor** - supports the rich text editor form input.
* **Section Tabs** - Form sections can be displayed in a tabbed box.
* **Repeatable Sections and Fields** - supports repeatable form sections and fields.
* **Sortable Fields** - supports sortable fields.
* **Reset Button** - create a reset button that lets your users to initialize the saved options.
* **Validation and Error Messages** - with the pre-defined validation callbacks, the user's submitting data can be verified as a part of using the Settings API. Furthermore, by setting the error array, you can display the error message to the user.
* **Custom Post Types** - the framework provides methods to create custom post types.
* **Meta Boxes** - the framework provides methods to create custom meta boxes with form elements that you define.
* **Taxonomy Fields** - the framework provides methods to add fields in the taxonomy definition page.
* **Contextual Help Pane** - help contents can be added to the contextual help pane that appears at the top right of each screen.
* **Custom Field Types** - your own field type can be registered. 

= Built-in Field Types =
* `text` - a normal field to enter text input.
* `password` - a masked text input field.
* `textarea` - a text input field with multiple lines. It supports rich text editor.
* `radio` - a set of radio buttons that lets the user pick an option.
* `checkbox` - a check box that lets the user enable/disable an item.
* `select` - a drop-down list that lest the user pick one or more item(s) from a list.
* `hidden` - a hidden field that will be useful to insert invisible values.
* `file` - a file uploader that lets the user upload files.
* `image` - a custom text field with the image uploader script that lets the user set the image URL.
* `media` - a custom text field with the media uploader script that lets the user set the file URL.
* `color` - a custom text field with the color picker script.
* `submit` - a submit button that lets the user send the form.
* `export` - a custom submit field that lets the user export the stored data.
* `import` - a custom combination field of the file and the submit fields that let the user import data.
* `posttype` - a set of check-lists of taxonomies enabled on the site in a tabbed box.
* `taxonomy` - check-lists of taxonomies enabled on the site in a tabbed box.
* `size` - a combination field of the text and the select fields that let the user set sizes with a selectable unit.
* `section_title` - a text field type that will be placed in the section title so that it lets the user set the section title.

= Custom Field Types = 
You can include your own custom field types when they are necessary. The reason that they are not built-in is to keep the library size as small as possible. The example custom field types are included in the demo plugin.

* `geometry` - a location selector with the Google map.
* `date`, `time`, `date_time` - date and time fields with the date picker.
* `dial` - a dial input field.
* `font` - a font uploader and its preview.
* `revealer` - a selector field that displays a hidden HTML element.
* `grid` - a drag and drop grid composer.
* `autocomplete` - a custom text field that shows a predefined pop-up autocomplete list.

= Necessary Files =
* **`admin-page-framework.min.php`** is in the *library* folder. Alternatively you may use **`admin-page-framework.php`** located in the *development* folder. In that case, all the class files in the sub-folders need to be copied.

= Documentation =
The HTML documentation is included in the distribution package and can be accessed via the sidebar menu that the demo plugin creates.

* [Online Documentation](http://admin-page-framework.michaeluno.jp/en/v3/class-AdminPageFramework.html)

= Tutorials =
[Index](http://en.michaeluno.jp/admin-page-framework/tutorials-v3/)

1. [Create an Admin Page](http://en.michaeluno.jp/admin-page-framework/tutorials-v3/01-create-an-admin-page/)
2. [Create a Form](http://en.michaeluno.jp/admin-page-framework/tutorials-v3/02-create-a-form/)
3. [Create a Page Group](http://en.michaeluno.jp/admin-page-framework/tutorials-v3/03-create-a-page-group/)
4. [Create In-page Tabs](http://en.michaeluno.jp/admin-page-framework/tutorials-v3/04-create-inpage-tabs/)
5. [Organize a Form with Sections](http://en.michaeluno.jp/admin-page-framework/tutorials-v3/05-organize-a-form-with-sections/)
6. [Use Section Tabs and Repeatable Sections](http://en.michaeluno.jp/admin-page-framework/tutorials-v3/06-use-section-tabs-and-repeatable-sections/)
7. [Validate Submitted Form Data of a Single Field](en.michaeluno.jp/admin-page-framework/tutorials-v3/07-validate-submitted-form-data-of-a-single-field/)
8. [Validate Submitted Form Data of Multiple Fields](http://en.michaeluno.jp/admin-page-framework/tutorials-v3/08-validate-submitted-form-data-of-multiple-fields/)

== Screenshots ==
1. **Text Fields**
2. **Selector and Checkboxes**
3. **Image, Media, and File Upload**
4. **Form Input Verification**
5. **Import and Export**
6. **Taxonomy and Post Type Checklists**
7. **Color Pickers and Buttons**
8. **Custom Post Type and Meta Box**
9. **Contextual Help Pane**
10. **Taxonomy Field**
11. **Meta Boxes in Pages Added by Framework**
12. **Repeatable Sections, Section Tabs and Section Title Field**

== Installation ==

= Getting Started =

<h5><strong>Step 1</strong> - Include <em><strong>admin-page-framework.min.php</strong></em></h5>
You need to include the library file in your PHP script. The file is located in the `library` folder of the uncompressed plugin file.

`if ( ! class_exists( 'AdminPageFramework' ) )
    include_once( dirname( __FILE__ ) . '/library/admin-page-framework.min.php' );`
	
<h5><strong>Step 2</strong> - Extend the Library Class</h5>

`class APF_GettingStarted extends AdminPageFramework {
}`

<h5><strong>Step 3</strong> - Define the <em>setUp()</em> Method</h5>

`function setUp() {
	$this->setRootMenuPage( 'Settings' );	// specifies to which parent menu to belong.
	$this->addSubMenuItem(
		array(
			'title' => 'My First Page',
			'page_slug' => 'myfirstpage'
		)
	); 
}`

<h5><strong>Step 4</strong> - Define the Methods for Hooks</h5>

`function do_myfirstpage() {  // do_{page slug}	
	?>
	<h3>Say Something</h3>
	<p>This is my first admin page!</p>
	<?php
}`

<h5><strong>Step 5</strong> - Instantiate the Class</h5>

`new APF;`

<h5><strong>Example Code</strong> - Getting Started</h5>

`<?php
/* Plugin Name: Admin Page Framework - Getting Started */ 

if ( ! class_exists( 'AdminPageFramework' ) )
    include_once( dirname( __FILE__ ) . '/library/admin-page-framework.min.php' );
    
class APF extends AdminPageFramework {

    function setUp() {
		
    	$this->setRootMenuPage( 'Settings' );	// where to belong
		$this->addSubMenuItem(
			array(
				'title' => 'My First Page',
				'page_slug' => 'myfirstpage'
			)
		);
			
    }

    function do_myfirstpage() {  // do_{page slug}
        ?>
        <h3>Say Something</h3>
        <p>This is my first admin page!</p>
        <?php   
    }
    
}
new APF;`

<strong>Create a Form</strong>

`<?php
/* Plugin Name: Admin Page Framework - My First Form */ 

if ( ! class_exists( 'AdminPageFramework' ) )
    include_once( dirname( __FILE__ ) . '/library/admin-page-framework.min.php' );
    
class APF_MyFirstFrom extends AdminPageFramework {

    function setUp() {
		
    	$this->setRootMenuPage( 'My Settings' );	// create a root page 
		$this->addSubMenuItem(
			array(
				'title' => 'My First Form',
				'page_slug' => 'my_first_form'
			)
		);
					
		$this->addSettingFields(
			array(	
				'field_id'	=>	'text',
				'section_id'	=>	'my_first_text_section',
				'title'	=>	'Text',
				'type'	=>	'text',
				'default'	=>	123456,
			),
			array(	
				'field_id'	=>	'submit',
				'type'	=>	'submit',
			)
		);
		
    }
    
}
new APF_MyFirstFrom;`

== Frequently asked questions ==
= What is this for? =
This is	a PHP class library that enables to create option pages and form fields in the administration panel. Also it helps manage to save, export, and import options.

= I've written a useful class, functions, and even custom field types that will be useful for others! Do you want to include it? = 
The [GitHub repository](https://github.com/michaeluno/admin-page-framework "Admin Page Framework") is avaiable. Raise an [issue](https://github.com/michaeluno/admin-page-framework/issues) first and we'll see if changes can be made. 

= How can I contribute to improving the documentation? =
You are welcome to submit documentation. Please follow the [Documentation Guideline](https://github.com/michaeluno/admin-page-framework/blob/master/documentation_guideline.md). 

In addition, your tutorials and snippets for the framework can be listed in the manual. Let us know it [here](https://github.com/michaeluno/admin-page-framework/issues?direction=desc&labels=Documentation&page=1&sort=created&state=open).

= Does my commercial product incorporating your framework library have to be released under GPL? =
No. The demo plugin is released under GPLv2 or later but the library itself is released under MIT. 

= Can I set a custom post type as a root page? =
Yes. For built-in root menu items or create your own ones, you need to use the `setRootMenuPage()` method. For root pages of custom post types, use `setRootMenuPageBySlug()`.

= How do I retrieve the stored options? =
The framework stores them as an organized multidimensional array in the options table in a single row. So use the `get_option()` function and pass the instantiated class name as the key or the custom key if you specify one in the constructor. 

For instance, if your instantiated class name is `APF` then the code would be `get_option( 'APF' );` Alternatively, use the [AdminPageFramework::getOption()](http://admin-page-framework.michaeluno.jp/en/v3/class-AdminPageFramework.html#_getOption) static method.

= Is it possible to use a custom database table to store submitted form data instead of using the options table? =
Yes. There are two main means to achive that. 

One is to set the `value` argument in the field definition array to suppress the displaying value in the field.
See an example. https://gist.github.com/michaeluno/fb4088b922b71710c7fb

The other is to override the options array set to the entire form using the `options_{instantiated class name}`.
See an example. https://gist.github.com/michaeluno/fcfac27825aa8a35b90f

Also passing an empty string, `''` to the first parameter of the constructor will disable the ability to store submitted form data into the options table.

e.g.
`new MyAdminPage( '' );`

= How can I add sub-menu pages to the root page created by the framework in a separate script? =

Say, in your main plugin, your class `MyAdminPageClassA` created a root page. In your extension plugin, you want to add sub-menu pages from another instance `MyAdminPageClassB`. 

In the `setUp()` method of `MyAdminPageClasB`, pass the instantiated class name of the main plugin that created the root menu, `MyAdminPageClassA`, to the `setRootMenuPageBySlug()` method.

e.g.
`$this->setRootMenuPageBySlug( 'MyAdminPageClassA' );`

= Does the framework work with WordPress Multi-site? =
Yes, it works with [WordPress MU](https://codex.wordpress.org/WordPress_MU).

== Other Notes ==

= Tips =
<h5><strong>Use Unique Page Slug</strong></h5>
The framework internally uses the `add_submenu_page()` function to register sub menu pages. When the same page slug is registered for multiple root pages, only the last registered callback gets triggered. The other ones will be ignored.

This means if you choose a very simple page slug such as <code>about</code> for your plugin/theme's information page and then if there is another plugin using same page slug, your users will get either of your page or the other.

So just use a unique page slug. One way to do that is to add a prefix like <code>apf_about</code>. 

<h5><strong>Change Class Names</strong></h5>
When you include the library, change the class names that the library uses. This is because if there is a plugin that uses a lesser version of the library and it is loaded earlier than yours, your script may not work properly.

All the class names have the prefix <code>AdminPageFramework</code> so just change it to something like <code>MyPlugin_AdminPageFramework</code>. 

Most text editors supports the *Replace All* command so just use that. By the time WordPress's minimum required PHP version becomes 5.3 or higher, we can use namespaces then this problem will be solved.

<h5><strong>Change Framework's System Messages</strong></h5>
The default messages defined by the framework can be changed. For example when you import a setting with the framework, the setting notice will be displayed. 

If you want to change it to something else, modify the `oMsg` object. It has the `aMessages` public property array which holds all the messages that the library uses.

<h5><strong>Change Preview Image Size of the `image` Field Type</strong></h5>
To specify a custom size to the preview element of the `image` field type, set an attribute array like the below, where 300px is the max width.

`array(
	'field_id'	=>	'my_image_field_id',
	'title'	=>	__( 'Image', 'admin-page-framework-demo' ),
	'type'	=>	'image',
	'attributes'	=>	array(
		'style'	=>	'max-width:300px;',
	),
),`

<h5><strong>Set default field value</strong></h5>
To set the initial value of a field, use the `default` argument in the field definition array.

`array(
	'field_id'	=>	'my_text_field_id',
	'title'	=>	__( 'My Text Input Field', 'admin-page-framework-demo' ),
	'type'	=>	'text',
	'default'	=>	'This text will be displayed for the first time that the field is displayed and will be overridden when a user set an own value.',
),`

<h5><strong>Always display a particular value in a field</strong></h5>
The `value` argument in the definition array can suppress the saved value. This is useful when you want to set a value from a different data source or create a wizard form that stores the data in a custom location.

`array(
	'field_id'	=>	'my_text_field_id',
	'title'	=>	__( 'My Text Inpu Field', 'admin-page-framework-demo' ),
	'type'	=>	'text',
	'value'	=>	'This will be always set.',
),`

If it is a repeatable field, set the value in the sub-fields.

`array(
	'field_id'	=>	'my_text_field_id',
	'title'	=>	__( 'My Text Input Field', 'admin-page-framework-demo' ),
	'type'	=>	'text',
	'repeatable'	=>	true,
	'value'	=>	'the first value',
	array(
		'value'	=>	'the second value',
	),
	array(
		'value'	=>	'the third value',
	),	
),`

Alternately, if it is in a framework's generic pages (not post meta box fields) you may use the `options_{instantiated class name}` filter to suppress the options so that setting the value argument is not necessary.
See examples, https://gist.github.com/michaeluno/c30713fcfe0d9d45d89f, https://gist.github.com/michaeluno/fcfac27825aa8a35b90f, 

= Roadmap =
Check out [the issues](https://github.com/michaeluno/admin-page-framework/issues?labels=enhancement&page=1&state=open) on GitHub labeled *enhancement*.

== Changelog ==

= 3.1.0 - 2014/07/18 =
- Added the `options_{instantiated class name}` filter to suppress the data used to display the form values.
- Added the `AdminPageFramework_Debug::log()` method.
- Added the ability not to set the default link to the custom post type post listing table's page in the plugin listing table page by passing an empty string to the 'plugin_listing_table_title_cell_link` key of the 'label' argument option.
- Added the `date_range`, `date_time_range`, `time_range` custom field type.
- Added the ability to set options for the `date`, `date_time`, and `time` custom field types.
- Added the `hasSettingNotice()` method that checks if at least one setting notice has been set or not.
- Added the `admin-page-framework-subfield` class selector to the div element's class attribute of field containers of sub-fields. 
- Added the `field_definition_{instantiated class name}` filter hook that applies to all the defined field arrays.
- Added the `disableSavingOptions()` method that disables the functionality to save submitted form data into the options table.
- Added the `setPluginSettingsLinkLabel()` method which enables to set the text label to the automatically embedded link to the plugin listing table of the plugin title cell in addition to disabling the functionality.
- Added the `start()` method which is automatically called at the end of the constructor, which can be used when the instantiated class name cannot be determined. 
- Added the ability to disable settings notices by passing false to the `$_GET{'settings-notice']` key.
- Added the `AdminPageFramework_NetworkAdmin` abstract class that enables to add pages in the network admin area.
- Tweaked the styling of the `number` input type to align the text on the right.
- Tweaked the styling of the `checkbox` field type not to wrap the label after the checkbox.
- Tweaked the styling of field td element when the `show_title_column` option is set to false to disable the title.
- Changed the `removed_repeatable_field` callback to be triggered after the element is removed.
- Changed the target form action url not to contain the `settings-updated` key.
- Changed the demo plugin to be separated into smaller components.
- Changed the `validation_{...}` callback methods to receive a third parameter of the class object so that third party scripts can access object members inside from the validation method.
- Changed the `AdminPageFramework` class to accept an empty string value to be passed to the first parameter of the constructor, to be used to disable saving options.
- Changed the scope of `oUtil`, `oDebug`, and `oMsg` objects to public from protected to be accessed from an instantiated object.
- Changed the `section_head` filter hook to be triggered even when the section description is not set.
- Changed not to redirect to options.php when a form created by the framework is submitted in the pages created by the framework.
- Fixed a bug that a value of `0` did not get displayed but and empty string instead.
- Fixed a bug that sub-fields could not properly have the default key-values of the field definition of the type.
- Fixed a bug that in PHP v5.2.x, setting a section error message caused a string "A" to be inserted in each belonging field.
- Fixed a bug that previously set field error arrays were lost if the `setFieldErrors()` method is performed multiple times in a page load.
- Fixed a bug that page load info was not inserted when multiple admin page objects were instantiated.
- Fixed a bug that duplicated setting notices were displayed.
- Fixed a bug that the redirect transient remained when a field error is set and caused unexpected redirects when the 'href' argument is set for the submit field type.
- Fixed an issue that `textarea` input field was placed in the wrong position when the browser turned off JavaScript.
- Fixed a bug that the `autocomplete` custom field type's JavaScript script could not run when the prePopulate option is set and the value is saved without changing.
- Fixed an issue in the class autoloader that caused a PHP fatal error in some non GNU OSes such as Solaris in the development version.

= 3.0.6 - 05/10/2014 =
- Fixed a JavaScript syntax error in the `font` custom field type.
- Fixed a bug in the `image` and `media` field types and the `font` custom field type that escaping the frame did not cancel setting the selection.
- Fixed an issue that the section tab script was applying the styling to all the `ul` elements inside the section.
- Tweaked the styling of the repeatable section buttons.
- Tweaked the `autocomplete` custom field type to find more posts by loosening the search criteria. 
- Fixed a bug in the `autocomplete` custom field type that setting the `prePopulate` option caused a JavaScript error after submitting the form.
- Fixed an issue that submitted form input data array in validation callback methods lost array keys of fields with individual set capabilities when the form-submitting user has lower capability than the stored field capability.
- Added the ability to set a link and its label in the title cell of the plugin listing table for a custom post type created by the framework.
- Fixed an issue that the `dial` and `autocomplete` custom field type fields could not be repeated properly in repeatable sections.

= 3.0.5 - 04/29/2014 =
- Fixed a bug that the `redirect_url` option of the `submit` field type did not take effect.
- Fixed a bug that repeatable sections messages did not indicate the correct maximum and minimum numbers.
- Tweaked the `autocomplete` custom field type to have some delays to perform post title queries in the background.
- Changed the `validation_{instantiated class name}_{section id}_{field id}` and `validation_{instantiated class name}_{field id}` hooks to be triggered only when the section or field belongs to the page that the form is submitted.
- Fixed a bug that some public methods caused a PHP fatal error "Call to a member function" after submitting a form in multi-sites when a plugin is network-activated.
- Changed the post type class methods, `enquueueStyles()`, `enquueueStyle()`, `enquueueScripts()`, `enquueueScript()` to silently fail when they are called not in the post type page.

= 3.0.4 - 04/19/2014 =
- Improved the accuracy on search results of the `autocomplete` custom field type.
- Fixed a bug that the help pane of meta box fields did not appear in the page after submitting the form.
- Added the ability to set a validation error message to appear at the top of a form section output.
- Fixed a bug that saved field values of page meta boxes got lost when fields are saved in a different tab but in the same page.
- Fixed a bug that the `script_common_{...}` filter was not functioning in meta box classes.
- Added the ability to throw a warning when undefined method is called.
- Changed the file structure of the `development` directory.

= 3.0.3 - 03/24/2014 =
- Added the ability to reveal more than one elements to the `revealer` custom field type with a small braking change. 
- Tweaked certain routines not to be triggered in irrelevant pages.
- Tweaked the field type registration process to be faster.
- Fixed an undefined index warning in the `AdminPageFramework_Property_MetaBox_Page` class.
- Fixed a bug in the development version that the fatal error occurred when trying to include a taxonomy field class individually.
- Changed the default log location and file name.

= 3.0.2 - 03/22/2014 =
- Fixed a bug that repeatable sections could not be removed when they are placed in generic pages but without in-page tabs.
- Fixed an issue of magic quotes with meta box fields for the framework pages.
- Added examples of implementing a custom sort algorithm for columns of the taxonomy term listing table and the custom post type post listing table in the demo plugin.
- Added the the `cell_{instantiated class name}_{column slug}` filter for the taxonomy field class.
- Added the `field_definition_{...}` filter.

= 3.0.1.4 - 03/09/2014 =
- Fixed a bug that `setCapability()` did not take effect for form elements.
- Fixed an issue that the target tab slug and the target section tab slug do not reset after the `setSettingFields()` method returns.
- Tweaked the layout of the geometry custom field type.

= 3.0.1.3 - 03/07/2014 =
- Fixed a bug that custom columns could not be updated properly in the taxonomy definition page (edit-tags.php).
- Added `class_exists()` checks for sample custom field type classes for the demo plugin.

= 3.0.1.2 - 03/04/2014 = 
- Fixed a bug that repeatable field buttons did not add/remove when a section is repeated with a new ID due to non-assigned options.
- Fixed a bug that sortable fields of a repeated section could not be sorted.
- Fixed a bug that `image` and `media` field type fields could not be repeated properly in repeatable sections.

= 3.0.1.1 - 03/01/2014 =
- Fixed a bug that `taxonomy` field type fields could not be properly repeated.
- Tweaked the styling of the `taxonomy` field type fields.
- Tweaked the styling of horizontal alignment of `th` and `td` form elements.
 
= 3.0.1 - 02/26/2014 =
- Added the `AdminPageFramework::getOption()` method that can be used from the front-end to retrieve saved option values. 
- Fixed a bug that the plus(+) field repeater button got inserted when a section is repeated in WordPress 3.5.x or below.
- Tweaked the styling of section tabs to prevent small dots from appearing when activating a tab. 
 
= 3.0.0.1 - 02/24/2014 =
- Tweaked the styling of section tabs with `section_title` type fields.
 
= 3.0.0 - 02/24/2014 =
- Added: the `section_title` field type that lets the user to enter a section title.
- Added: the ability to display form sections in tabs by specifying the `section_tab_slug`.
- Added: the `autocomplete` custom field type.
- Added: the ability to repeat form section.
- Added: the ability to set form sections in meta boxes.
- Added: the ability to omit the `addSettingSections()` method not to set a section. In other words, setting a section became optional.
- Added: the `fields_{instantiated class name}_{section id}` filter that receives registered field definition arrays which belong to the given section.
- Added: the `grid` custom field type.
- Added: the documentation pages in the distribution package.
- Added: an example to add a thumbnail to the taxonomy term listing table in the demo plugin.
- Added: the `cell_{taxonomy slug}` and the `cell_{instantiated class name}` filters for the taxonomy field class.
- Added: the `sortable_columns_{taxonomy slug}` and the `sortable_columns_{instantiated class name}` filters for the taxonomy field class.
- Added: the `columns_{taxonomy slug}` and the `columns_{instantiated class name}` filters for the taxonomy field class.
- Added: the `columns_{post type slug}` filter for the post type class.
- Added: the `sortable_columns_{post type slug}` filter for the post type class.
- Deprecated: ( ***Breaking Change*** ) the `setColumnHeader()` method in the post type class.
- Deprecated: ( ***Breaking Change*** ) the `setSortableColumns()` method in the post type class.
- Deprecated: ( ***Breaking Change*** ) the `addSubMenuLink()` method in the main class.
- Deprecated: ( ***Breaking Change*** ) the `addSubMenuPages()` and the addSubMenuPage() method in the main class.
- Added: the minified version of the library.
- Added: the ability to add fields in the taxonomy definition page.
- Added: the ability to add meta boxes in pages added by the framework.
- Added: the ability to set the target section ID in the `addSettingFields()` method so that the `section_id` key can be omitted for the next passing field arrays.
- Added: the ability to set the target page and tab slugs in the `addSettingSection()` and the `addSettingSections()` methods so that the page and tab slug keys can be omitted for the next passing section arrays.
- Added: the ability to set the target page slug in the `addInPageTabs()` method so that the page slug key can be omitted for the next passing tab arrays.
- Added: the ability to set options for repeatable fields including the maximum number of fields and the minimum number of fields.
- Changed: the all the registered field default values to be saved regardless of the page where the user submits the form.
- Changed: it to store all added sections and fields into the property object regardless of they belong to currently loading page and with some other conditions.
- Added: the ability to sort fields by drag and drop.
- Fixed: a bug that meta box specific styles were not loaded per class basis when multiple meta box class instances were created and they were displayed in the same page; only the first instantiated meta box class's styles were loaded.
- Added: the filters, `style_common_{extended meta box class name}`, `style_ie_common_{extended meta box class name}`, `style_ie_{extended meta box class name}`.
- Added: the ability to set option group in the `select` field type.
- Added: the ability to set tag attributes on field tags on an individual basis in the `select`, `radio`, and `checkbox` field types.
- Added: the ability to set tag attributes with the `attributes` key by passing an array with the key of the attribute name and the value of the property value for input fields.
- Added: the ability to mix field types in sub-fields.
- Added: the `hidden` key to the field definition array of pages that hides the field output.
- Added: the `show_title_column` key to the field definition array of pages.
- Added: the `after_fields` and the `before_fields` keys to the field definition array.
- Changed: ( ***Breaking Change*** ) the structure of field definition arrays.
- Changed: ( ***Breaking Change*** ) dropped the page slug dimensions from the saved option array structure.
- Fixed: a bug that page load info in the footer area was not embedded when multiple root pages are created.
- Moved: the method to retrieve library data into the property base class and they will be stored as static properties.
- Changed: ( ***Breaking Change*** ) the name of the `showInPageTabs()` method to `setInPageTabsVisibility()`.
- Changed: ( ***Breaking Change*** ) the name of the `showPageHeadingTabs()` method to `setPageHeadingTabsVisibility()`.
- Changed: ( ***Breaking Change*** ) the name of the `showPageTitle()` method to `setPageTitleVisibility()`.
- Changed: ( ***Breaking Change*** ) the `foot_{...}` filters to be renamed to `content_bottom_{...}`.
- Changed: ( ***Breaking Change*** ) the `head_{...}` filters to be renamed to `content_top_{...}`.
- Changed: ( ***Breaking Change*** ) the `{instantiated class name}_{page slug}_tabs` filter to be renamed to `tabs_{instantiated class name}_{page slug}`.
- Changed: ( ***Breaking Change*** ) the `{instantiated class name}_pages` filter to be renamed to `pages_{instantiated class name}`.
- Changed: ( ***Breaking Change*** ) the `{instantiated class name}_setting_fields` filter to be renamed to `fields_{instantiated class name}`.
- Changed: ( ***Breaking Change*** ) the `{instantiated class name}_setting_sections` filter to be renamed to `sections_{instantiated class name}`.
- Changed: ( ***Breaking Change*** ) the `{instantiated class name}_field_{field id}` filter to be renamed to `field_{instantiated class name}_{field id}`.
- Changed: ( ***Breaking Change*** ) the `{instantiated class name}_section_{section id}` filter to be renamed to `section_head_{instantiated class name}_{section id}`.
- Changed: the scope of all the methods intended to be used by the user to `public` from `protected`.
- Changed: all the callback methods to have the prefix of `replyTo`.
- Changed: all the internal methods to have the prefix of an underscore.
- Changed: all the variable names used in the code to apply the Alternative PHP Hungarian Notation.
- Changed: ( ***Breaking Change*** ) the name of the property `oProps` to `oProp`.
- Changed: ( ***Breaking Change*** ) the name of the class `AdminPageFramework_CustomFieldType` to `AdminPageFramework_FieldType`.
- Changed: some of the class names used internally.
- Changed: ( ***Breaking Change*** ) apart from the conversion to the lower case, renamed some of the keys of the field definition array and the section field definition array.
- Changed: ( ***Breaking Change*** ) all the names of array keys with which the user may interact to consist of lower case characters and underscores.

= 2.1.7.2 - 01/18/2014 =
- Fixed: a bug that the `for` attribute of the `label` tag was not updated in repeatable fields.
- Fixed: the warning: `Strict standards: Declaration of ... should be compatible with ...`.

= 2.1.7.1 - 12/25/2013 =
- Added: an example of basic usage of creating a page group as well as specifying a dashicon.
- Added: the ability for the `setRootMenuPage()` method to accept `dashicons`, the `none` value, and SVG base64 encoded icon for the second parameter.
- Fixed: a bug that the `color` field type was replaced with the `taxonomy` field type and the `taxonomy` field type was not available.

= 2.1.7 - 12/23/2013 =
- Fixed a bug that the screen icon could not be retrieved when the `strScreenIcon` key was not set (started to occur around v2.1.6).
- Added: the `import_mime_types_{...}` filter that receives the array holding allowed MIME types so that the user can add custom MIME types for the imported files.
- Added: the `enqueueScript()` and the `enqueueStyle()` methods for the post type class.
- Added: the ability to automatically insert page load information in the admin footer if the `WPDEBUG` constant is true.
- Fixed: a bug that the `password` field type could not be defined as of v2.1.6.

= 2.1.6 - 12/14/2013 = 
- Fixed: a bug that the focus of a drop-down list of the `size` field type got stolen when the user tries to select a unit.
- Added: another example to define custom field types in the demo plugin.
- Changed: the built-in field types to be declared before loading any custom field types.
- Added: a sample custom field type, `font`, in the demo plugin.
- Fixed: the `logArray()` method to use the site local time.
- Added: a sample page to view the message object's properties in the demo plugin.
- Fixed: all the individual messages to be in the message object so that it gives easier access to for the user to modify the framework's default messages.
- Added: a sample custom field type, `dial`, in the demo plugin.
- Added: sample custom field types, `date`, `time`, and `date_time` in the demo plugin.
- Added: additional input fields to the custom `geometry` field type to retrieve the location name and the elevation.
- Removed: ( ***Breaking Change*** ) the `date` field type.
- Added: the ability to set an icon with a file path for the `setRootMenuPage()`, `addSubMenuPage()`, and `getStylesForPostTypeScreenIcon()` methods.

= 2.1.5 - 12/08/2013 =
- Changed: ( *Minor Breaking Change* ) the format of the `id` and `for` attributes of the input and label tags of the `taxonomy` field type.
- Fixed: a bug that caused name collisions with the `for` attribute of label tags in the `taxonomy` field type.
- Added: the `field_{instantiated class name}_{field id}` and `section_{instantiated class name}_{section id}` filters. 
- Added: the `export_{instantiated class name}_{field id}`, `export_{instantiated class name}_{input id}` filters.
- Added: the `import_{instantiated class name}_{field id}`, `import_{instantiated class name}_{input id}` filters.
- Added: an example to retrieve the saved options from the front end in the demo plugin.
- Added: the ability for the `enqueueScript()` and `enqueueStyle()` methods to accept absolute file paths.
- Introduced: a new class `AdminPageFramework_CustomFieldType`.
- Added: a sample custom field type, `geometry`, in the demo plugin.
- Fixed: a bug that the `enqueueScripts()` method caused infinite loops.
- Added: the `field_types_{instantiated class name}` filter that receives the field type defining array so that the user can return custom field types by adding a definition array to it.
- Added: the `vClassAttributeUpload` key for the `import` field type that defines the class attribute of the custom file input tag in the field output.
- Added: the `vUnitSize` key for the `size` field type that indicates the `size` attribute of the select(unit) input field.</li>
- Added: the `vMerge` key for the `import` field type that determines whether the imported data should be merged with the existing options.
- Changed: admin settings notifications with `setSettingNotice()` not to have multiple messages with the same id.
- Added: the `validation_{instantiated class name}_{field id}` and the `validation_{instantiated class name}_{input id}` filters. 
- Fixed: a bug in the demo plugin that the `size` fields were not displayed properly.
- Fixed: a bug that menu positions could not be set with the `setRootMenuPage()` method.

= 2.1.4 - 11/24/2013 =
- Changed: the output of each field to have enclosing `fieldset` tag to be compatible with WordPress v3.8.
- Changed: ( *Minor Breaking Change* ) the default value of all the `vDelimiter` key to be an empty string as some input types' default values were `<br />`.
- Changed: ( *Minor Breaking Change* ) the structure of input field elements to enclose input elements in the `label` tag to make it compatible with the WordPress v3.8 admin style. Accordingly, those who are using the `vBeforeInputTag` and the `vAfterinputTag` keys should make sure that block elements are not passed to those outputs.
- Fixed: a bug that enqueuing multiple scripts/styles with the `enqueueStyle()`/`enqueueScript()` method did not take effect.
- Changed: some menu item labels in the demo plugin.
- Added: sample pages that demonstrate the use of hidden pages with the `fShowInMenu` key in the demo plugin.
- Added: the `fShowInMenu` key for the sub-menu page array which will add the ability to hide the page from the sidebar menu.

= 2.1.3 - 11/19/2013 = 
- Fixed: a bug that the style of the Iris color picker had a conflict with the date picker. 
- Added: the `screen_icon` key for the post type argument array that can set the screen icon of the post type pages.
- Added: the `fAllowExternalSource` key for the `image` and `media` field types that enables to set external URLs via the media uploader. 
- Added: the `media` field type.
- Added: the `arrCaptureAttributes` key to save additional attributes of the image selected via the media uploader. 
- Tweaked: the image fields' preview images to have the maximum width of 600px.
- Added: the ability to select multiple image files for repeatable fields.
- Added: the WordPress 3.5 uploader for the image field type.
- Fixed: a bug that an image URL could not be inserted from the `From URL` tab of the image uploader.
- Added: the `fRepeatable` key to the `text`,`textarea`, `image`, `date`, `color`, and `file` field types that make the fields to be repeatable.

= 2.1.2 - 11/3/2013 =
- Added: the 'vRich' key to the `textarea` field type that enables rich text editor.
- Added: the `vReset` key to the `submit` field type that performs resetting options. 
- Added: class electors to the field elements.
- Changed: ( *Minor Breacking Change* ) the `field_description` class selector name to `admin-page-framework-fields-description`.
- Changed: the *assets* folder name to *asset*.
- Added: the `setDisallowedQueryKeys()` method that can define disallowed query keys to be embedded in the links of in-page tabs and page-heading tabs.
- Fixed: a bug that the `settings-updated` query key string was embedded in the links of in-page tabs and page-heading tabs. 
- Changed: the `showPageTitle()`, `showPageHeadingTabs()`, `showInPageTabs()`, `setInPageTabTag()`, and `setPageHeadingTabTag()` methods to be able to use after registering pages. That means it can be used in methods that are triggered after registering pages such as the `do_before_{page slug}` hook.
- Changed: ( *Breacking Change* ) the key name of page property array `fPageHeadingTab` to `fShowPageHeadingTab`.
- Added: the `setAdminNotice()` method which enables the user to add custom admin messages. 
- Changed: the link class for custom post types to use a public property for the link title that appears in the plugin listing table so that the user can change the text.
- Fixed: a bug that the link url automatically inserted in the plugin listing table was not correct when setting a custom root page slug.
- Fixed: a bug that undefined index `typenow` warning occurred when a custom database query with the WP_Query class was performed in the edit.php admin page. 
- Added: the `admin-page-framework-radio-label` and the `admin-page-framework-checkbox-label` class selectors for the elements enclosing radio and checkbox input labels and removed `display:inline-block` from the inline CSS rule of the elements.
- Fixed: an undefined index warning to occur that appears when a non-existent parent tab slug is given to the `strParentTabSlug` in-page tab array element.
- Added: the `getFieldValue()` method which retrieves the stored value in the option properties by specifying the field name. This is helpful when the section name is unknown.
- Added: the `dumpArray()` method for the debug class.
- Added: the `fHideTitleColumn` field key for the meta box class's field array structure. This allows the user to disable the title column in the options table.
- Added: the `addSettingSection()` method that only accepts one section array so that the user can use it in loops to pass multiple items. 
- Added: the `addSettingField()` method that only accepts one field array so that the user can use it in loops to pass multiple items. 
- Added: the `enqueueStyle()` method and the `enqueueScript()` method that enqueue script/style by page/tab slug.
- Changed: the submit field type with the `vRedirect` value not to be redirected when a field error array is set.
- Fixed: a bug that hidden in-page tabs with the `fHide` value could not have associated callbacks such as `validation_{page slug}_{tab slug}`.
- Changed: the `getParentTabSlug()` method to return an empty string if the parent slug has the fHide to be true.
- Fixed: a bug that the redirect submit button did not work with a long page slug.
- Added: the Other Notes section including tips in the demo plugin.
- Added: the `setPageHeadingTabTag()` method that sets the page-heading tab's tag.
- Added: the ability to set visibility of in-page tabs, page-heading tabs, and page title by page slug.

= 2.1.1 - 10/08/2013 =
- Added: the *for* attribute of the *label* tag for checklist input elements so that clicking on the label checks/unchecks the item.
- Added: the *strWidth* and the *strHeight* field array keys for the *taxonomy* field type.
- Deprecated: the *numMaxWidth* and the *numMaxHeight* field array keys for the *taxonomy* field type.
- Changed: the *taxonomy* field type to display the elements in a tabbed box.
- Changed: the post type check list to display post types' labels instead of their slugs.
- Changed: the *vDelimiter* elements to be inserted after the *vAfterInputTag* elements. 
- Changed: the footer text links to have title attributes with script descriptions.
- Removed: the script version number in the footer text link and moved it to be displayed in the title attribute.
- Added: the *getCurrentAdminURL()* method.

= 2.1.0 - 10/05/2013 =
- Added: the *load_{instantiated class name}*, *load_{page slug}* and *load_{page slug}_{tab slug}* filters.
- Fixed: a bug that saving options with a custom capability caused the Cheatin' Uh message.
- Deprecated: ( ***Breaking Change*** ) the *setPageCapability()* method since it did not do anything.
- Changed: ( ***Breaking Change*** ) the *AdminPageFramework_PostType* class properties and *AdminPageFramework_MetaBox* to be encapsulated into a class object each.
- Added: the *strHelp* field key that adds a contextual help tab on the upper part of the admin page.
- Fixed: the required WordPress version to 3.3 as some of the functionalities were relying on the screen object that has been implemented since WordPress 3.3.

= 2.0.2 - 09/07/2013 =
- Fixed: a bug in the demo plugin that custom taxonomies were not added.
- Added: the *size* field type.

= 2.0.1 - 09/04/2013 =
- Fixed: a bug that admin setting notices were displayed twice in the options-general.php page.

= 2.0.0 - 08/28/2013 =
- Released 2.0.0.

= 2.0.0.b4 - 08/28/2013 =
- Fixed: a bug that custom post type preview page did not show the stored values in the demo plugin.
- Refactored: the code that loads the color picker script.
- Refactored: the code that loads the image selector script.
- Refactored: the code that loads framework's style.

= 2.0.0.b3 - 08/28/2013 =
- Added: more documentation in the source code.
- Removed: the *document* folder.
- Moved: the *documentation_guideline.md* file to the top level folder.
- Removed: the documentation pages and added an external link to the documentation web site.
- Removed: the *arrField* parameter of the constructor of the *AdminPageFramework_MetaBox* class.
- Removed: the *setFieldArray()* method of the *AdminPageFramework_MetaBox* class.
- Fixed: a bug that meta box color piker, image selector, data picker scripts did not load in the page after the Publish button was pressed.
- Changed: the *validation_ instantiated class name* filter for meta boxes to accept the second parameter to receive the stored data.

= 2.0.0.b2 - 08/26/2013 =
- Changed: *addLinkToPluginDescription()* and *addLinkToPluginTitle()* to accept variadic parameters. 
- Added: an example of using *addLinkToPluginDescription()* and *addLinkToPluginTitle()* in the demo plugin.
- Changed: the demo plugins file name.
- Fixed: an issue that date picker script caused an irregular element to be inserted around the page footer.
- Changed: the documentation compatible with the DocBlock syntax. 

= 2.0.0.b1 - 08/24/13 =
- Changed: the *setSettingsNotice()* method name to *setSettingNotice()* to be consistent with other names with *Settings*.
- Added: the *date* input field that adds a date picker.
- Added: the ability to specify the multiple attribute to the select field with the *vMultiple* key.
- Added: the *color* input field that adds a color picker.

= 1.1.0 - 2013/07/13 =
- Added: the *addSubMenuItems()* and *addSubMenuItem()* methods that enables to add not only sub menu pages but also external links.
- Added: the ability to list the terms of specified taxonomy with checkbox by taxonomy slug.
- Changed: ( *Breaking Change* ) the *category* field type to *taxonomy* field type.
- Fixed: a bug that adding sub pages to an existing custom post type page caused the links of in-page tabs to have the wrong urls.
- Changed: the *image* field type to be a custom text field.
- Added: the *import_format_{page slug}_{tab slug}*, *import_format_{page slug}*, *import_format_{instantiated class name}* filters to allow to modify the import format type.
- Added: the *import_option_key_{page slug}_{tab slug}*, *import_option_key_{page slug}*, *import_option_key_{instantiated class name}* filters to allow to modify the import option key.
- Added: the *export_format_{page slug}_{tab slug}*, *export_format_{page slug}*, *export_fomat_{instantiated class name}* filters to allow to modify the export format type.
- Added: the *export_name_{page slug}_{tab slug}*, *export_name_{page slug}*, *export_name_{instantiated class name}* filters to allow to modify the export file name.
- Added: the ability to set the *accept* attribute for the *file* input field.
- Added: ( *Breaking Change* ) the second parameter to the validation callback method to pass the old stored option data.
- Changed: ( *Breaking Change* ) the validation behaviour to maintain the stored option values to return the second parameter value in the validation callback method from returning an empty array.
- Changed: ( *Breaking Change* ) the validation behaviour to delete the stored option values to return an empty array in the validation callback method from returning a null value.
- Added: the *validation_{instantiated class name}* filter that allows to modify the submitted form data throughout the whole script.
- Added: the ability to set the text domain for the text messages that the framework uses.
- Added: the ability to set the minimum width for label tags for *textarea*, *text*, and *number* input fields.
- Added: the ability to set the label tag for *textarea*, *text*, and *number* input fields.
- Added: the *{instantiated class name}_field_{field id}* filter to allow to modify settings field output.
- Added: the *{instantiated class name}_{page slug}_tabs* filter to allow to modify adding in-page tabs.
- Added: the *{instantiated_class name}_pages* filter to allow to modify adding pages.
- Added: the *{instantiated class name}_setting_fields* and *{instantiated class name}_setting_sections* filters to allow to modify registering sections and fields.
- Changed: ( *Breaking Change* ) the default option key that is stored in the option database table to be the instantiated class name from the page slug.
- Changed: ( *Breaking Change* ) the section and field filters to have the prefix of the instantiated class name of the Admin Page Framework so that it prevents conflicts with other plugins that uses the framework.
- Changed: the anchor link *name* attribute to *id*.
- Added: the ability to order the in-page tabs with the *numOrder* key.
- Added: the *addInPageTab()* methods to set in-page tabs.
- Changed: ( *Breaking Change* ) the array structure of the parameter of the *addInPageTabs()* methods.
- Added: the ability to automatically assign the default screen icon if not set, which is of the **generic** id.
- Added: the ability to set the WordPress built-in screen icon to the custom added sub-menu pages.
- Added: a class for handling custom-post types.
- Added: a class for handling meta-boxes.
- Changed: ( *Breaking Change* ) to apply the camel-back notation to all the array argument keys.
- Changed: ( *Breaking Change* ) all the method names to be uncapitalised. 
- Changed: ( *Breaking Change* ) the sub-string of class names, Admin_Page_Framework, to AdminPageFramework.

= 1.0.4.2 - 07/01/2013 =
- Tweaked: the demo plugin to load the admin-page object only in the administration pages with the is_admin() function.
- Fixed: a bug that setting and retrieving a transient for the field error array caused extra database queries.
- Fixed: a bug that setting multiple checkboxes caused undefined index warning. 
- Fixed: a bug in the demo plugin that single upload field did not appear and caused undefined index warning after updating the options.

= 1.0.4.1 - 04/14/2013 =
- Added: the *if* key for section and field array that evaluates the passed expression to evaluate whether the section or field should be displayed or not.
- Added: the support of the *label* key for the *text* input field and multiple elements to be passed as array.
- Fixed: a bug that the disable field key for the check box type did not take effects when multiple elements were passed as array.

= 1.0.4 - 04/07/2013 =
- Fixed: an issue that the submit field type with the redirect key caused an unset index warning.
- Changed: not to use the get_plugin_data() function if it does not exist to support those who change the location of the wp-admin directory.
- Added: enclosed the checkbox, radio fields and its label in a tag with the *display:inline-block;* property so that each item do not wrap in the middle.
- Added: the *SetSettingsNotice()* method which can be used instead of the *AddSettingsError()* method. The new method does not require an ID to be passed.
- Changed: **(Breaking Change)** the parameters of *SetFieldErrors()* method; the first parameter is now the error array and the second parameter is the ID and it is optional.
- Changed: that when multiple labels were set for the field types that supports multiple labels but the *name* key was set null, it now returns the default value instead of an empty string.
- Tweaked: the settings registration process including sections and fields to be skipped if the loading page is not one of the pages added by the user.
- Improved: the accuracy to retrieve the caller script information.
- Added: the *posttype* field type.
- Added: the *category* field type.

= 1.0.3.3 - 04/02/2013 =
- Fixed: a bug that a debug log file was created after submitting form data introduced in 1.0.3.2.

= 1.0.3.2 - 04/02/2013 =
- Added: the *redirect* field key for the submit input type that redirects the page after the submitted form data is successfully saved.
- Fixed: an issue that when there are multiple submit input fields and the same label was used with the *href* key, the last url was set to previous buttons; the previous buttons urls were overwritten by the last one. 
- Fixed: a bug that a value for the *pre_field* was applied to the *post_field* key in some field types.
- Added: the ability to disable Settings API's admin notices to be automatically displayed after submitting a form by default. To enable the Settings API's notification messages, use the EnableSettingsAPIAdminNotice() method.

= 1.0.3.1 - 04/01/2013 =
- Added: the default message which appears when the settings are saved.
- Changed: to automatically insert plugin information into the plugin footer regardless of whether the second parameter of the constructor is set or not.

= 1.0.3 - 04/01/2013 =
- Added: the *href* field key for the submit field type that makes the button serve like a hyper link.
- Added: the SetFieldErrors() method that enables to set field errors easily without dealing with transients.
- Added: the *AddSettingsError()* and the *ShowSettingsErrors()* methods to be alternated with the settings_errors() and the add_settings_error() functions to prevent multiple duplicate messages to be displayed.
- Added: the ability to automatically insert anchor links to each section and field of form elements.
- Added: the *readonly* field key for text and textarea input fields that inserts the readonly attribute to the input tag.
- Added: the *pre_field* and *post_field* field keys that adds HTML code right before/after the input element.
- Fixed: a minor bug in the method that merges arrays that did not merge correctly with keys with a null value.

= 1.0.2.3 - 03/17/2013 =
- Added: the ability to set access rights ( capability ) to adding pages individually, which can be set in the newly added fourth parameter of the AddSubMenu() method.

= 1.0.2.2 - 03/17/2013 =
- Changed: (**Breaking Change**) the second parameter of the constructor from capability to script path; the capability can be set via the SetCapability() method.
- Added: the ability to automatically insert script information ( plugin/theme name, version, and author ) into the footer if the second parameter is set in the constructor.

= 1.0.2.1 - 03/16/2013 =
- Added: the capability key for section and field arrays which sets access rights to the form elements.
- Added: a hidden tab page which belongs to the first page with a link back-and-forth in the demo plugin. 
- Changed: the required WordPress version to 3.2 as the newly used filter option_page_capability_{$pageslug} requires it.
- Fixed: an issue that setting a custom capability caused the "Creatin' huh?" message and the user could not change the options.
- Added: the *HideInPageTab()* method which hides a specified in-page tab yet still accessible by the direct url.
- changed: the method name *RenderInPageTabs()* to *GetInPageTabs()* since it did not print anything but returned the output string. 

= 1.0.2 - 03/11/2013 =
- Added: the *export_{suffix}* and *import_{suffix}* filters and the corresponding callback methods to capture exporting/importing array to modify before processing it.
- Supported: multiple export buttons per page.
- Added: the *delimiter* key which delimits multiple fields passed as array including the field types of checkbox, radio, submit, export, import, and file.
- Fixed: to apply the value of the *disable* key to the *import* and *export* custom field.
- Fixed: a bug that an empty string was applied for the *description* key even when it is not set.
- Added: the transient key for the *export* custom field to set a custom exporting array.
- Added: *do_form* action hooks ( tag, page, global ) which are triggered before rendering the form elements after the form opening tag.
- Fixed: a bug that the *file_name* key for the *export* field key did not take effect.

= 1.0.1.2 - 03/09/2013 =
- Fixed: a typo which caused a page not to be added to the Appearance menu.

= 1.0.1.1 - 03/08/2013 =
- Fixed: typos in the demo plugin.
- Changed: error message for a field to display the field value as well in addition to the specified error message.
- Changed: the post_html key to be inserted after the description key.
- Changed: tip key to use the description key if it is not set.


= 1.0.1 - 03/05/2013 =
- Removed: array_replace_recursive() to support PHP below 5.3 and applied an alternative.
- Changed: to use md5() for the error transient name, class name + page slug, to prevent WordPress from failing to retrieve or save options for the character lengths exceeding 45 characters.
- Changed: to echo the value in a user-defined custom field type.
- Added: the *pre_html* and *post_html* keys for input fields that adds extra HTML code before/after the field input and the description.
- Added: the *value* key for input fields that precedes the option values saved in the database.
- Added: the *disable* key for input fields to add disabled="Disabled".

= 1.0.0.2 - 02/17/2013 =
- Fixed a warning in debug mode, undefined index, selectors.
- Added a brief instruction in the demo plugin code and fixed some inaccurate descriptions.

= 1.0.0.1 - 02/15/2013 =
- Fixed a bug that the options were not properly saved when the forms were created in multiple pages.

= 1.0.0.0 - 02/14/2013 = 
- Initial Release