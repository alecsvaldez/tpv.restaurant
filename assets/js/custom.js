jQuery(document).ready( function() { 
    $('.select-dependant').on('change', function(){
        var child_selector = $(this).attr('data-child')
        var url = '/ajax/' + $(this).attr('data-url') + $(this).val()
        $(child_selector).html('')
        $.ajax({
            method: 'GET',
            url: url,
            success: function(response){
                if (response.length > 0){
                    $(child_selector)
                        .removeAttr('disabled')
                        .removeClass('disabled')
                        .append($("<option />").val('').text('Selecciona Uno'));
                    $.each(response, function() {
                        $(child_selector).append($("<option />").val(this.id).text(this.nombre));
                    });
                } else {
                    $(child_selector)
                        .attr('disabled','disabled')
                        .addClass('disabled')
                        .append($("<option />").val('').text('Sin opciones'));
                }
            }
        })
    })
});

//console.log((186457865).fileSize(false)); // false for IEC (power 1024)
//console.log((186457865).fileSize()); //1,true for SI (power 1000) DEFAULT para mi
Object.defineProperty(Number.prototype, 'fileSize', {
    value: function (a = true, b, c, d) {
        return (a = a ? [1e3, 'k', 'b'] : [1024, 'K', 'iB'], b = Math, c = b.log,
            d = c(this) / c(a[0]) | 0, this / b.pow(a[0], d)).toFixed(2)
            + ' ' + (d ? (a[1] + 'MGTPEZY')[--d] + a[2] : 'Bytes');
    }, writable: false, enumerable: false
});