( function( $ ) {
	let buttons = document.querySelectorAll( 'div.sdz_table_contain_container div.sdz_table_contain_title button.sdz_toogle_button' );
	if( buttons.length ) {
		buttons.forEach( ( button ) => {
			button.addEventListener( 'click', ( e ) => {
				let table = button.closest( 'div.sdz_table_contain_container' ).querySelector( 'div.sdz_table_contain_table' );
				if( !table.classList.contains( 'hide' ) ) {
					table.classList.add( 'hide' );
					e.target.innerText = button.dataset.textShow;
				}
				else {
					table.classList.remove( 'hide' );
					e.target.innerText = button.dataset.textHide;
				}
			} );

			button.innerText = button.dataset.textHide;
		} );
	}
} )( jQuery );