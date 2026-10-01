(function ($) {
  'use strict';

  function initColorPickers(context) {
    var $context = context ? $(context) : $(document);
    $context.find('.lm-color-field').each(function () {
      var $field = $(this);
      if ($field.hasClass('wp-color-picker')) return;
      $field.wpColorPicker();
    });
  }

  function mediaTitle(type) {
    return type === 'video' ? 'Choose a video' : 'Choose an image';
  }

  function mediaButton(type) {
    return type === 'video' ? 'Use this video' : 'Use this image';
  }

  $(document).on('click', '.lm-media-select', function (event) {
    event.preventDefault();

    var button = this;
    var targetId = button.getAttribute('data-target');
    var mediaType = button.getAttribute('data-media-type') || 'image';
    var input = document.getElementById(targetId);
    if (!input || typeof wp === 'undefined' || !wp.media) return;

    var frame = wp.media({
      title: mediaTitle(mediaType),
      button: { text: mediaButton(mediaType) },
      library: { type: mediaType },
      multiple: false
    });

    frame.on('select', function () {
      var attachment = frame.state().get('selection').first().toJSON();
      input.value = attachment.url || '';
      input.dispatchEvent(new Event('change', { bubbles: true }));
    });

    frame.open();
  });

  $(document).on('click', '.lm-media-clear', function (event) {
    event.preventDefault();
    var targetId = this.getAttribute('data-target');
    var input = document.getElementById(targetId);
    if (!input) return;
    input.value = '';
    input.dispatchEvent(new Event('change', { bubbles: true }));
  });

  $(function () {
    initColorPickers(document);
  });

  $(document).on('widget-added widget-updated', function (event, widget) {
    initColorPickers(widget);
  });

  if (typeof wp !== 'undefined' && wp.customize) {
    wp.customize.bind('ready', function () {
      initColorPickers(document);
    });
  }
})(jQuery);
