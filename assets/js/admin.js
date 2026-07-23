(function ($) {
    'use strict';

    $(function () {
        var frame;
        var $destinationPicker = $('[data-mfi-destination-picker]');
        var $filePicker = $('.mfi-file-picker');

        if (!$destinationPicker.length) {
            return;
        }

        function updateDestinationPanels() {
            var selectedType = $destinationPicker.find('input[name="mfi_source_type"]:checked').val() || 'file';

            $destinationPicker.find('[data-mfi-source-panel]').each(function () {
                var $panel = $(this);
                $panel.prop('hidden', $panel.data('mfi-source-panel') !== selectedType);
            });
        }

        $destinationPicker.on('change', 'input[name="mfi_source_type"]', updateDestinationPanels);
        updateDestinationPanels();

        if (!$filePicker.length || typeof wp === 'undefined' || !wp.media) {
            return;
        }

        var $input = $('#mfi-file-attachment-id');
        var $selection = $filePicker.find('.mfi-file-picker__selection');
        var $name = $filePicker.find('.mfi-file-picker__name');
        var $url = $filePicker.find('.mfi-file-picker__url');
        var $select = $filePicker.find('.mfi-select-file');
        var $remove = $filePicker.find('.mfi-remove-file');

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
