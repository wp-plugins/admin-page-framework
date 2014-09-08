(function($) {
    $.Gridster.saveGrid = function( selectorInput ) {
		
		var s = this.serialize();
		$( selectorInput ).val( JSON.stringify( s ) );	// set the value to the input

    };
	
	$.Gridster.getLargestColumnPosition = function() {
					
		var aResult = [];
		var iLargestColumnNumber = 1;
		var iLargestRowNumber = 1;
		
		this.$widgets.each( $.proxy( function( i, widget ) {
			
			iCurrentColumnNumber = parseInt( widget['dataset']['col'] ) + parseInt( widget['dataset']['sizex'] ) - 1;
			iLargestColumnNumber = iLargestColumnNumber < iCurrentColumnNumber ? iCurrentColumnNumber : iLargestColumnNumber;
			
			iCurrentRowNumber = parseInt( widget['dataset']['row'] ) + parseInt( widget['dataset']['sizey'] ) - 1;
			iLargestRowNumber = iLargestRowNumber < iCurrentRowNumber ? iCurrentRowNumber : iLargestRowNumber;
			
		}, this  ));
		
		return { 
			'x': iLargestColumnNumber, 
			'y': iLargestRowNumber,
		};			
		
	}
	
	/**
	 * Associate the remove widget function with the given elements.
	 */
	$.Gridster.setRemoveButton = function( joNode, selectorInput ) {		
		var oGridster = this;
		$( joNode ).click( function( event, ui ) {
			oGridster.remove_widget( $( this ).closest( '.gs-w' ) );
			oGridster.saveGrid( selectorInput );
			return false;
		});		
	}
	
	/**
	 * The original script does not expect the style varies depending on the instance. 
	 * So this should fix it.
	 */
	$.Gridster.add_style_tag = function(css) {
		var d = document;
		var tag = d.createElement( 'style' );

		tag.setAttribute( 'data-generated_by', 'gridster' );
		tag.setAttribute( 'id', this.options.namespace );
		
		d.getElementsByTagName('head')[0].appendChild(tag);
		tag.setAttribute('type', 'text/css');
		
		if (tag.styleSheet) {
			tag.styleSheet.cssText = css;
		} else {
			tag.appendChild(document.createTextNode(css));
		}
		
		$( 'head [id="' + this.options.namespace + '"][data-generated_by="gridster"]:not(:last)' ).remove();
		return this;
	};

   
})( jQuery );