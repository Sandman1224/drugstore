$(function() {
    $('#configSales').on('click', function(){
        $.ajax({
            url: 'index.php?r=sales/getconfigdata',
            type: 'POST',
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            secureuri: false,
            datatype: 'json',
            success: function success(data){
                console.log(data);
                if(data.result === 'success'){
                    $('#txt-mount').val(data.mountByPoint);
                    $('#configSales_popup').modal('show');
                }else{
                    alert('Hubo un error al intentar obtener los datos de configuración de ventas');
                }
            },
            error: function(){
                alert('Hubo un error al intentar configurar las ventas');
            }
        });

    });

    $('#form-configSales').on('submit', function(e){
        e.preventDefault();

        let mountByPoint = $('#txt-mount').val();

        let form_data = new FormData();
        form_data.append('mountByPoint', mountByPoint);

        $.ajax({
            url: 'index.php?r=sales/configsales',
            type: 'POST',
            data: form_data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            secureuri: false,
            datatype: 'json',
            success: function success(data){
                console.log(data);
                if(data.result === 'success'){
                    console.log(data.data);

                    $('#txt-mount').val('');
                    $('#configSales_popup').modal('hide');
                }else{
                }
            },
            error: function(){
                alert('Hubo un error al intentar configurar las ventas');
            }
        });
    });

    /**
     * Controles y Eventos del Dynamic Form
     */
    $('#products-0-idsaleproduct').on('change', function(){
        var productSelected = $('#products-0-idsaleproduct').val();
        changeProduct(productSelected, 0);
    });

    $('#products-0-quantity').on('change', function(){
        calculatePriceSale();
    });

    $(".dynamicform_wrapper").on("beforeInsert", function(e, item) {
        //console.log("beforeInsert", item);
    });

    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        let selectProduct = $(item).find('div.panel-body div.form-group select');

        $(selectProduct).on('change', function(){
            let totalPanels = $(".item.panel.panel-default").length;
            let curPanelIndex = totalPanels - 1;

            let productSelected = this.value;
            changeProduct(productSelected, curPanelIndex);
        });

        let quantityProduct = $(item).find('.productQuantity');
        $(quantityProduct).on('change', function(){
            calculatePriceSale();
        });

        calculatePriceSale();
    });

    $(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
        if (! confirm("¿Estas seguro de que deseas eliminar este item?")) {
            return false;
        }

        let selectProduct = $(item).find('div.panel-body div.form-group select');
        $(selectProduct).unbind('change');

        return true;
    });

    $(".dynamicform_wrapper").on("afterDelete", function(e) {
        console.log("Deleted item!");

        calculatePriceSale();
    });

    $(".dynamicform_wrapper").on("limitReached", function(e, item) {
        alert("El límite de productos se alcanzó");
    });

    $('#btnFind-client').on('click', function(){
        let dniClient = $('#sales-client').val();

        let form_data = new FormData();
        form_data.append('dniClient', dniClient);

        $.ajax({
            url: 'index.php?r=clients/findclientbyajax',
            type: 'POST',
            data: form_data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            secureuri: false,
            datatype: 'json',
            success: function success(data){
                if(data.result === 'success'){
                    $('#sales-client').val(data.dni);

                    toastr.success(data.lastname + ', ' + data.firstname, 'Cliente encontrado');
                }else{
                    $('#sales-client').val('');
                    $('#btn-newClient').removeClass('hide');

                    toastr.warning('Los puntos no se guardarán', 'Cliente no encontrado');
                }
            },
            error: function(){
                alert('Error al cargar los datos del cliente.');
            }
        });
    });

    $('#btn-newClient').on('click', function(){
        clearClientForm();
        $('#client_popup').modal('show');
    });

    $('#form-client').on('submit', function(e){
        e.preventDefault();

        let firstname = $('#txt-firstname').val();
        let lastname = $('#txt-lastname').val();
        let dni = $('#txt-dni').val();

        let form_data = new FormData();
        form_data.append('firstname', firstname);
        form_data.append('lastname', lastname);
        form_data.append('dni', dni);

        $.ajax({
            url: 'index.php?r=clients/createbyajax',
            type: 'POST',
            data: form_data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            secureuri: false,
            datatype: 'json',
            success: function success(data){
                console.log(data);
                if(data.result === 'success'){
                    $('#sales-client').val(data.client);
                    $('#client_popup').modal('hide');

                    toastr.success('El cliente fue agregado con éxito', 'Alta Exitosa');
                }else{
                    console.log(data.errors);
                    toastr.error('Revise la información ingresada', 'Error');

                    $('#errors').empty();
                    $('#errors').removeClass('hide');
                    $.each(data.errors, function(indexError, valueError){
                        $.each(valueError, function(indexRecord, valueRecord){
                            $('#errors').append('<p>' + valueRecord + '</p>');
                        });
                    });
                }
            },
            error: function(){
                alert('Hubo un error al intentar crear el nuevo cliente');
            }
        });
    });

    /**
     * ----------
     */

    $('.salesSelect').on('click', function () {
        let keys = $('#salesTable').yiiGridView('getSelectedRows');

        let idSales = [];
        $.each(keys, function (index, value) {
            let sale = $('#salesTable tbody tr').eq(value).find('td').eq(1);

            idSales.push(sale.html());
        });

        console.log(idSales);

        if(idSales.length > 0){
            $.redirect('index.php?r=sales/reports', {'idSales': idSales});
        }else{
            //toastr.error('No se seleccionaron ventas para generar reporte', 'Error');
            $('#exportSales_popup').modal('show');
        }
    });
});

/**
* Limpia el formulario ajax de alta de cliente para su próximo uso
*/
function clearClientForm() {
    $('#errors').empty();
    $('#errors').addClass('hide');
    $('#txt-firstname').val('');
    $('#txt-lastname').val('');
    $('#txt-dni').val('');
}

function changeProduct(productSelected, index) {
    var form_data = new FormData();
    form_data.append('idProduct', productSelected);

    $.ajax({
        url: 'index.php?r=sales/dataproduct',
        type: 'POST',
        data: form_data,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        secureuri: false,
        datatype: 'json',
        success: function success(data) {
            console.log(data);
            if (data.result === 'success') {
                $('#products-' + index + '-name').val(data.name);
                $('#products-' + index + '-price').val(data.price);
                $('#products-' + index + '-quantity').val(1);

                calculatePriceSale();
            } else {
                alert(data.message);
            }
        },
        error: function () {
            alert('Error al cargar los datos del producto.');
        }
    });
}

function calculatePriceSale(){
    console.log('calculatePriceSale()');
    let priceSale = 0;
    $('.productQuantity').each(function (index, value) {
        let priceProduct = $('#products-' + index + '-price').val();
        let quantityProduct = $('#products-' + index + '-quantity').val();

        priceSale += priceProduct * quantityProduct;

        $('#sales-price').val(priceSale);
    });
}