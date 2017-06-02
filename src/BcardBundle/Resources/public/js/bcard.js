/**
 * Created by sarra on 16/05/17.
 */
$(document).ready(function () {
    $('#formimport').on('submit',function (event) {
        var data = $(this).serializeArray();
        if($('.recto').length){
            data.push({name:'recto',value:$('.recto').html()});
        }
        if($('.verso').length){
            data.push({name:'verso',value:$('.verso').html()});
        }
        getRequest(Routing.generate('submitinvoice'), data, function (response) {
            $('#fiche_Validation').modal('hide');
            $('#validation').modal();
        });

        return false;
    });
    $('g>image').resizable();
    $('g>image')
        .draggable({
            containment: "g",
            scroll: true,
            cursor: "move",

        })
        .bind('drag', function(event, ui){
            // update coordinates manually, since top/left style props don't work on SVG
           // debugger;
            event.target.setAttribute('x', event.offsetX);
            event.target.setAttribute('y', event.offsetY);
        });

    $('.bcard svg g').children().on('click', function (event) {
        $('.formedition').css('display', 'block');
        var obj = {};
        if ($(this).is('rect')) {
            obj = {
                type: 'rect',
                id: $(this).attr('id'),
                stroke: $(this).attr('stroke'),
                fill: $(this).attr('fill'),
                x: $(this).attr('x'),
                y: $(this).attr('y'),
                width: $(this).attr('width'),
                height: $(this).attr('height'),
            };
        } else if ($(this).is('image')) {
            obj = {
                type: 'image',
                id: $(this).attr('id'),
            }
        } else if ($(this).is('text')) {
            obj = {
                type: 'text',
                id: $(this).attr('id'),
                text: this.textContent,
                stroke: $(this).attr('stroke'),
                fill: $(this).attr('fill'),
                x: $(this).attr('x'),
                y: $(this).attr('y'),
                width: $(this).attr('width'),
                height: $(this).attr('height'),
                fontfamily: $(this).attr('font-family'),
                fontsize: $(this).attr('font-size'),
            }
        } else if ($(this).is('circle')) {
            obj = {
                type: 'circle',
                id: $(this).attr('id'),
                stroke: $(this).attr('stroke'),
                strokewidth: $(this).attr('stroke-width'),
                fill: $(this).attr('fill'),
                cx: $(this).attr('cx'),
                cy: $(this).attr('cy'),
                r: $(this).attr('r'),
            }
        } else if ($(this).is('path')) {
            obj = {
                type: 'path',
                id: $(this).attr('id'),
                fill: $(this).attr('fill'),
                stroke: $(this).attr('stroke'),
                strokewidth: $(this).attr('stroke-width'),
                fill: $(this).attr('fill'),
            }
        }
        window.element = obj;

        getRequest(Routing.generate('bcard_generate_form'), obj, function (html) {
            $('.formedition').html('').html(html);
            $('.colorpicker').colorpicker({
                customClass: 'colorpicker-2x',
                format: 'hex',
                sliders: {
                    saturation: {
                        maxLeft: 200,
                        maxTop: 200
                    },
                    hue: {
                        maxTop: 200
                    },
                    alpha: {
                        maxTop: 200
                    }
                }
            }).on('changeColor', function (event) {
                changeBlock(event.currentTarget);
            });
            $('.formedition input, .formedition select')
                .not('.colorpicker')
                .not('input[type=file]')
                .on('change', function (event) {
                changeBlock(event.currentTarget);
            });
            $('.formedition input[type=file]').on('change',function (event) {
               $(event.currentTarget).closest('form').submit();
            });
            $('#formlogo').on('submit',function (event) {
                var data = new FormData(this);
                var url = $(this).attr('action');

                getRequest(url, data, function (result) {
                    var id = $("input[type=file]").attr("data-id");
                    $('#'+id).attr('xlink:href',result);
                }, {type:"POST"}, {
                    cache: false,
                    dataType: 'html',
                    mimeType: "multipart/form-data",
                    contentType: false,
                    processData: false
                });
                return false;
            });
        })
    })
});
function changeBlock(input) {
    var val = $(input).val();
    var id = $(input).attr("data-id");
    var attr = $(input).attr('data-element');
    if (attr == "fontfamily")attr = "font-family";
    if (attr == "fontsize")attr = "font-size";
    if (attr == "text") {
        $("#" + id).text(val);
    } else
        $("#" + id).attr(attr, val);
}

function getRequest(url, _data, success_function, params, extraParams) {
    params = params || {};
    var showloader = params.showloader || true;

    var parameters = {
        async: true,
        url: url,
        type: params.type || "POST",
        data: _data,
        success: function (data) {
            if (data === "is_not_logged") {
                successDialog("Nyellow", "Session expired! Please login again.", BootstrapDialog.TYPE_WARNING, undefined, function () {
                    window.location.href = "login";
                });
            } else {
                if (typeof (success_function) == "function") {
                    success_function(data);
                }
            }
        },
        error: function (xhr) {
            errorDialog('Error contacting server', 'Error encountered : ' + xhr.responseText);
        },
        complete: function () {

        }
    };
    if (typeof(extraParams) == "object") {
        $.extend(parameters, extraParams);
    }
    $.ajax(parameters);
}