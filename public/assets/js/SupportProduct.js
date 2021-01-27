$(function() {
    'use strict';
    addWActive();
    refresh('/productos_apoyos');
});

window.operateEvents = {
    'click .update': function(e, value, row, index) {
        // alert('You click like action, row: ' + JSON.stringify(row.id));
        $('.modal-title').text('Modificar Registro');
        $('#action').val("update");
        $('#frm').removeClass('was-validated')
        $('#id').val(row.id);
        $('#supports_id').val(row.supports_id);
        $('#products_id').val(row.products_id);
        if (row.active == 1)
            $('#active').attr("checked", "checked");
        else
            $('#active').removeAttr("checked");
        $('#modal-register').modal('toggle');
    },
    'click .remove': function(e, value, row, index) {
        $('#registerId').val(row.id);
        $('#modal-confirmation').modal('toggle');
    }
}