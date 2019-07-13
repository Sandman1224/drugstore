$(function() {
    $('.updatePoints').on('click', function(){
        let client = $(this).closest("tr").find('td:eq(1)').text();
        $('#txt-dni').val(client);

        $('#updatePoints_popup').modal('show');
    });

    $('#form-updatePoints').on('submit', function(e){
        e.preventDefault();

        let dniClient = $('#txt-dni').val();
        let pointsUpdated = $('#txt-points').val();

        let form_data = new FormData();
        form_data.append('dniClient', dniClient);
        form_data.append('pointsUpdated', pointsUpdated);

        $.ajax({
            url: 'index.php?r=clients/updatepoints',
            type: 'POST',
            data: form_data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            secureuri: false,
            datatype: 'json',
            success: function success(data){
                if(data.result === 'success'){
                    $.pjax.reload({container: '#pjax-grid-view'});
                    $('#updatePoints_popup').modal('toggle');
                }else{
                    alert(data.message);
                }
            },
            error: function(){
                alert('Hubo un error al intentar actualizar los puntos del cliente');
            }
        });
    });
});