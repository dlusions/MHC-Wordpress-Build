<?php /**
 * @package     [FS] Switcher Pro for YOOtheme Pro
 * @subpackage  fs-switcher
 *
 * @author      Flart Studio https://flart.studio
 * @copyright   Copyright (C) 2026 Flart Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see https://flart.studio/license
 * @link        https://flart.studio/yootheme-pro/switcher
 * @build       (FLART_BUILD_NUMBER)
 */

/** @noinspection DuplicatedCode */

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $builder */
/** @var $children */
/** @var $element */
/** @var $props */
/** @var $helper */
/** @var $i */

// Helper: Determine modal configuration
$isWrapAll = $element['sublayout_mode'] === 'modal' && $element['sublayout_modal_group'] === 'all';
$isWrapEach = $element['sublayout_modal_group'] === 'each';
$isMixedCustom = $element['sublayout_mode'] === 'mixed' && !empty($element['sublayout_modal_group_custom']);

// Helper: Sanitize ID for HTML
$sanitizeId = static fn($id) => preg_replace('/^(\d)/', 'modal-$1', preg_replace('/[^\w\-]+/', '-', trim(strip_tags($id))));

// Helper: Generate Modal ID
$generateModalId = function () use ($props, $children, $i, $isWrapAll, $isWrapEach, $isMixedCustom, $sanitizeId) {
    // Get base ID or generate default
    $baseId = $props['link_modal_id'] ?: "fs-switcher-pro-modal-{$this->uid()}";
    $baseId = $sanitizeId($baseId);

    // For wrap all mode, return the base ID
    if ($isWrapAll) {
        return $baseId;
    }

    // For each/mixed mode, add suffix
    if ($isWrapEach || $isMixedCustom) {
        // Try to get existing ID from a child or use index
        $suffix = $children[$i]->children[0]->attrs['id']
            ?? $children[$i]->attrs['id']
            ?? 'sublayout-' . ($i + 1);

        $suffix = $sanitizeId($suffix);

        return "$baseId-$suffix";
    }

    return $baseId;
};

// Generate Modal ID
$modal_id = $generateModalId();

// Helper: Determine if toggle is enabled
$isToggle = $props['link_toggle'] ?? false;
if (!empty($helper) && !$isToggle) {
    foreach ($helper as $positions) {
        foreach ($positions as $fieldsets) {
            foreach ($fieldsets as $f => $fieldset) {
                if ($fieldset['link'] !== '' && !empty($props["link_{$f}_toggle"])) {
                    $isToggle = true;
                    break 3;
                }
            }
        }
    }
}

// Modal ID Helper Alert
if ($isToggle && $props['link_modal_id_helper'] && (!$props['link_toggle_modal_integration'] || !$isWrapAll)) {
    $alert = $this->el('div', [
        'class' => 'uk-alert-warning uk-text-small',
        'uk-alert' => true,
        'data-nosnippet' => true,
    ]);

    $id = htmlspecialchars($modal_id, ENT_QUOTES, 'UTF-8');
    echo $alert($element,
        "The <strong>Modal ID</strong> is <strong>$id</strong>, " .
        "which can be connected with the link <strong>#$id</strong>"
    );
}

// Modal
$modal = $this->el('div', [
    'id' => $modal_id,
    'class' => [
        'fs-switcher__modal',
        'uk-modal-container {@!sublayout_modal_width}{@!sublayout_modal_full}',
        'uk-modal-full {@sublayout_modal_full}',
        'uk-flex-top', // Needed for vertical margin in IE11
    ],
    'uk-modal' => $element['sublayout_modal_stack'] ? ['stack: 1'] : true,
]);

// Container
$container = $this->el('div', [
    'class' => ['fs-switcher__modal-container'],
    'style' => [
        '[max-width: 100%; {@sublayout_modal_width}{@!sublayout_modal_full}]',
        '[width: {sublayout_modal_width}px; {@sublayout_modal_width}{@!sublayout_modal_full}]',
        '[height: {sublayout_modal_height}px; {@sublayout_modal_height}{@!sublayout_modal_full}]',
    ],
    'uk-overflow-auto' => $element['sublayout_modal_height'],
]);

// Close
$close = $this->el('button', [
    'class' => [
        'fs-switcher__modal-close',
        'uk-modal-close-{sublayout_modal_close_position} {@!sublayout_modal_full}',
        'uk-modal-close-full uk-close-large {@sublayout_modal_full}',
        'uk-close-large {@sublayout_modal_close_size}{@!sublayout_modal_full}',
    ],
    'type' => 'button',
    'uk-close' => true,
]);

// Sublayout
$sublayout = $this->el('div', [
    'class' => [
        'fs-switcher__item-sublayout uk-margin',

        // Deprecated remove in v.2.0.0
        'el-sublayout',
    ],
]);

// Determine content to render
$renderContent = match (true) {
    $isWrapAll => $builder->render($children),
    $isWrapEach || $isMixedCustom => $builder->render($children[$i]),
    default => ''
};

// Modal header (only for wrap all modes)
$showHeader = $isWrapAll && $element['sublayout_modal_header'] && $props['link_modal_header_text'];
?>

<?= $modal($element) ?>
    <div class="fs-switcher__modal-dialog uk-modal-dialog uk-width-auto uk-margin-auto-vertical">
        <?= $close($element, '') ?>
        <?= $container($element) ?>
        <?php if ($showHeader) : ?>
            <div class="fs-switcher__modal-header uk-modal-header">
                <h2 class="uk-modal-title"><?= $props['link_modal_header_text'] ?></h2>
            </div>
        <?php endif ?>
        <div class="fs-switcher__modal-body uk-modal-body">
            <?= $sublayout($element, $renderContent) ?>
        </div>
        <?= $container->end() ?>
    </div>
<?= $modal->end() ?>