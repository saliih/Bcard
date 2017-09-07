/**
 * Created by sarra on 16/05/17.
 */
$(document).ready(function () {
    $('#upload-file-selector').on('change',function (event) {
        $('#uploadfile').submit();
    });
    $('#uploadfile').on('submit',function () {
        var data = new FormData(this);
        var url = $(this).attr('action');

        getRequest(Routing.generate('upload_logo'), data, function (result) {

            var html = '<image x="1" y="13.5" width="118" height="114.56311" id="recto_svg_13" xlink:href="'+result+'" style="position: relative;">'
            $('.recto svg').
            /*var img = "image"+Math.floor((Math.random() * 1000000) + 1).toString();
            var html = '<i:pgf id="'+img+'">'+result+'</i:pgf>'
            $('.recto svg').append(html);
            var html2 = '<foreignObject requiredExtensions="&amp;ns_ai;" x="0" y="0" width="1" height="1">'
                    +'<i:pgfref xlink:href="#'+img+'">'
                    +'</i:pgfref></foreignObject>';
            $('.recto svg').prepend(html2);*/
        }, {type: "POST"}, {
            cache: false,
            dataType: 'html',
            mimeType: "multipart/form-data",
            contentType: false,
            processData: false
        });
        return false;
    });


    $.each($('svg'), function (index, svg) {
        $(svg).parent().width($(svg).width());
        $(svg).parent().height($(svg).height());
        $(svg).parent().css({margin: '0 auto'});
    });
    $('#formimport').on('submit', function (event) {
        var data = $(this).serializeArray();

        if ($('.recto').length) {
            data.push({name: 'recto', value: $('.recto').html()});
        }
        if ($('.verso').length) {
            data.push({name: 'verso', value: $('.verso').html()});
        }
        getRequest(Routing.generate('submitinvoice'), data, function (response) {
            $('#fiche_Validation').modal('hide');
            $('#validation').modal();
        });

        return false;
    });
    $('g image').resizable();
    $('g image')
        .draggable({
            containment: "g",
            scroll: true,
            cursor: "move",

        })
        .bind('drag', function (event, ui) {
            // update coordinates manually, since top/left style props don't work on SVG
            //
            event.target.setAttribute('x', event.offsetX);
            event.target.setAttribute('y', event.offsetY);
        });

    $('rect, image, text, circle, path').off().on('click', function (event) {
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
                fill: $(this).attr('fill'),
            }
        }
        window.element = $(this);

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
            $('.formedition input[type=file]').on('change', function (event) {
                $(event.currentTarget).closest('form').submit();
            });
            $('#formlogo').on('submit', function (event) {
                var data = new FormData(this);
                var url = $(this).attr('action');

                getRequest(url, data, function (result) {
                    var id = $("input[type=file]").attr("data-id");
                    $('#' + id).attr('xlink:href', result);
                }, {type: "POST"}, {
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
        window.element.text(val);
    } else {
        window.element.attr(attr, val);
    }
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