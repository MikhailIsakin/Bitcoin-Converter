jQuery( document ).ready(function( $ ) {

    function getCurrencies() {
            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: 'action=get_currencies',
                dataType:"json",
                beforeSend: function( xhr ) {},
                success: function( data ) {
                    $.each(data, function( currency, price ){
                        $( '.btcc-select' ).append( $( '<option></option>', {value: price, text: currency} ) );
                    });
                }
            });
    }

    function countPrice() {
        var value = $( '.btcc-input' ).val(),
            currency = $( '.btcc-select option:selected' ).text(),
            price = $( '.btcc-select option:selected' ).val(),
            result = value * price;

        $( '.btcc-result' ).text( result );

        if (  $( '.btcc-history-item' ).length > 9 ) {
            $('.btcc-history-item:first').remove();
        }
        $( '.btcc-history' ).append( $( '<div class="btcc-history-item">' + value + ' BTC = ' + result + ' ' + currency + '</div>' ) );
    }


    $( '.btcc-select' ).change(function(){
        countPrice();
    });

    $( '.btcc-input' ).change(function(){
        countPrice();
    });

    $( '.btcc-input' ).keyup( function(){
        if( $( this ).val() < 0 ){
            $( this ).val( '0' );
        }
        countPrice();
    });

    getCurrencies();

});