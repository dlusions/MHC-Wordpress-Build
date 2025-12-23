// JavaScript Document
(function($) {
  const repeaterKey = 'field_687e7dbcfbbea';
  const eventKey = 'field_687ac9df0907c';
  const levelKey = 'field_687acc5b5f82c';

  function setupSupporterLevel($row) {
    if ($row.hasClass('acf-clone') || $row.data('enhanced')) return;
    $row.data('enhanced', true);

    const $eventField = $row.find(`[data-key="${eventKey}"]`);
    const $eventSelect = $eventField.find('select');

    const $levelField = $row.find(`[data-key="${levelKey}"]`);
    const $levelInputWrapper = $levelField.find('.acf-input');
    const $levelInput = $levelInputWrapper.find('input[type="text"]');

    if (!$levelInput.length || !$eventSelect.length) return;

    $levelInput.hide();

    let $levelSelect = $levelInputWrapper.find('select.supporter-level-select');
    if (!$levelSelect.length) {
      $levelSelect = $('<select class="supporter-level-select" style="width:100%; margin-top:4px;"><option value="">Select a level</option></select>');
      $levelInputWrapper.append($levelSelect);
    }

    $levelSelect.off('change').on('change', function () {
      $levelInput.val($(this).val()).trigger('change');
    });

    function loadLevels(eventID, preselectValue = '') {
      $levelSelect.empty().append('<option>Loading levels…</option>');
      $levelSelect.prop('disabled', true);

      if (!eventID) {
        $levelSelect.empty().append('<option value="">Select a level</option>');
        $levelInput.val('').trigger('change');
        return;
      }

      $.post(acfSupporterLevels.ajax_url, {
        action: 'get_supporter_levels',
        event_id: eventID,
        nonce: acfSupporterLevels.nonce
      }, function (res) {
        $levelSelect.empty();

        if (res.success && Array.isArray(res.data)) {
          $levelSelect.append('<option value="">Select a level</option>');
          res.data.forEach(level => {
            const $opt = $('<option>').val(level).text(level);
            if (level === preselectValue) $opt.attr('selected', true);
            $levelSelect.append($opt);
          });
          $levelSelect.prop('disabled', false);
        } else {
          $levelSelect.append('<option value="">No levels found</option>');
        }
      });
    }

    // Always attach listener
    $eventSelect.off('change.supporter').on('change.supporter', function () {
      const eventID = $(this).val();
      $levelInput.val('');
      loadLevels(eventID);
    });

    // If event already selected (e.g. on append or edit), trigger loading manually
    const initialEventID = $eventSelect.val();
    const initialSupporterLevel = $levelInput.val();
    if (initialEventID) {
      loadLevels(initialEventID, initialSupporterLevel);
    }
  }

  function initAllRows($scope) {
    const $container = $scope || $(document);
    $container.find(`.acf-field[data-key="${repeaterKey}"] .acf-row:not(.acf-clone)`).each(function () {
      setupSupporterLevel($(this));
    });
  }

  acf.addAction('ready', function ($el) {
    initAllRows($el || $(document));
  });

  acf.addAction('append', function ($el) {
    initAllRows($el || $(document));
  });
})(jQuery);
