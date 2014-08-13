/**
 * NavaJcrop class file.
 * This extension is a wrapper of http://blueimp.github.io/JavaScript-Load-Image/
 *
 * @author Le Phuong <notteen@gmail.com> & Dao Bui <dao.hunter@gmail.com>
 * @website http://navagroup.net
 * @version 0.1
 * @license http://www.opensource.org/licenses/MIT
 */
$(function () {
    'use strict';
    if(unique != ''){
        var uniqueId = unique;
    } else {
        var uniqueId = '';
    }
    var result = $('#jcrop_result'+uniqueId),
        thumbNode = $('#thumbnail'+uniqueId),
        actionsNode = $('#jcrop_actions'+uniqueId),
        currentFile,
        replaceResults = function (img) {
            var content;
            if (!(img.src || img instanceof HTMLCanvasElement)) {
                content = $('<span>Loading image file failed</span>');
                result.children().replaceWith(content);
            } else {
                result.append(img);
                result.attr('style',resultStyle);
                actionsNode.attr('style','text-align: center;');
                $('#JcropOverlay'+uniqueId).removeClass('hide');
            }
            if (img.getContext) {
                actionsNode.show();
            }
        },
        appendToDiv = function (img){
            var content;
            if (!(img.src || img instanceof HTMLCanvasElement)) {
                content = $('<span>Loading image file failed</span>');
                result.children().replaceWith(content);
                errorFunction();
            } else {
                var object = $('#jcrop_image'+uniqueId);
                object.attr('src',img.toDataURL());
                $('.jcrop-holder').remove();
                actionsNode.attr('style','display: none;');
                result.attr('style','');
                //$('canvas').remove();
                $('#JcropOverlay'+uniqueId).addClass('hide');
                successFunction(object,img.toDataURL());
            }
        },
        displayImage = function (file, options) {
            currentFile = file;
            if (!loadImage(file, replaceResults, options)) {
                result.children().replaceWith(
                    $('<span>Your browser does not support the URL or FileReader API.</span>')
                );
            }
        },
        dropChangeHandler = function (e) {
            e.preventDefault();
            e = e.originalEvent;
            var target = e.dataTransfer || e.target,
                file = target && target.files && target.files[0],
                options = {
                    maxWidth: resultMaxWidth,
                    canvas: true
                };
            if (!file) {
                return;
            }
            thumbNode.hide();
            loadImage.parseMetaData(file, function (data) {
                displayImage(file, options);
            });
        },
        coordinates;
    if (window.createObjectURL || window.URL || window.webkitURL || window.FileReader) {
        result.children().hide();
    }
    $('#jcrop_fileinput'+uniqueId).on('change', dropChangeHandler);
    $('#jcrop_edit'+uniqueId).on('click', function (event) {
        event.preventDefault();
        var imgNode = result.find('img, canvas'),
            img = imgNode[0];
        imgNode.Jcrop({
            aspectRatio: aspectRatio,
            setSelect: [40, 40, img.width - 40, img.height - 40],
            onSelect: function (coords) {
                coordinates = coords;
            },
            onRelease: function () {
                coordinates = null;
            }
        }).parent().on('click', function (event) {
            event.preventDefault();
        });
    });
    $('#jcrop_crop'+uniqueId).on('click', function (event) {
        event.preventDefault();
        var img = result.find('img, canvas')[0];
        if (img && coordinates) {
            appendToDiv(loadImage.scale(img, {
                left: coordinates.x,
                top: coordinates.y,
                sourceWidth: coordinates.w,
                sourceHeight: coordinates.h,
                minWidth: resultMinWidth,
            }), event);
            coordinates = null;
        }
    });
    $('#jcrop_cancel'+uniqueId).on('click', function (event) {
        $('.jcrop-holder'+uniqueId).remove();
        actionsNode.attr('style','display: none;');
        result.attr('style','width: 350px;');
        $('#JcropOverlay'+uniqueId).addClass('hide');
        $('canvas').remove();
    });
    $('#load_image'+uniqueId).hover(function(){
        $('#load_image_hover'+uniqueId).removeClass('hide');
    }, function(){
        $('#load_image_hover'+uniqueId).addClass('hide');
    });
});
