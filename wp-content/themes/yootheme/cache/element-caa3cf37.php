<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/form-elements/form_upload/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_form_upload', 
  'title' => 'Upload', 
  'group' => 'Form Essentials', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_upload/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_upload/icon.svg', $file), 
  'width' => 500, 
  'element' => true, 
  'submittable' => true, 
  'defaults' => [
    'margin' => 'default', 
    'layout' => 'default', 
    'button_style' => 'default', 
    'button_icon_align' => 'left', 
    'content' => 'Select File', 
    'control_unique_filenames' => true, 
    'control_id_inherit' => true
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file)
  ], 
  'fields' => [
    'layout' => [
      'label' => 'Display', 
      'text' => 'Choose the output layout.', 
      'type' => 'select', 
      'options' => [
        'Button & Input' => 'default', 
        'Button' => 'button', 
        'Link' => 'link', 
        'Native' => 'native'
      ]
    ], 
    'content' => [
      'label' => 'Text', 
      'description' => 'Set the text that will be rendered in the Button or Link.', 
      'show' => 'layout !== \'native\''
    ], 
    'control_upload_path' => [
      'label' => 'Upload To', 
      'description' => 'Set the path to the local folder where the submitted files will be uploaded to. Use the <code>~/</code> prefix to reference the site root, <code>~theme/cache</code> as a temporal folder if files don\'t need to be persisted, or an absolute path from the server root to store the files outside the public domain, which is recommended.', 
      'type' => 'link', 
      'source' => true
    ], 
    'control_upload_filename' => [
      'label' => 'Filename', 
      'description' => 'Optionally overwrite the name of the submitted file.', 
      'source' => true
    ], 
    'control_multiple' => [
      'label' => 'Multiple', 
      'text' => 'Allow multiple uploads', 
      'type' => 'checkbox'
    ], 
    'control_unique_filenames' => [
      'label' => 'Unique Filenames', 
      'text' => 'Avoid filename collisions', 
      'type' => 'checkbox'
    ], 
    'control_mimetypes' => [
      'label' => 'MimeTypes', 
      'description' => 'Comma-separated list of allowed mimetypes, e.g: <code>image/png</code>, <code>video/*</code>.', 
      'source' => true, 
      'attrs' => [
        'placeholder' => 'Any MimeType'
      ]
    ], 
    'control_extensions' => [
      'label' => 'Extensions', 
      'description' => 'Comma-separated list of allowed extensions, e.g: <code>png</code>, <code>jpg</code>, <code>gif</code>.', 
      'source' => true, 
      'attrs' => [
        'placeholder' => 'Any Extension'
      ]
    ], 
    'control_min_filesize' => [
      'source' => true, 
      'attrs' => [
        'placeholder' => 'No Min Size'
      ]
    ], 
    'control_max_filesize' => [
      'source' => true, 
      'attrs' => [
        'placeholder' => 'No Max Size'
      ]
    ], 
    'button_size' => [
      'label' => 'Size', 
      'type' => 'select', 
      'options' => [
        'Small' => 'small', 
        'Default' => '', 
        'Large' => 'large'
      ]
    ], 
    'button_fullwidth' => [
      'type' => 'checkbox', 
      'label' => 'Fullwidth', 
      'text' => 'Expand width.'
    ], 
    'button_style' => [
      'label' => 'Style', 
      'description' => 'Set the button style.', 
      'type' => 'select', 
      'options' => [
        'Default' => 'default', 
        'Primary' => 'primary', 
        'Secondary' => 'secondary', 
        'Danger' => 'danger', 
        'Text' => 'text', 
        'Link' => '', 
        'Link Muted' => 'link-muted', 
        'Link Text' => 'link-text'
      ]
    ], 
    'control_attrs' => $config->get('yooessentials.form.fields.control_attrs'), 
    'control_name' => $config->get('yooessentials.form.fields.control_name'), 
    'control_label' => $config->get('yooessentials.form.fields.control_label'), 
    'control_id' => $config->get('yooessentials.form.fields.control_id'), 
    'control_id_inherit' => $config->get('yooessentials.form.fields.control_id_inherit'), 
    'button_icon' => $config->get('yooessentials.form.fields.control_icon'), 
    'button_icon_align' => $config->get('yooessentials.form.fields.control_icon_align'), 
    'control_icon' => $config->get('yooessentials.form.fields.control_icon'), 
    'control_icon_align' => $config->get('yooessentials.form.fields.control_icon_align'), 
    'control_required' => $config->get('yooessentials.form.fields.control_required'), 
    'control_error_message' => $config->get('yooessentials.form.fields.control_error_message'), 
    'control_size' => $config->get('yooessentials.form.fields.control_size'), 
    'control_width' => $config->get('yooessentials.form.fields.control_width'), 
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
    'transform' => $config->get('builder.transform'), 
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
          'fields' => ['control_name', 'control_upload_path', 'control_upload_filename', 'control_required', 'control_mimetypes', 'control_extensions', [
              'label' => 'Min/Max File Size', 
              'description' => 'The min and/or max allowed file size in B, KB, MB, GB, TB, PB, EB, ZB and YB units, e.g. 10MB.', 
              'name' => '_file_size', 
              'type' => 'grid', 
              'width' => '1-2', 
              'fields' => ['control_min_filesize', 'control_max_filesize']
            ], 'control_error_message']
        ], [
          'title' => 'Field', 
          'fields' => ['control_label', 'control_id', 'control_id_inherit', 'control_attrs']
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'Field', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['control_multiple', 'control_unique_filenames']
            ], [
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['layout', 'content']
            ], [
              'label' => 'Input', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['control_size', 'control_width', 'control_icon', 'control_icon_align'], 
              'show' => 'layout === \'default\''
            ], [
              'label' => 'Button', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['button_fullwidth', 'button_style', 'button_size', 'button_icon', 'button_icon_align'], 
              'show' => 'layout === \'default\' || layout === \'button\''
            ], [
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ]
];
