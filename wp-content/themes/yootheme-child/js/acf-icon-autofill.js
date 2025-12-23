// JavaScript Document
(function($) {
  if (typeof acf === 'undefined') return;

  const iconMap = {
    21: 'file-text',                       // Press Release
    20: 'microphone',                      // Podcast
    18: 'ionicons-outline--megaphone',     // Announcement
    22: 'file-pdf',                        // Newsletter
    19: 'ionicons-outline--newspaper'      // News Clip
  };

  function updateMediaIconFromSelect() {
    const $typeSelect = $('select[name="acf[field_68841b2e73bb2]"]');
    const selectedType = $typeSelect.val();
    const newIcon = iconMap[selectedType];

    if (!newIcon) return;

    const $iconSelect = $('select[name="acf[field_688d36a575cfd]"]');
    if ($iconSelect.length) {
      $iconSelect.val(newIcon).trigger('change');
    }
  }

  // Initial run after DOM ready
  $(document).ready(function() {
    updateMediaIconFromSelect();

    // Watch for changes to the Type field
    $(document).on('change', 'select[name="acf[field_68841b2e73bb2]"]', function() {
      updateMediaIconFromSelect();
    });
  });

})(jQuery);
