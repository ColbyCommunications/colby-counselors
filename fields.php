<?php

if( function_exists('register_field_group') ):

$us_territories = explode( '<br />', get_field( 'us_territories', 'option' ) );
$international_territories = explode( '<br />', get_field( 'international_territories', 'option' ) );

$territories = [];
foreach ( array_merge( $us_territories, $international_territories ) as $territory ) {
	if ( trim( $territory ) ) {
		$territories[$territory] = $territory;
	}
}

register_field_group([
'id' => 'acf_counselor-fields',
	'title' => 'Counselor Fields',
	'fields' => [
		[
			'key' => 'field_54184c182fc22',
			'label' => 'Locations',
			'name' => 'locations',
			'type' => 'select',
			'required' => 0,
			'choices' => $territories,
			'default_value' => '',
			'allow_null' => 0,
			'multiple' => 1,
		],
		[
			'key' => 'field_x4185a4f2c415',
			'label' => 'Job Title',
			'name' => 'jobtitle',
			'type' => 'text',
			'required' => 0,
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'formatting' => 'html',
			'maxlength' => '',
		],
		[
			'key' => 'field_54185a4f2c415',
			'label' => 'Phone',
			'name' => 'phone',
			'type' => 'text',
			'required' => 1,
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'formatting' => 'html',
			'maxlength' => '',
		],
		[
			'key' => 'field_54185a7a2c416',
			'label' => 'Email',
			'name' => 'email',
			'type' => 'email',
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		],
		[
			'key' => 'field_545c292214b26',
			'label' => 'Regional Representative',
			'name' => 'regional_representative',
			'prefix' => '',
			'type' => 'checkbox',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'choices' => [
				'yes' => 'Yes',
				],
			'default_value' => array(),
			'layout' => 'vertical',
		],
	],
	'location' => [
		[
			[
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'counselors',
				'order_no' => 0,
				'group_no' => 0,
			],
		],
	],
	'options' => [
		'position' => 'normal',
		'layout' => 'no_box',
		'hide_on_screen' => [],
		],
	'menu_order' => 0,
] );

endif;

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_57ec061700c87',
	'title' => 'Counselor Territories',
	'fields' => array (
		array (
			'key' => 'field_57ec062849913',
			'label' => 'U.S. Territories',
			'name' => 'us_territories',
			'type' => 'textarea',
			'instructions' => 'Enter each territory on its own line.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => 20,
			'new_lines' => 'br',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_57ec066049914',
			'label' => 'International Territories',
			'name' => 'international_territories',
			'type' => 'textarea',
			'instructions' => 'Enter each territory on its own line.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => 20,
			'new_lines' => 'br',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-counselor-territories',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
