<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/form-elements/form_hcaptcha/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_form_hcaptcha', 
  'title' => 'hCaptcha', 
  'group' => 'Form Essentials', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_hcaptcha/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_hcaptcha/icon.svg', $file), 
  'width' => 500, 
  'element' => true, 
  'submittable' => true, 
  'defaults' => [
    'control_type' => 'checkbox', 
    'control_theme' => 'light', 
    'control_size' => 'normal', 
    'control_threshold' => '0.5', 
    'control_compliance' => 'This site is protected by hCaptcha and its <a href="https://hcaptcha.com/privacy">Privacy Policy</a> and <a href="https://hcaptcha.com/terms">Terms of Service</a> apply.'
  ], 
  'placeholder' => [], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file)
  ], 
  'fields' => [
    'control_type' => [
      'label' => 'Type', 
      'type' => 'select', 
      'options' => [
        'Checkbox' => 'checkbox', 
        'Invisible' => 'invisible'
      ]
    ], 
    'control_theme' => [
      'label' => 'Theme', 
      'type' => 'select', 
      'options' => [
        'Light' => 'light', 
        'Dark' => 'dark'
      ]
    ], 
    'control_size' => [
      'label' => 'Size', 
      'type' => 'select', 
      'options' => [
        'Normal' => 'normal', 
        'Compact' => 'compact'
      ], 
      'show' => 'control_type === \'checkbox\''
    ], 
    'control_site_key' => [
      'label' => 'Site Key', 
      'type' => 'text'
    ], 
    'control_secret_key' => [
      'label' => 'Secret Key', 
      'type' => 'text'
    ], 
    'control_threshold' => [
      'label' => 'Score Threshold (Enterprise only)', 
      'description' => 'The threshold <b>over</b> which to consider the submitter a bot, from 0.0 (no risk) to 1.0 (confirmed threat).'
    ], 
    'control_compliance' => [
      'label' => 'Compliance', 
      'description' => 'A legally required text to comply with online privacy laws. Make sure it links to hCaptcha <a href="https://hcaptcha.com/privacy">Privacy Policy</a> and <a href="https://hcaptcha.com/terms">Terms of Service</a>.', 
      'show' => 'control_type === \'invisible\''
    ], 
    'control_label' => $config->get('yooessentials.form.fields.control_label'), 
    'control_error_message' => $config->get('yooessentials.form.fields.control_error_message'), 
    'position' => $config->get('builder.position'), 
    'position_left' => $config->get('builder.position_left'), 
    'position_right' => $config->get('builder.position_right'), 
    'position_top' => $config->get('builder.position_top'), 
    'position_bottom' => $config->get('builder.position_bottom'), 
    'position_z_index' => $config->get('builder.position_z_index'), 
    'margin' => $config->get('builder.margin'), 
    'margin_remove_top' => $config->get('builder.margin_remove_top'), 
    'margin_remove_bottom' => $config->get('builder.margin_remove_bottom'), 
    'maxwidth' => $config->get('builder.maxwidth'), 
    'maxwidth_breakpoint' => $config->get('builder.maxwidth_breakpoint'), 
    'block_align' => $config->get('builder.block_align'), 
    'block_align_breakpoint' => $config->get('builder.block_align_breakpoint'), 
    'block_align_fallback' => $config->get('builder.block_align_fallback'), 
    'text_align' => $config->get('builder.text_align'), 
    'text_align_breakpoint' => $config->get('builder.text_align_breakpoint'), 
    'text_align_fallback' => $config->get('builder.text_align_fallback'), 
    'animation' => $config->get('builder.animation'), 
    '_parallax_button' => $config->get('builder._parallax_button'), 
    'visibility' => $config->get('builder.visibility'), 
    'name' => $config->get('builder.name'), 
    'status' => $config->get('builder.status'), 
    'id' => $config->get('builder.id'), 
    'class' => $config->get('builder.cls'), 
    'attributes' => $config->get('builder.attrs'), 
    'source' => $config->get('builder.source'), 
    'css' => [
      'label' => 'CSS', 
      'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: <code>.el-element</code>, <code>.el-link</code>', 
      'type' => 'editor', 
      'editor' => 'code', 
      'mode' => 'css', 
      'attrs' => [
        'debounce' => 500
      ]
    ]
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['control_type', [
              'description' => 'Set the keys obtained from <a href="https://dashboard.hcaptcha.com" target="_blank">hCaptcha Dashboard</a>.', 
              'name' => '_captcha_keys', 
              'type' => 'grid', 
              'width' => '1-2', 
              'fields' => ['control_site_key', 'control_secret_key']
            ], 'control_label', 'control_theme', 'control_size', 'control_threshold', 'control_compliance', 'control_error_message']
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ]
];
