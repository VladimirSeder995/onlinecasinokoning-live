<?php 
/**
* Field Example with all properties
*   'key' => 'fcrp_fields_addon_cover_image',
*   'label' => 'Step',
*   'name' => 'step',
*   'type' => 'text',
*   'instructions' => __('Enter a sentence or a few words to describe the site which will be used in the featured shortcode.', 'flytonic-casino-review'),
*   'required' => 0,
*   'conditional_logic' => 0,
*	'wrapper' => array (
*		'width' => '',
*		'class' => '',
*		'id' => '',
*	),
*   'default_value' => '',
*   'placeholder' => '',
*   'maxlength' => '',
*   'rows' => 2,
*   'new_lines' => '',
*   'readonly' => 0,
*   'disabled' => 0,
 */
$fields = [
    [
        'key'           => 'cover_image',
        'label'         => 'Logo',
        'name'          => 'logo',
        'type'          => 'image',
        'wrapper' => array (
            'width' => '50%',
            'class' => '',
            'id' => '',
        ),
    ],
    [
        'key'           => 'cover_image_small',
        'label'         => 'Logo small',
        'name'          => 'logo_small',
        'type'          => 'image',
        'wrapper' => array (
            'width' => '50%',
            'class' => '',
            'id' => '',
        ),
    ],
    [
        'key'           => 'bonus_display_text',
        'label'         => __('Bonus Display Additional Text (second row)', 'flytonic-casino-review'),
        'name'          => 'bonus_display_text',
        'type'          => 'text',
    ]
];

?>