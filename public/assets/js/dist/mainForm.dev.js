"use strict";

function addModal() {
  $('#add').on('click', function (evt) {
    $('.modal-title').text('Agregar Registro');
    $('#action').val("new");
    $('#frm').removeClass('was-validated');
    $('#modal').modal('toggle');
  });
}

function addNew($url) {
  $('#add').attr('href', $url);
}

function success() {
  alertify.success('Tus datos fueron almacenados de forma satisfactoria.');
}

function error() {
  alertify.error('Tus datos no pudieron ser almacenados.');
}

$(function () {
  window.addEventListener('load', function () {
    var forms = document.getElementsByClassName('needs-validation');
    var validation = Array.prototype.filter.call(forms, function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add('was-validated');
      }, false);
    });
  }, false);
});

function refresh($url) {
  var $tabla = $("#dataTable");
  $tabla.bootstrapTable({
    locale: "es-ES"
  });
  $.ajax({
    url: $url,
    type: 'POST',
    dataType: 'json',
    data: {
      "action": "query",
      "_token": $("meta[name='csrf-token']").attr("content")
    }
  }).done(function (response) {
    $tabla.bootstrapTable('showLoading');
    console.log({
      response: response
    });

    if (response.length > 0) {
      $tabla.bootstrapTable('destroy').bootstrapTable({
        height: 550,
        locale: "es-Es",
        data: response
      });
    }
  }).fail(function () {
    console.log("error");
  }).always(function () {
    $tabla.bootstrapTable('hideLoading');
  });
}