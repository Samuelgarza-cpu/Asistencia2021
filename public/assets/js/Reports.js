$(function() {
    'use strict';
    $('#supports_id').change(function() {
        if ($(this).val() != '') {
            var support_id = $(this).val();
            var action = 'productsFilter';
            $.ajax({
                    type: "POST",
                    url: "reportes",
                    data: { 'action': action, 'support_id': support_id, "_token": $("meta[name='csrf-token']").attr("content") }
                })
                .done(function(response) {
                    console.log(response);
                    var products = $('#products_id');
                    products.prop('disabled', false);
                    products.find('option').remove();
                    products.append('<option value="0">Todos</option>');
                    $(response).each(function(i, v) { // indice, valor
                        products.append('<option value="' + v.id + '">' + v.name + '</option>');
                    });
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {})
        }
    });
});


function getProducts() {

}

function filterInformation() {
    // if ($(this).val() != '') {
    //     var curp = $(this).val();
    //     var action = 'check';
    //     $.ajax({
    //             type: "POST",
    //             url: "nueva_solicitud",
    //             data: { 'action': action, 'curp': curp, "_token": $("meta[name='csrf-token']").attr("content") }
    //         })
    //         .done(function(response) {
    //             if (response == "Ya existe un usuario con esa curp")
    //                 alertify.error(response);
    //             else
    //                 alertify.success(response);
    //         })
    //         .fail(function() {
    //             console.log("error");
    //         })
    //         .always(function() {})
    // }
    alert('You click on me');
}

function exportExcel() {
    alert('You click on you');
}

function exportPDF() {
    alert('You click on she');
}