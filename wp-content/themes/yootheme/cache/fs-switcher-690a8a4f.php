<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/fs-switcher/modules/config/fs-switcher.json

return [
  'grid_position' => [
    'label' => 'Position', 
    'description' => 'Select the grid position relative to the content or image cell.', 
    'default' => 'below-content', 
    'type' => 'select', 
    'options' => [
      'Above Content' => 'above-content', 
      'Below Content' => 'below-content', 
      'Item Top' => 'item-top', 
      'Item Bottom' => 'item-bottom', 
      'Item Image Cell Top' => 'item-image-cell-top', 
      'Item Image Cell Bottom' => 'item-image-cell-bottom'
    ], 
    'source' => true
  ], 
  'grid_column_gap' => [
    'label' => 'Column Gap', 
    'description' => 'Set the space between grid columns.', 
    'type' => 'select', 
    'options' => [
      'Small' => 'small', 
      'Medium' => 'medium', 
      'Default' => '', 
      'Large' => 'large', 
      'None' => 'collapse'
    ]
  ], 
  'grid_row_gap' => [
    'label' => 'Row Gap', 
    'description' => 'Set the space between grid rows.', 
    'type' => 'select', 
    'options' => [
      'Small' => 'small', 
      'Medium' => 'medium', 
      'Default' => '', 
      'Large' => 'large', 
      'None' => 'collapse'
    ]
  ], 
  'grid_divider_top' => [
    'label' => 'Divider', 
    'type' => 'checkbox', 
    'text' => 'Show grid divider'
  ], 
  'grid_divider' => [
    'type' => 'checkbox', 
    'text' => 'Show inner dividers'
  ], 
  'grid_column_align' => [
    'label' => 'Alignment', 
    'type' => 'checkbox', 
    'text' => 'Center columns'
  ], 
  'grid_row_align' => [
    'type' => 'checkbox', 
    'text' => 'Center rows'
  ], 
  'grid_text_align' => [
    'label' => 'Text Alignment', 
    'description' => 'Choose how text should be aligned within the grid.', 
    'type' => 'select', 
    'options' => [
      'None' => '', 
      'Left' => 'left', 
      'Center' => 'center', 
      'Right' => 'right'
    ]
  ], 
  'grid_breakpoint' => [
    'label' => 'Grid Breakpoint', 
    'description' => 'Set the breakpoint from which grid items will stack.', 
    'type' => 'select', 
    'default' => 'm', 
    'options' => [
      'Always' => '', 
      'Small (Phone Landscape)' => 's', 
      'Medium (Tablet Landscape)' => 'm', 
      'Large (Desktop)' => 'l', 
      'X-Large (Large Screens)' => 'xl'
    ]
  ], 
  'grid_text_align_fallback' => [
    'label' => 'Text Alignment Fallback', 
    'description' => 'Set a fallback alignment for smaller screens below the selected breakpoint.', 
    'type' => 'select', 
    'options' => [
      'None' => '', 
      'Left' => 'left', 
      'Center' => 'center', 
      'Right' => 'right'
    ]
  ], 
  'grid_margin' => [
    'label' => 'Margin', 
    'description' => 'Adjust the vertical margin. It’s applied automatically to the top or bottom based on the selected grid position.', 
    'type' => 'select', 
    'options' => [
      'Small' => 'small', 
      'Default' => '', 
      'Medium' => 'medium', 
      'Large' => 'large', 
      'X-Large' => 'xlarge', 
      'None' => 'remove'
    ]
  ], 
  'margin' => [
    'label' => 'Margin Top', 
    'description' => 'Set the top margin. Only applies if this field directly follows another content field.', 
    'type' => 'select', 
    'options' => [
      'Small' => 'small', 
      'Default' => '', 
      'Medium' => 'medium', 
      'Large' => 'large', 
      'X-Large' => 'xlarge', 
      'None' => 'remove'
    ]
  ], 
  'visibility' => [
    'label' => 'Visibility', 
    'description' => 'Control the element’s visibility across device widths. Hidden elements will also hide their parent columns, rows, or sections when empty.', 
    'type' => 'select', 
    'options' => [
      'Always' => '', 
      'Visible Small (Phone Landscape)' => 'uk-visible@s', 
      'Visible Medium (Tablet Landscape)' => 'uk-visible@m', 
      'Visible Large (Desktop)' => 'uk-visible@l', 
      'Visible X-Large (Large Screens)' => 'uk-visible@xl', 
      'Hidden Small (Phone Landscape)' => 'uk-hidden@s', 
      'Hidden Medium (Tablet Landscape)' => 'uk-hidden@m', 
      'Hidden Large (Desktop)' => 'uk-hidden@l', 
      'Hidden X-Large (Large Screens)' => 'uk-hidden@xl'
    ]
  ], 
  'grid_columns' => [
    'label' => '__DYNAMIC__', 
    'description' => 'Set the number of grid columns for each breakpoint. <i>Inherit</i> refers to the number of columns on the next smaller screen size. <i>Auto</i> expands the columns to the width of their items filling the rows accordingly.', 
    'type' => 'select', 
    'options' => [
      '1 Column' => '1-1', 
      '2 Columns' => '1-2', 
      '3 Columns' => '1-3', 
      '4 Columns' => '1-4', 
      '5 Columns' => '1-5', 
      '6 Columns' => '1-6', 
      'Expand' => 'expand', 
      'Auto' => 'auto'
    ]
  ], 
  'grid_slider' => [
    'label' => 'Slider', 
    'type' => 'checkbox', 
    'description' => 'Display the grid as a slider. The same slider settings are used across all custom grids.', 
    'text' => 'Enable the slider'
  ], 
  'grid_slider_panel' => [
    'type' => 'button-panel', 
    'panel' => '_fs_switcher_pro_grid_slider_settings', 
    'text' => 'Slider Settings'
  ], 
  'panel_style' => [
    'label' => 'Style', 
    'description' => 'Select one of the boxed card or tile styles or a blank panel.', 
    'type' => 'select', 
    'options' => [
      'None' => '', 
      'Card Default' => 'card-default', 
      'Card Primary' => 'card-primary', 
      'Card Secondary' => 'card-secondary', 
      'Card Hover' => 'card-hover', 
      'Tile Default' => 'tile-default', 
      'Tile Muted' => 'tile-muted', 
      'Tile Primary' => 'tile-primary', 
      'Tile Secondary' => 'tile-secondary', 
      'Tile Checked' => 'tile-checked'
    ]
  ], 
  'panel_card_offset' => [
    'type' => 'checkbox', 
    'text' => 'Add clipping offset'
  ], 
  'panel_padding' => [
    'label' => 'Padding', 
    'description' => 'Adjust the inner padding of the panel.', 
    'type' => 'select', 
    'options' => [
      'None' => '', 
      'Small' => 'small', 
      'Default' => 'default', 
      'Large' => 'large'
    ]
  ], 
  'image_loading' => [
    'label' => 'Loading', 
    'description' => 'Load images eagerly instead of lazy loading for those visible in the initial viewport.', 
    'type' => 'checkbox', 
    'text' => 'Enable eager loading'
  ], 
  'image_fetchpriority' => [
    'label' => 'Fetch Priority', 
    'description' => 'Prioritize loading of important images using the fetchpriority attribute.', 
    'type' => 'checkbox', 
    'text' => 'Set high fetch priority'
  ], 
  'image_decoding' => [
    'label' => 'Decoding', 
    'description' => 'Decode images asynchronously to prevent rendering delays for other content.', 
    'type' => 'checkbox', 
    'text' => 'Enable async decoding'
  ], 
  'cache_disable' => [
    'label' => 'Cache', 
    'description' => 'Disable image caching and thumbnail generation.', 
    'type' => 'checkbox', 
    'text' => 'Disable image cache'
  ], 
  'image_width' => [
    'type' => 'number', 
    'attrs' => [
      'placeholder' => 'auto'
    ]
  ], 
  'image_height' => [
    'type' => 'number', 
    'attrs' => [
      'placeholder' => 'auto'
    ]
  ], 
  'icon_width' => [
    'attrs' => [
      'placeholder' => 'auto'
    ], 
    'type' => 'number'
  ], 
  'image_border' => [
    'label' => 'Border', 
    'description' => 'Choose a border style for the image.', 
    'type' => 'select', 
    'options' => [
      'None' => '', 
      'Rounded' => 'rounded', 
      'Circle' => 'circle', 
      'Pill' => 'pill'
    ]
  ], 
  'icon_color' => [
    'label' => 'Icon Color', 
    'description' => 'Choose a color style for the icon.', 
    'type' => 'select', 
    'options' => [
      'None' => '', 
      'Muted' => 'muted', 
      'Emphasis' => 'emphasis', 
      'Primary' => 'primary', 
      'Secondary' => 'secondary', 
      'Success' => 'success', 
      'Warning' => 'warning', 
      'Danger' => 'danger'
    ]
  ], 
  'image_align' => [
    'label' => 'Alignment', 
    'description' => 'Align the image to the top, bottom, left, right, or between the title and content.', 
    'default' => 'top', 
    'type' => 'select', 
    'options' => [
      'Top' => 'top', 
      'Bottom' => 'bottom', 
      'Left' => 'left', 
      'Right' => 'right'
    ]
  ], 
  'focal_point' => [
    'label' => 'Focal Point', 
    'description' => 'Set a focal point to adjust the image focus when cropping.', 
    'type' => 'select', 
    'options' => [
      'Top Left' => 'top-left', 
      'Top Center' => 'top-center', 
      'Top Right' => 'top-right', 
      'Center Left' => 'center-left', 
      'Center Center' => '', 
      'Center Right' => 'center-right', 
      'Bottom Left' => 'bottom-left', 
      'Bottom Center' => 'bottom-center', 
      'Bottom Right' => 'bottom-right'
    ], 
    'source' => true
  ], 
  'grid_width' => [
    'label' => 'Grid Width', 
    'description' => 'Define the image width within the grid. Choose between percentage, fixed, or auto sizes.', 
    'type' => 'select', 
    'default' => '1-2', 
    'options' => [
      'Auto' => 'auto', 
      '80%' => '4-5', 
      '75%' => '3-4', 
      '66%' => '2-3', 
      '60%' => '3-5', 
      '50%' => '1-2', 
      '40%' => '2-5', 
      '33%' => '1-3', 
      '25%' => '1-4', 
      '20%' => '1-5', 
      'Small' => 'small', 
      'Medium' => 'medium', 
      'Large' => 'large', 
      'X-Large' => 'xlarge', 
      '2X-Large' => '2xlarge'
    ]
  ], 
  'image_vertical_align' => [
    'label' => 'Vertical Alignment', 
    'description' => 'Vertically align grid items in the center.', 
    'type' => 'checkbox', 
    'text' => 'Center'
  ], 
  'image_margin_top' => [
    'label' => 'Margin Top', 
    'description' => 'Adjust the top margin. Applies only when this content field follows another content field.', 
    'type' => 'select', 
    'options' => [
      'Small' => 'small', 
      'Default' => '', 
      'Medium' => 'medium', 
      'Large' => 'large', 
      'X-Large' => 'xlarge', 
      'None' => 'remove'
    ]
  ], 
  'image_margin_bottom' => [
    'label' => 'Margin Bottom', 
    'description' => 'Adjust the bottom margin. Applies only when another content field follows this one.', 
    'type' => 'select', 
    'options' => [
      'Small' => 'small', 
      'Default' => '', 
      'Medium' => 'medium', 
      'Large' => 'large', 
      'X-Large' => 'xlarge', 
      'None' => 'remove'
    ]
  ], 
  'image_svg_inline' => [
    'label' => 'Inline SVG', 
    'description' => 'Inject SVGs directly into the markup to allow styling with CSS.', 
    'type' => 'checkbox', 
    'text' => 'Make SVG stylable with CSS'
  ], 
  'image_svg_animate' => [
    'type' => 'checkbox', 
    'text' => 'Animate strokes'
  ], 
  'image_svg_color' => [
    'label' => 'SVG Color', 
    'description' => 'Apply a color to supported elements within the SVG.', 
    'type' => 'select', 
    'options' => [
      'None' => '', 
      'Muted' => 'muted', 
      'Emphasis' => 'emphasis', 
      'Primary' => 'primary', 
      'Secondary' => 'secondary', 
      'Success' => 'success', 
      'Warning' => 'warning', 
      'Danger' => 'danger'
    ]
  ], 
  'target_grid' => [
    'label' => 'Grid', 
    'description' => 'Select the grid where this fieldset will be displayed. Requires Advanced Grid Mode to be enabled.', 
    'default' => '1', 
    'type' => 'select', 
    'options' => [
      1 => '1', 
      2 => '2', 
      3 => '3', 
      4 => '4', 
      5 => '5', 
      6 => '6'
    ], 
    'source' => true, 
    'enable' => 'show_multiple_grids || this.builder.parent(this.node)[\'props\'][\'show_multiple_grids\']'
  ], 
  'show_text' => [
    'type' => 'checkbox', 
    'text' => 'Show the text', 
    'default' => true
  ], 
  'show_meta' => [
    'type' => 'checkbox', 
    'text' => 'Show the meta', 
    'default' => true
  ], 
  'show_image' => [
    'type' => 'checkbox', 
    'text' => 'Show the image', 
    'default' => true
  ], 
  'show_link' => [
    'type' => 'checkbox', 
    'text' => 'Show the link', 
    'default' => true
  ], 
  'limit' => [
    'label' => 'Limit', 
    'description' => 'Enable a character limit for the text output.', 
    'type' => 'checkbox', 
    'text' => 'Limit length'
  ], 
  'limit_length' => [
    'label' => 'Character Limit', 
    'description' => 'Set the maximum number of characters to display. HTML tags are stripped from the output but remain in the editor.', 
    'type' => 'range', 
    'attrs' => [
      'min' => 0, 
      'max' => 500, 
      'step' => 1, 
      'placeholder' => 'none'
    ]
  ], 
  'style' => [
    'label' => 'Style', 
    'description' => 'Select a predefined text style including color, size, and font family.', 
    'type' => 'select', 
    'options' => [
      'None' => '', 
      'Heading 3X-Large' => 'heading-3xlarge', 
      'Heading 2X-Large' => 'heading-2xlarge', 
      'Heading X-Large' => 'heading-xlarge', 
      'Heading Large' => 'heading-large', 
      'Heading Medium' => 'heading-medium', 
      'Heading Small' => 'heading-small', 
      'Heading H1' => 'h1', 
      'Heading H2' => 'h2', 
      'Heading H3' => 'h3', 
      'Heading H4' => 'h4', 
      'Heading H5' => 'h5', 
      'Heading H6' => 'h6', 
      'Text Meta' => 'text-meta', 
      'Text Lead' => 'text-lead', 
      'Text Large' => 'text-large', 
      'Text Default' => 'text-default', 
      'Text Small' => 'text-small'
    ]
  ], 
  'hover_style' => [
    'label' => 'Hover Style', 
    'description' => 'Set the hover style for linked text.', 
    'type' => 'select', 
    'options' => [
      'None' => 'reset', 
      'Heading Link' => 'heading', 
      'Default Link' => ''
    ]
  ], 
  'decoration' => [
    'label' => 'Decoration', 
    'description' => 'Decorate the text with a divider, bullet or a line that is vertically centered to the heading.', 
    'type' => 'select', 
    'options' => [
      'None' => '', 
      'Divider' => 'divider', 
      'Bullet' => 'bullet', 
      'Line' => 'line'
    ]
  ], 
  'color' => [
    'label' => 'Color', 
    'description' => 'Select the text color.', 
    'type' => 'select', 
    'options' => [
      'None' => '', 
      'Muted' => 'muted', 
      'Emphasis' => 'emphasis', 
      'Primary' => 'primary', 
      'Secondary' => 'secondary', 
      'Success' => 'success', 
      'Warning' => 'warning', 
      'Danger' => 'danger'
    ]
  ], 
  'html_element' => [
    'label' => 'HTML Element', 
    'description' => 'Choose one of the HTML elements to fit the semantic structure.', 
    'default' => 'div', 
    'type' => 'select', 
    'options' => [
      'h1' => 'h1', 
      'h2' => 'h2', 
      'h3' => 'h3', 
      'h4' => 'h4', 
      'h5' => 'h5', 
      'h6' => 'h6', 
      'div' => 'div'
    ]
  ], 
  'meta_position' => [
    'label' => 'Position', 
    'description' => 'Select the meta text position relative to the custom text.', 
    'default' => 'above-custom-text', 
    'type' => 'select', 
    'options' => [
      'Above Custom Text' => 'above-custom-text', 
      'Below Custom Text' => 'below-custom-text'
    ]
  ], 
  'hide_grid' => [
    'label' => 'Status', 
    'description' => 'Disable the grid and publish it later.', 
    'type' => 'checkbox', 
    'text' => 'Disable grid'
  ], 
  'meta' => [
    'label' => 'Meta', 
    'source' => true
  ], 
  'text' => [
    'label' => 'Text', 
    'source' => true
  ], 
  'image' => [
    'label' => 'Image', 
    'type' => 'image', 
    'source' => true
  ], 
  'image_alt' => [
    'label' => 'Image Alt', 
    'source' => true
  ], 
  'text_color' => [
    'label' => 'Text Color', 
    'description' => 'Set light or dark color mode for text, buttons and controls if a sticky transparent navbar is displayed above.', 
    'type' => 'select', 
    'options' => [
      'None' => '', 
      'Light' => 'light', 
      'Dark' => 'dark'
    ], 
    'source' => true
  ], 
  'icon' => [
    'label' => 'Icon', 
    'type' => 'icon', 
    'description' => 'Pick an icon from the icon library.', 
    'source' => true
  ], 
  'link' => [
    'label' => 'Link', 
    'type' => 'link', 
    'description' => 'Enter or pick a link, an image or a video file.', 
    'attrs' => [
      'placeholder' => 'https://'
    ], 
    'source' => true
  ], 
  'link_target' => [
    'type' => 'checkbox', 
    'text' => 'Open the link in a new window'
  ], 
  'link_toggle' => [
    'type' => 'checkbox', 
    'text' => 'Interactive toggle (also used for sublayouts modal connect)'
  ]
];
