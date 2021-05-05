{
	function handleEscape( event ) {
		const newPageTitle =
			'undefined' !== typeof event.target.dataset.pageTitle
				? event.target.dataset.pageTitle
				: 'Google';
		const redirectURL =
			'undefined' !== typeof event.target.href
				? event.target.href
				: 'https://google.com';

		// Replace the current page's title.
		document.title = newPageTitle;

		// Append a redirect parameter to the current page's URL.
		window.history.replaceState( {}, '', '?qew=1' );

		// Redirect to the new page.
		window.location.href = redirectURL;

		event.preventDefault();
	}

	document.addEventListener( 'DOMContentLoaded', () => {
		const quickEscapes = document.querySelectorAll( '.js-quick-escape' );

		quickEscapes.forEach( ( el ) => {
			if ( 'undefined' === typeof el ) {
				return;
			}

			el.addEventListener( 'click', handleEscape );
		} );
	} );
}
