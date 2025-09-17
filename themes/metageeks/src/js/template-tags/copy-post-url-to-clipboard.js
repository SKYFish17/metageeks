const copyButton = document.getElementById( 'copy_post_url_button' );

if ( copyButton ) {
	copyButton.addEventListener( 'click', function () {
		const inputEl = document.createElement( 'input' );
		const pageUrl = window.location.href;
		const copyButtonText = copyButton.querySelector( 'span' );

		document.body.appendChild( inputEl );
		inputEl.value = pageUrl;
		inputEl.select();
		document.execCommand( 'copy' );
		document.body.removeChild( inputEl );
		copyButton.style.color = '#ffffff';
		copyButton.style.background = '#81324A';
		copyButtonText.innerHTML = 'Copied!';
	} );
}
