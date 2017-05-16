$(document).ready(function () {
    $(".link").on('click', function (event) {
        $('#zoneedit').html('');
        $('#tableform>tbody').html('');
        var id = $(event.currentTarget).attr('data-value');
        var bg = $(event.currentTarget).find('img').attr('src');
        $('#zoneedit').html('<img src=' + bg + ' width="425" height="275" />');
        $.ajax({
            url: Routing.generate('drawAccrodion', {id: id}),
            type: 'get',
            dataType: 'html',
            success: function (html) {
                $('#accord').html(html);

                $('.formtosave, .formtocolor,.formalign, .formpolice').on('keyup keypress change', function (event) {
                    var id = $(this).attr('data-value');
                    var text = $('#input-' + id).val();
                    if ($('#input-' + id).attr('datatype') == "file") {
                        if (text != "") {
                            $('#box' + id).html('<img src="' + text + '" height="' + $('#box' + id).height() + '" width="' + $('#box' + id).width() + '" />');
                        }
                    } else {
                        $('#box' + id).html(text);

                    }
                    var color = $('#input-color-' + id).val();
                    var align = $('#input-align-' + id).val();
                    var police = $('#input-police-' + id).val();
                    $('#box' + id).css({
                        'text-align': align,
                        'color': color,
                        'font-family': police,
                    });
                });
            }
        });
        $.ajax({
            url: Routing.generate('gettemplates', {id: id}),
            type: 'get',
            dataType: 'json',
            success: function (data) {
                window.template = data;
                $.each(data, function (index, obj) {
                    $('#zoneedit').append('<div id="box' + obj.id + '"></div>');
                    $('#box' + obj.id).css({
                        'position': 'absolute',
                        'text-align': getAligne(obj.align),
                        'color': obj.color,
                        'police': (obj.police  ),
                        'line-height': ((obj.y2 - obj.y1) ) + "px",
                        'font-size': obj.size + "px",
                        'width': ((obj.x2 )  ) + "px",
                        'height': ((obj.y2 )  ) + "px",
                        'top': (obj.x1)+ "px",
                        'left': (obj.y1 )  + "px",
                    });


                    $('#input-color-' + obj.id).colorpicker();
                    // put collapse


                });
                $('#myModal').modal('show');
                $('#formcarte').on('submit', function (event) {
                    var object = [];
                    $.each($('.formtosave'), function (index, element) {
                        var id = $(element).attr('data-value');
                        var valeur = $("#input-" + id).val();
                        var color = $("#input-color-" + id).val();
                        var align = $("#input-align-" + id).val();
                        var police = $("#input-police-" + id).val();
                        object.push({id: id, value: valeur, color: color, align: align, police: police});
                    });
                    $.ajax({
                        url: Routing.generate('puttemplates'),
                        type: 'post',
                        dataType: 'json',
                        data: {prodid: idprod, tpl: object},
                        success: function (data) {
                            $('#myModal').modal('hide')
                            bootbox.alert("Votre Template est enregistré");
                        }
                    });

                    return false;
                });

            }
        });
        return false;
    });

    $('.optionprice').on("click", function (event) {
        var price = $(event.currentTarget).val();
        $('.boxSubmit>h5').html('Prix');
        $('.boxSubmit>h6').html(price + " <sup>DT</sup>");
    });
});
function getAligne(str) {
    if (str == "L")
        return 'left';
    else if (str == 'C')
        return "center";
    else if (str == "R")
        return 'right';
    return 'left';
}