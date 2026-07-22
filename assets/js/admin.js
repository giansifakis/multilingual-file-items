(function ($) {
    'use strict';

    $(function () {
        var frame;
        var $picker = $('.mfi-file-picker');

        if (!$picker.length || typeof wp === 'undefined' || !wp.media) {
            return;
        }

        var $input = $('#mfi-file-attachment-id');
        var $selection = $picker.find('.mfi-file-picker__selection');
        var $name = $picker.find('.mfi-file-picker__name');
        var $url = $picker.find('.mfi-file-picker__url');
        var $select = $picker.find('.mfi-select-file');
        var $remove = $picker.find('.mfi-remove-file');

        $select.on('click', function (event) {
            event.preventDefault();

            if (frame) {
                frame.open();
                return;
            }

            frame = wp.media({
                title: mfiAdmin.frameTitle,
                button: {
                    text: mfiAdmin.frameButton
                },
                multiple: false
            });

            frame.on('select', function () {
                var attachment = frame.state().get('selection').first().toJSON();
                var filename = attachment.filename || attachment.title || '';

                $input.val(attachment.id);
                $name.text(filename);
                $url.attr('href', attachment.url).text(mfiAdmin.openFile);
                $selection.prop('hidden', false);
                $remove.prop('hidden', false);
                $select.text(mfiAdmin.replaceButton);
            });

            frame.open();
        });

        $remove.on('click', function (event) {
            event.preventDefault();

            $input.val('');
            $name.text('');
            $url.attr('href', '#');
            $selection.prop('hidden', true);
            $remove.prop('hidden', true);
            $select.text(mfiAdmin.selectButton);
        });
    });
})(jQuery);
