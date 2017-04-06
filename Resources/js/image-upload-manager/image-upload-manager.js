$(document).ready(function() {

    var $fieldGroup = $('#illustration-field-group');
    var $dimensionGroup = $('#dimension-field-group')

    var $fakeFileField = $('.fake-file-field', $fieldGroup);
    var cropperRatio = $fakeFileField.data('ratio');

    var $progressBar = $('#file-upload-progress.progress', $fieldGroup);
    var $progressBarInner = $('.progress-bar', $progressBar);
    var $progressBarValue = $('.value', $progressBarInner);

    var $previewModalLinkWrapper = $('.preview-modal-link-wrapper', $fieldGroup);
    var $previewModalLink = $('.preview-modal-link', $previewModalLinkWrapper);
    var $previewModal = $('.preview-modal', $previewModalLinkWrapper);

    var coordinatesElements = {
        width: $('.crop-width', $dimensionGroup),
        height: $('.crop-height', $dimensionGroup),
        offsetX: $('.crop-offset-x', $dimensionGroup),
        offsetY: $('.crop-offset-y', $dimensionGroup)
    };

    $('.fileupload-trigger', $fieldGroup).fileupload({
        dataType: 'json',
        done: function (e, data) {
            $progressBarValue
                .text(data.files[0].name);
            $fakeFileField
                .val(data.result.webPath);

            $previewModalLinkWrapper.removeClass('hidden');

            if ($('img', $previewModalLink)) {
                $('img', $previewModalLink).remove();
            }

            $previewModalLink.prepend(
                $('<img>')
                    .addClass('lazy-image')
                    .addClass('img-responsive')
                    .addClass('original-thumb')
                    .attr('data-original', '/' + data.result.webPath)
            );
            $('img', $previewModalLink).lazyload();

            if ($('.modal-body img', $previewModal)) {
                $('.modal-body img', $previewModal).cropper('destroy');
                $('.modal-body img', $previewModal).remove();
            }

            $('.modal-body', $previewModal).append(
                $('<img>')
                    .addClass('img-responsive center-block')
                    .attr('src', '/' + data.result.webPath)
            );

            var $imgToCrop = $('.modal-body img', $previewModal);

            $previewModal.one('shown.bs.modal', function (event) {
                $imgToCrop.cropper({
                    aspectRatio: cropperRatio,
                    zoomable: false,
                    preview: '.preview-modal-link .cropper-preview',
                    crop: function(e) {
                        // Output the result data for cropping image.
                        // console.log(e.x);
                        // console.log(e.y);
                        // console.log(e.width);
                        // console.log(e.height);
                        // console.log(e.rotate);
                        // console.log(e.scaleX);
                        // console.log(e.scaleY);
                    },
                    built: function () {
                        $('.original-thumb', $previewModalLink)
                            .addClass('hidden');

                        $('.btn-crop', $previewModal).removeClass('hidden');

                        $('.btn-crop', $previewModal).unbind('click');
                        $('.btn-crop', $previewModal)
                            .bind('click', function() {
                                var data = $imgToCrop.cropper('getData');

                                coordinatesElements.width.val(data.width);
                                coordinatesElements.height.val(data.height);
                                coordinatesElements.offsetX.val(data.x);
                                coordinatesElements.offsetY.val(data.y);

                                $previewModal.modal('hide');
                            });
                    }
                });
            });

            $previewModalLink.click();
        },
        add: function (e, data) {
            var uploadErrors = [];
            var acceptFileTypes = /^image\/(gif|jpe?g|png)$/i;
            if(data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
                uploadErrors.push('Not an accepted file type (Images only)');
            }
            if(data.originalFiles[0]['size'] && data.originalFiles[0]['size'] > 5000000) {
                uploadErrors.push('Filesize is too big (Maximum allowed: 5MB)');
            }
            if(uploadErrors.length > 0) {
                $.notify({
                    // options
                    message: uploadErrors.join("<br />"),
                    icon: 'fa fa-danger'
                },{
                    // settings
                    type: 'danger',
                    placement: {
                        from: 'top',
                        align: 'center'
                    },
                    offset: 60
                });
            } else {
                data.submit();
            }
        },
        always: function (e, data) {
            $progressBar.addClass('hidden');
        },
        start: function (e) {
            $progressBar.removeClass('hidden');
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $progressBarInner.text(progress + '%');
            $progressBarInner.css(
                'width',
                progress + '%'
            );
        }
    });

});