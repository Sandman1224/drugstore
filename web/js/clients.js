$(function() {
    $('.updatePoints').on('click', function(){
        let client = $(this).closest("tr").find('td:eq(1)').text();
        $('#txt-dni').val(client);

        $('#updatePoints_popup').modal('show');
    });

    $('.changePoints').on('click', function(){
        let client = $(this).closest("tr").find('td:eq(1)').text();
        $('#txt-dni-points').val(client);
        $('#sl-points').trigger('change');

        $('#changePoints_popup').modal('show');
    });

    $('#sl-points').on('change', function(){
        let optionSelected = this.value;
        $('#txt-points-change').val(optionSelected);

        if (optionSelected === ''){
            $('#txt-points-change').removeClass('hide');
        }else{
            $('#txt-points-change').addClass('hide');
        }
    });

    $('#form-changePoints').on('submit', function(e){
        e.preventDefault();

        let dniClient = $('#txt-dni-points').val();
        let pointsToChange = $('#txt-points-change').val();

        let form_data = new FormData();
        form_data.append('dniClient', dniClient);
        form_data.append('pointsToChange', pointsToChange);

        $.ajax({
            url: 'index.php?r=clients/changepoints',
            type: 'POST',
            data: form_data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            secureuri: false,
            datatype: 'json',
            success: function success(data){
                if(data.result === 'success'){
                    $('#changePoints_popup').modal('toggle');
                    toastr.success('Se canjearon ' + data.changedPoints + ' puntos satisfactoriamente', 'Canjeo Exitoso');

                    setTimeout(function(){ location.reload(); }, 3000);
                }else{
                    toastr.error(data.message, 'Error');
                }
            },
            error: function(){
                alert('Hubo un error al intentar actualizar los puntos del cliente');
            }
        });
    });
});