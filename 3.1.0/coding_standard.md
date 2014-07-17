# Admin Page Framework Coding Standard #

## Variable Naming ##

Admin Page Framework employs [PHP Alternative Hungarian Notation](http://en.wikibooks.org/wiki/PHP_Programming/Alternative_Hungarian_Notation) for variable naming used in the source code to help better code readability.

### Scope Based Prefix ###

These should be prefixed before any other prefix character.

- `g+` - global variables.

	global $gasGlobalArray;
	$gasGlobalArray = $oObject->doMethod();

- `_` - private/protected class property variables. But if it is clear that they are accessed by the end-users (the user types the variable name to access it), the prefix should not be added.
	
	class MyClass {
	
		public $sPublicProperty = 'This is a public property string value';
		
		protected $_sProtectedProperty = 'This is a protected property string value';
		
		private	  $_sPrivateProperty = 'This is a private property string value';
			
	}

- '_' - local variables.

	function doMyfunc( $sPrameter ) {
		
		$_sLocalVariable = $sParameter;
	
	}

### Data Type Based Prefix ###

When the used types are mixed place them in alphabetical order.

- `a+` - array (often combined with the data type used inside the array)
- `b+` - boolean
- `c+` - character
- `d+` - date object -- as in what's returned from a date() or gmdate()
- `f+` - float -- a floating point number, e.g. an integer with a fractional part
- `h+` - handle, as in db handle, file handle, connection handle, curl handle, socket handle, etc.
- `hf` - handle to function, as in setRetrievalStrategy(callable $hfStrategy)
- `i+` - integer -- an integer
- `n+` - numeric (unknown if it's float, integer, etc. Use infrequently)
- `o+` - object
- `rs+` - db recordset (set of rows)
- `rw+` - db row
- `s+` - string
- `v+` - variant -- used very infrequently to mean any kind of possible variable type
- `x+` - to let other programmers know that this is a variable intended to be used by reference rather than value

	$sMyString = 'Hello World';
	$iCount = 43;
	$aMyArray = array();
	$asValue = $bIsString ? 'My String' : array( 'My Array' );
	

## Array Key Naming ##

Use lower case characters with underscores. 

	array(
		'first_key'		=>	'some value',
		'second_key'	=>	'another value',
	);

When it's internal and certain that the user will not need to modify the value, use the above alternative Hungarian notation to imply that the elements are for internal use.

	private $_aLibraryInfo = array(
		'sName'		=> ...,
		'sVersion'	=> ...,
	);

Or add a prefix	of an underscore.

	private $_aLibraryInfo = array(
		'_name'		=> ...,
		'_version'	=> ...,
	);
	
## Function and Method Naming ##

Add the underscore prefix for _internal_ methods regardless of the scope. *Internal* here means that the end-users will not need to use therefore they don't need to pay attention to it.

	_fomrmatData();

Start from always a verb. 

	run();
	doTask();
	
Use the camel-back notation.

	doMyStuff();
	
Not, 
	
	do_my_stuff();
	
For callback functions, prepend `replyTo` to help understand it's a callback. 

	replyToDoMyStuff();
	
Usually the framework callbacks are internal, so prepend an underscore to it.

	_replyToHandleCallbacks();
	