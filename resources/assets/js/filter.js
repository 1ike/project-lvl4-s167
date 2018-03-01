(function () {
    const $form = $( "#filterForm" );
    const $input = $( "input[name=orderBy]" );

    $( ".filter-btn" ).click(function( event ) {
        event.preventDefault();
        const column = $( this ).data("column");
        const order = $( this ).data("order");
        const wasAscOrder = order === 'asc';
        const newOrder = wasAscOrder ? 'desc' : 'asc';

        const value = `${column}:${newOrder}`;
        
        if ($input.length) {
            $input.val(value);
        } else {
            $form.append( `<input name="orderBy" value="${value}" type="hidden">` )
        }

        $form.submit();
    });

    $form.submit(function( event ) {
        event.preventDefault();

        $inputs = $( this ).find('input');
        $selects = $( this ).find('select');

        $inputs.add($selects).each(function() {
            if (!this.value) {
                this.parentNode.removeChild(this);
            }
        });

        this.submit();
    });
})();
