export default ($) => {
    $( ".danger-form" ).submit(function( event ) {
        event.preventDefault();
        if (confirm("Are you realy want delete profile?")) {
            this.submit();
        }
    });
}