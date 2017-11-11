/**
 * Created by sarra on 16/05/17.
 */
function refreshText(){
    $('.textelementrecto').html("");
    $.each($('svg'),function (index,svg) {
        var $svg = $(svg);
        $.each($svg.find('text'),function (key,text) {
            var content = $(text).html();
            var id = $(text).attr('id');
            if(content.trim())
                var html = '<label  id="bc_'+id+'" data-value="'+id+'" class="form-control element" >'+content+'</label>';
            $('.textelementrecto').append(html);
        });

    });
    $('.element').off().on('click',function (event) {
        var id = $(this).attr('data-value');
        $('#'+id).trigger('click');
    });
}
function saveTemplate(){
    var html = $('.recto').html();
    setAppData("localsvg"+window.template,html);
}
$(document).ready(function () {
    //validation bt
    $('body :not(svg)').on('click',function () {
        $('svg [style]').removeAttr('style');
    });
    $('#validationModal').on('click',function () {
        $('svg [style]').removeAttr('style');
       $('#fiche_Validation').modal();
    });



    var width = $('.recto svg').width() * 1.5;
    var height = $('.recto svg').height() * 1.5;
    $('.recto svg').height(height);
    $('.recto svg').width(width);
    // get from app data
    if(getAppData("localsvg"+window.template)){
        $('.recto').html(getAppData("localsvg"+window.template));
    }

    // detect text
    refreshText();
    $('.recto svg i').remove();
    $('#upload-file-selector').on('change',function (event) {
        $('#uploadfile').submit();
        saveTemplate();
    });
    $('#upload-file-selector-background').on('change',function (event) {
        $('#uploadfile-background').submit();
        saveTemplate();
    });
    $('#uploadfile').on('submit',function () {
        var data = new FormData(this);
        var url = $(this).attr('action');

        getRequest(Routing.generate('upload_logo'), data, function (result) {
            var x1 = 259/2 - result.width/2;
            var y1 = 145 / 2 - result.height/2;
            if(result.type == "svg"){

                var html = '<g><image  x="'+x1+'" y="'+y1+'" width="' + result.width + '" height="' + result.height + '" xlink:href="' + result.url + '" /></g>';
            }else {
                var html = '<g><image height="' + result.width + '" width="' + result.height + '" xlink:href="' + result.base64 + '" x="'+x1+'" y="'+y1+'"></image></g>';
            }
            if($('.recto svg switch g').eq(0).length){
                $('.recto svg switch g').eq(0).append(html);
            }else{
                $('.recto svg').append(html);
            }
            var oldhtml = $('.recto svg').html();
            var newhtml = oldhtml.replace(/img/g, "image");
            $('.recto svg').html(newhtml);
            $.each($('image'),function (index,element) {
                $(element).after("</image>");
            });

            $('g image').resizable();
            $('g image, g text')
                .draggable({
                    containment: "g",
                    scroll: false,
                    //cursor: "move",
                    cursor: "pointer",
                    cursorAt: { left: Math.round($(this).outerWidth() / 2), top: Math.round($(this).outerHeight() / 2)  }
                })
                .bind('drag', function (event, ui) {
                    // update coordinates manually, since top/left style props don't work on SVG
                    //
                    event.target.setAttribute('x', event.offsetX);
                    event.target.setAttribute('y', event.offsetY);
                });
            saveTemplate();
        }, {type: "POST"}, {
            cache: false,
            dataType: 'json',
            mimeType: "multipart/form-data",
            contentType: false,
            processData: false
        });

        return false;
    });


    $('#uploadfile-background').on('submit',function () {
        var data = new FormData(this);
        var url = $(this).attr('action');

        getRequest(Routing.generate('upload_logo'), data, function (result) {
debugger;
            var x1 = 259/2 - result.width/2;
            var y1 = 145 / 2 - result.height/2;
            var html = '<g><image height="'+$('svg').eq(0).height()+'" width="'+$('svg').eq(0).width()+'" href="' +result.url+'"  x="'+x1+'" y="'+y1+'"></image></g>';
            if($('.recto svg switch g').eq(0).length){
                $('.recto svg switch g').eq(0).prepend(html);
            }else{
                $('.recto svg').prepend(html);
            }
            var oldhtml = $('.recto svg').html();
            var newhtml = oldhtml.replace(/img/g, "image");
            $('.recto svg').html(newhtml);
            $.each($('image'),function (index,element) {
                $(element).after("</image>");
            });
            saveTemplate();
        }, {type: "POST"}, {
            cache: false,
            dataType: 'json',
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
            saveTemplate();
           eventElements()
        });

        return false;
    });
    $('g image').resizable();
    $('g image, g text')
        .draggable({
            containment: "g",
            scroll: false,
            //cursor: "move",
            cursor: "pointer",
            cursorAt: { left: Math.round($(this).outerWidth() / 2), top: Math.round($(this).outerHeight() / 2)  }

        })
        .bind('drag', function (event, ui) {
            // update coordinates manually, since top/left style props don't work on SVG
            //
            event.target.setAttribute('x', event.offsetX);
            event.target.setAttribute('y', event.offsetY);
            saveTemplate();
        });

    eventElements()
});
function changeBlock(input) {
    var val = $(input).val();
    var id = $(input).attr("data-id");
    var attr = $(input).attr('data-element');
    if (attr == "fontfamily")attr = "font-family";
    if (attr == "fontsize")attr = "font-size";
    if (attr == "text") {
        window.element.text(val);
    }else if(attr == "width"){
        $(window.element).width($(window.element).attr("width") * parseFloat(val) / 100);
        $(window.element).height($(window.element).attr('height') * parseFloat(val) / 100);
        //debugger
    } else {
        window.element.attr(attr, val);
    }
    saveTemplate();
}
function eventElements(){
    $(document).off().on('click','polyline, polygon, line ,ellipse, rect, image, text, circle, path', function (event) {
        if(typeof window.element != "undefined") {
            window.element.removeAttr('style');
        }
        $('.formedition').css('display', 'block');
        var obj = {};
        if ($(this).is('rect') || $(this).is('polyline') || $(this).is('polygon') || $(this).is('line') || $(this).is('ellipse') || $(this).is('circle') ) {
            var type = "";
            if($(this).is('rect') ){
                type = 'rect';
            }
            if($(this).is('polyline') ){
                type = 'polyline';
            }
            if( $(this).is('polygon')){
                type = 'polygon';
            }
            if( $(this).is('line') ){
                type = 'line';
            }
            if($(this).is('ellipse')){
                type = 'ellipse';
            }
            if($(this).is('circle')){
                type = 'circle';
            }
            obj = {
                type: type,
                id: $(this).attr('id'),
                //stroke: $(this).attr('stroke'),
                fill: $(this).attr('fill')
               // x: $(this).attr('x'),
               // y: $(this).attr('y'),
               // width: $(this).attr('width'),
               // height: $(this).attr('height'),
            };
        } else if ($(this).is('image')) {
            obj = {
                type: 'image',
                id: $(this).attr('id'),
                x: $(this).attr('x'),
                y: $(this).attr('y'),
                width: $(this).attr('width'),
                height: $(this).attr('height'),
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
        } else if ($(this).is('path')) {
            obj = {
                type: 'path',
                fill: $(this).attr('fill'),
            }
        }
        window.element = $(this);
        window.element.attr('style',' stroke:#ffff00;');
        getRequest(Routing.generate('bcard_generate_form'), obj, function (html) {

            $('.formedition').html('').html(html);
            if($('#drop_image').length){
                $('#drop_image').off().on('click',function (event) {
                    window.element.remove();
                    $('.formedition').html('');
                    saveTemplate();
                    refreshText();
                    return false;
                })
            }
            $('.colorpicker').colorpicker({format:'hex'}).on('changeColor', function (event) {
                $('.colorpicker-component .input-group-addon i').attr('style','background:'+$(this).val());
                changeBlock(event.currentTarget);
            });
            $('.formedition input, .formedition textarea, .formedition select')
                .not('.colorpicker')
                .not('input[type=file]')
                .on('change', function (event) {
                    changeBlock(event.currentTarget);
                    refreshText();
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
                    saveTemplate();
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

function setAppData(key, val) {
    localStorage.setItem(key, typeof (val) == 'object' ? JSON.stringify(val) : val);
}

function getAppData(key, defaultValue, subkey) {
    var data = localStorage.getItem(key);
    if ((data != null) && (data.length != 0) && ((data[0] == "{") || (data[0] == "["))) {
        data = JSON.parse(data);
        if (typeof (subkey) != "undefined") {
            data = data[subkey];
        }
    }
    if ((data == null) && ( typeof (defaultValue) != 'undefined')) {
        return defaultValue;
    } else {
        return data;
    }
}