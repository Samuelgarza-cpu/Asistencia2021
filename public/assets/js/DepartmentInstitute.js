$(function() {
    'use strict';
    addWImageID();
    refresh('/instituto_departamento');
    var inputs = document.querySelectorAll('.stamp-img');
    Array.prototype.forEach.call(inputs, function(input) {
        var label = input.nextElementSibling,
            labelVal = label.innerHTML;
        input.addEventListener('change', function(e) {
            var fileName = '';
            if (this.files && this.files.length > 1)
                fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
            else
                fileName = e.target.value.split("'\'").pop();
            if (fileName) {
                label.querySelector('span').innerHTML = fileName;
                let reader = new FileReader();
                reader.readAsDataURL(e.target.files[0]);
                reader.onload = function() {
                    let preview = document.getElementById('imagenPrevisualizacion');
                    preview.src = reader.result;
                    preview.setAttribute("class", "file-img");
                };

            } else {
                label.innerHTML = labelVal;
            }
        });
    });
    var inputs = document.querySelectorAll('.image-img');
    Array.prototype.forEach.call(inputs, function(input) {
        var label = input.nextElementSibling,
            labelVal = label.innerHTML;
        input.addEventListener('change', function(e) {
            var fileName = '';
            if (this.files && this.files.length > 1)
                fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
            else
                fileName = e.target.value.split("'\'").pop();
            if (fileName) {
                label.querySelector('span').innerHTML = fileName;
                let reader = new FileReader();
                reader.readAsDataURL(e.target.files[0]);
                reader.onload = function() {
                    let preview = document.getElementById('imagenPrevisualizacion2');
                    preview.src = reader.result;
                    preview.setAttribute("class", "file-img");
                };

            } else {
                label.innerHTML = labelVal;
            }
        });
    });
});

window.operateEvents = {
    'click .update': function(e, value, row, index) {
        // alert('You click like action, row: ' + JSON.stringify(row));
        $('.modal-title').text('Modificar Registro');
        $('#action').val("update");
        $('#frm').removeClass('was-validated')
        $('#id').val(row.id);
        $('#departments_id').val(row.departments_id);
        $('#image').removeAttr("required");
        $('#institutes_id').val(row.institutes_id);
        if (row.stampSRC == null) {
            $('#imagenPrevisualizacion').removeAttr('src');
            $('#imagenPrevisualizacion').removeAttr('class');
        } else {
            $('#imagenPrevisualizacion').addClass("file-img");
            $('#imagenPrevisualizacion').attr('src', row.stampSRC);
        }

        if (row.stampSRC == null) {
            $('#imagenPrevisualizacion2').removeAttr('src');
            $('#imagenPrevisualizacion2').removeAttr('class');
        } else {
            $('#imagenPrevisualizacion2').attr('src', row.imageSRC);
            $('#imagenPrevisualizacion2').addClass("file-img");
        }


        $('#modal-register').modal('toggle');
    },
    'click .remove': function(e, value, row, index) {
        $('#registerId').val(row.id);
        $('#modal-confirmation').modal('toggle');
    }
}