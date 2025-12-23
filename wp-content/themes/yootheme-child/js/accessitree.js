/**
 * ACCESSIBILITY ENHANCEMENTS & FORM REMEDIATION
 * ------------------------------------------------------------
 * Consolidated client-side accessibility fixes.
 * ESLint clean. WCAG-aligned. UIkit-safe.
 */

/* ============================================================
 * 1. Google reCAPTCHA Badge Position Adjustment (Global Scope)
 * ============================================================ */
function adjustBadgePosition() {
  const grecaptchaBadge = document.querySelector('.grecaptcha-badge');
  const footer = document.querySelector('footer');
  if (!grecaptchaBadge || !footer) return;

  const defaultBottom = 14;
  const gapAboveFooter = 16;
  const footerRect = footer.getBoundingClientRect();

  if (footerRect.top < window.innerHeight) {
    const distanceToFooterTop = window.innerHeight - footerRect.top;
    grecaptchaBadge.style.bottom = `${distanceToFooterTop + gapAboveFooter}px`;
  } else {
    grecaptchaBadge.style.bottom = `${defaultBottom}px`;
  }
}

document.addEventListener('scroll', adjustBadgePosition);
window.addEventListener('load', adjustBadgePosition);
window.addEventListener('resize', adjustBadgePosition);

/* ============================================================
 * jQuery Consolidated Enhancements
 * ============================================================ */
jQuery(function ($) {

  /* ==========================================================
   * 2. External + PDF Link Enhancements
   * ========================================================== */
  $('a[href]').each(function () {
    const $link = $(this);
    const href = ($link.attr('href') || '').trim();
    if (!href) return;

    const isPdf = href.toLowerCase().endsWith('.pdf');
    const isExternal =
      href.indexOf('http') === 0
      && this.hostname !== window.location.hostname;

    if (!isExternal && !isPdf) return;

    if (isExternal && !isPdf) {
      $link.attr('target', '_blank');

      const relSet = new Set(($link.attr('rel') || '').split(/\s+/));
      ['noopener', 'noreferrer', 'nofollow'].forEach(v => relSet.add(v));
      $link.attr('rel', Array.from(relSet).join(' ').trim());
    }

    if (isPdf && !isExternal) {
      $link.attr('target', '_blank');
    }

    if ($link.attr('target') === '_blank'
      && !$link.find('.screen-reader-text').length) {
      $link.append('<span class="screen-reader-text">Opens a new tab</span>');
    }

    const $img = $link.find('img');
    const $innerButton = $link.find('.uk-button');
    const $innerContent = $link.find('.el-content');
    const iconHtml =
      '<span style="margin-left:5px;" class="external-link-icon" uk-icon="icon: link-external; ratio: 1.1" aria-hidden="true"></span>';

    if ($img.length) {
      $link
        .css('display', 'inline-block')
        .attr({
          'uk-tooltip': 'title: Opens in a new tab',
          title: 'Opens in a new tab'
        });
      if (window.UIkit && UIkit.tooltip) UIkit.tooltip($link[0]);
      return;
    }

    if ($link.hasClass('uk-button') && !$link.hasClass('uk-icon-button')) {
      $link.addClass('uk-flex-inline uk-flex-center uk-flex-middle').append(iconHtml);
    } else if ($innerButton.length) {
      $innerButton.not('.uk-icon-button')
        .addClass('uk-flex-inline uk-flex-center uk-flex-middle')
        .append(iconHtml);
    } else if ($innerContent.length) {
      $innerContent.append(iconHtml);
    } else if (!$link.hasClass('uk-icon-button')) {
      $link.append(iconHtml);
    }
  });

  /* ==========================================================
   * 3. Logo Landmark Clarification
   * ========================================================== */
  $('a.uk-logo.uk-navbar-item')
    .attr('aria-label', 'Mental Health Colorado, Back to home');

  /* ==========================================================
   * 4. Navigation & Landmark Roles
   * ========================================================== */
  $('.tm-toolbar .widget_nav_menu')
    .attr({
      role: 'navigation',
      'aria-label': 'Toolbar Navigation'
    });

  $('.tm-header .uk-navbar')
    .attr({
      role: 'navigation',
      'aria-label': 'Main Navigation'
    });

  $('.tm-toolbar')
    .attr({
      role: 'toolbar',
      'aria-label': 'Site Tools'
    });

  const $toolbar = $('.tm-toolbar.tm-toolbar-default.uk-visible\\@l');
  if ($toolbar.length && !$toolbar.closest('[role="complementary"]').length) {
    $toolbar.wrap('<div role="complementary" aria-label="Site Tools Toolbar"></div>');
  }

  $('#tm-dialog-mobile .widget_nav_menu')
    .attr({
      role: 'dialog',
      'aria-label': 'Mobile Navigation Menu'
    });

  /* ==========================================================
   * 5. Navigation Parent Item Role Normalization
   * ========================================================== */
  (function () {
    function normalize(root) {
      const $root = root ? $(root) : $(document);
      $root.find(
        '.tm-toolbar a[aria-haspopup="true"],'
        + '.tm-header a[aria-haspopup="true"],'
        + '.tm-header-mobile a[aria-haspopup="true"]'
      ).each(function () {
        const $item = $(this);
        if ($item.attr('href')) {
          $item.removeAttr('role');
        } else {
          $item.attr('role', 'menuitem');
        }
      });
    }

    normalize();
    setTimeout(normalize, 800);
    $(window).on('load', normalize);

    const observer = new MutationObserver(mutations => {
      mutations.forEach(m => {
        if (m.type === 'childList' && m.addedNodes.length) {
          normalize(m.target);
        }
      });
    });

    observer.observe(document.documentElement, {
      childList: true,
      subtree: true
    });
  })();

  /* ==========================================================
   * 6. Social Link Semantics
   * ========================================================== */
  $('.social-buttons').each(function () {
    const $list = $(this).find('ul').first();
    if (!$list.length) return;

    $list.attr({
      role: 'list',
      'aria-label': 'Follow Jefferson Center on social media'
    });

    $list.find('li').each(function () {
      const $li = $(this);
      const hasContent = $li.find('a, svg, img').length || $.trim($li.text()).length;
      if (!hasContent) $li.remove();
      else $li.attr('role', 'listitem');
    });

    $list.find('a').each(function () {
      const $a = $(this);
      if (!$a.attr('aria-label')) {
        $a.attr('aria-label', 'Link to ' + ($a.attr('href') || 'social media'));
      }

      if ($a.attr('target') === '_blank') {
        $a.attr({
          'uk-tooltip': 'title: Opens in a new tab',
          title: 'Opens in a new tab'
        });
        if (window.UIkit && UIkit.tooltip) UIkit.tooltip($a[0]);
      }
    });
  });

  /* ==========================================================
   * 7. Search Field Remediation (SC 3.3.2)
   * ========================================================== */
  function remediateSearchField(selector, labelText, errorId) {
    const $input = $(selector);
    if (!$input.length) return;

    const $form = $input.closest('form').attr('novalidate', '');
    $input.removeAttr('required').attr({
      'aria-label': labelText,
      autocomplete: 'off'
    });

    $form.on('submit', function (e) {
      if ($input.val().trim()) return;
      e.preventDefault();

      if (!$('#' + errorId).length) {
        $input.after(
          `<div id="${errorId}" class="search-error-message" role="alert">
            Error: Search term is required.
          </div>`
        );
      }

      $input.attr({
        'aria-invalid': 'true',
        'aria-describedby': errorId
      }).focus();
    });

    $input.on('input', function () {
      if (!$input.val().trim()) return;
      $('#' + errorId).remove();
      $input.removeAttr('aria-invalid aria-describedby');
    });
  }

  remediateSearchField(
    '#custom-search input[type="search"]',
    'Media Search',
    'media-search-error'
  );

  /* ==========================================================
   * 8. Media Search Context Injection
   * ========================================================== */
  (function () {
    const $form = $('#custom-search');
    if (!$form.length) return;

    $form.on('submit', function () {
      $form.attr('action', '/');

      const match = window.location.pathname.match(/\/media\/([^\/]+)\/?$/);
      if (match && match[1] && match[1] !== 'media') {
        if (!$form.find('input[name="media_term"]').length) {
          $('<input>', {
            type: 'hidden',
            name: 'media_term',
            value: match[1]
          }).appendTo($form);
        }
      }

      $form.find(
        'input[name="_wpnonce_yooessentials"], input[name="formid"]'
      ).remove();
    });
  })();

  /* ==========================================================
   * 9. Decorative Required Indicator Silence
   * ========================================================== */
  $('label span, legend span').each(function () {
    const txt = $.trim($(this).text());
    if (txt === '*' || txt === '') {
      $(this).attr('aria-hidden', 'true');
    }
  });

  /* ==========================================================
   * 10. General Form Remediation (incl. checkbox/radio groups)
   * ========================================================== */
  const isValidEmail = email =>
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

  $('form').not('[role="search"]').each(function () {
    const $form = $(this).attr('novalidate', '');

    $form.on('submit', function (e) {
      let firstError = null;
      const validatedGroups = {};

      $form.find('input, textarea, select').each(function () {
        const $f = $(this);
        const type = $f.attr('type');
        const val = ($f.val() || '').toString().trim();
        const required = $f.prop('required');
        const name = $f.attr('name');

        const id = $f.attr('id') || `gen-${Math.random().toString(36).slice(2)}`;
        $f.attr('id', id);

        // Checkbox / Radio Group Handling
        if ((type === 'checkbox' || type === 'radio') && name) {
          if (validatedGroups[name]) return;
          validatedGroups[name] = true;

          const $group = $form.find(`input[name="${name}"]`);
          const isChecked = $group.filter(':checked').length > 0;
          const $fieldset = $group.closest('fieldset');

          const isGroupRequired =
            $group.filter('[required], [aria-required="true"]').length > 0
            || ($fieldset.length && /\*/.test($fieldset.find('legend').text()));

          if (isGroupRequired && !isChecked) {
            e.preventDefault();

            let labelText =
              $fieldset.find('legend').text()
              || $('label[for="' + id + '"]').text()
              || $group.first().attr('aria-label')
              || 'This field';

            labelText = labelText
              .replace('*', '')
              .replace(/\s*\(.*?\)/g, '')
              .trim();

            const errorId = `${name.replace(/\[|\]/g, '')}-error`;
            $('#' + errorId).remove();

            const $target = $fieldset.length ? $fieldset : $group.last().parent();

            $target.after(
              `<div id="${errorId}" class="general-error-message" role="alert">
        Error: ${labelText} is required.
      </div>`
            );

            $group.attr({
              'aria-invalid': 'true',
              'aria-describedby': errorId
            });

            if (!firstError) firstError = $group.first();
          }

          return;
        }


        const errorId = `${id}-error`;
        $('#' + errorId).remove();
        $f.removeAttr('aria-invalid aria-describedby')
          .removeClass('input-error-state');

        let labelText =
          $('label[for="' + id + '"]').text()
          || $f.attr('aria-label')
          || $f.attr('placeholder')
          || 'This field';

        labelText = labelText.replace('*', '').replace(/\s*\(.*?\)/g, '').trim();

        let error = '';
        if (required && !val) {
          error = `Error: ${labelText} is required.`;
        }
        if (type === 'email' && val && !isValidEmail(val)) {
          error = `Error: ${labelText} must be a valid email address.`;
        }

        if (error) {
          e.preventDefault();
          if (!firstError) firstError = $f;

          $f.after(
              `<div id="${errorId}" class="general-error-message" role="alert">${error}</div>`
            )
            .attr({
              'aria-invalid': 'true',
              'aria-describedby': errorId
            })
            .addClass('input-error-state');
        }
      });

      if (firstError) firstError.focus();
    });

    // Persistence cleanup
    $form.on('input change', 'input, textarea, select', function () {
      const $f = $(this);
      const type = $f.attr('type');
      const name = $f.attr('name');
      const id = $f.attr('id');

      let errorId;

      if ((type === 'checkbox' || type === 'radio') && name) {
        errorId = `${name.replace(/\[|\]/g, '')}-error`;
        $('#' + errorId).remove();
        $form.find(`input[name="${name}"]`)
          .removeAttr('aria-invalid aria-describedby');
        return;
      }

      errorId = `${id}-error`;
      $('#' + errorId).remove();
      $f.removeAttr('aria-invalid aria-describedby')
        .removeClass('input-error-state');
    });
  });

  /**
   * DJ Popup Modal Close Button Handler (Scoped)
   * ------------------------------------------------------------
   * - Applies ONLY to DJ Popup modals
   * - Does not interfere with UIkit-native modals
   * - Prevents blank modal issues on reopen
   */

  (function ($) {

    function bindDjPopupCloseButtons(context) {
      const $root = context ? $(context) : $(document);

      // Target ONLY DJ Popup modals
      $root.find('.uk-modal[id^="dj-popup-"], .djpopup-popup').each(function () {
        const $modal = $(this);
        const modalId = $modal.attr('id');
        if (!modalId) return;

        $modal.find('button[aria-label="Close"], button.uk-button-secondary')
          .each(function () {
            const $btn = $(this);

            if ($btn.data('djpopup-bound')) return;
            $btn.data('djpopup-bound', true);

            $btn.on('click', function (e) {
              e.preventDefault();
              e.stopPropagation();

              if (window.UIkit && UIkit.modal) {
                UIkit.modal('#' + modalId).hide();
              }
            });
          });
      });
    }

    // Initial run
    bindDjPopupCloseButtons();

    // DJ Popup + UIkit lifecycle hook
    if (window.UIkit && UIkit.util) {
      UIkit.util.on(document, 'shown', function (e) {
        bindDjPopupCloseButtons(e.target);
      });
    }

    // MutationObserver for injected DJ Popup modals
    const observer = new MutationObserver(mutations => {
      mutations.forEach(mutation => {
        mutation.addedNodes.forEach(node => {
          if (
            node.nodeType === 1
            && (node.classList.contains('djpopup-popup')
              || (node.id && node.id.indexOf('dj-popup-') === 0))
          ) {
            bindDjPopupCloseButtons(node);
          }
        });
      });
    });

    observer.observe(document.documentElement, {
      childList: true,
      subtree: true
    });

  })(jQuery);
});
