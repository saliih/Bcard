/**
 * Created by sarra on 16/05/17.
 */
$(document).ready(function () {
    $('.bcard svg g').children().on('click',function (event) {
        var obj = {}
        if($(this).is('rect')){
            obj = {
                type: 'rect',
                id:$(this).attr('id'),
                stroke:$(this).attr('stroke'),
                fill:$(this).attr('fill'),
                x:$(this).attr('x'),
                y:$(this).attr('y'),
                width:$(this).attr('width'),
                height:$(this).attr('height'),
            };
        }else if($(this).is('text')){
            obj={
                type: 'text',
                id:$(this).attr('id'),
                stroke:$(this).attr('stroke'),
                fill:$(this).attr('fill'),
                x:$(this).attr('x'),
                y:$(this).attr('y'),
                width:$(this).attr('width'),
                height:$(this).attr('height'),
                fontfamily:$(this).attr('font-family'),
                fontsize:$(this).attr('font-size'),
            }
        }else if($(this).is('circle')){
            obj={
                type: 'circle',
                id:$(this).attr('id'),
                stroke:$(this).attr('stroke'),
                strokewidth:$(this).attr('stroke-width'),
                fill:$(this).attr('fill'),
                cx:$(this).attr('cx'),
                cy:$(this).attr('cy'),
                r:$(this).attr('r'),
            }
        }else if($(this).is('path')){
            obj={
                type: 'path',
                id:$(this).attr('id'),
                fill:$(this).attr('fill'),
                stroke:$(this).attr('stroke'),
                strokewidth:$(this).attr('stroke-width'),
                fill:$(this).attr('fill'),
            }
        }
console.log(obj)
        window.element = obj;
    })
});