<?php
/*
Plugin Name: Reservations
Description: Easy reservation form
Author: Marc Neuhaus <mneuhaus@famelo.com>
Version: 0.0.1
*/

#if(!function_exists("register_field_group")) {
#	define( 'ACF_LITE' , true );
#	include_once('advanced-custom-fields/acf.php' );
#}

class FameloReservations {
	public function __construct() {
		add_action( 'admin_menu', array($this, 'addMenu') );
		$this->registerFields();
		$this->addShortcodes();
	}

	public function addMenu() {
		add_options_page( 'Reservations', 'Reservations', 'manage_options', 'famelo_reservations', array($this, 'showOptions') );
	}

	public function addShortCodes() {
		add_shortcode('form', array($this, 'showFormShortcode'));
	}

	public function registerFields() {
		register_field_group(array (
			'id' => 'famelo-reservations',
			'title' => 'Famelo Reservations',
			'fields' => array (
				array (
					'key' => 'field_521c649c18d55',
					'label' => 'Firmenname',
					'name' => 'company',
					'type' => 'text',
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				)
			),
			'options' => array (
				'position' => 'normal',
				'layout' => 'no_box',
				'hide_on_screen' => array (
				),
			),
			'menu_order' => 0,
		));
	}

	public function showOptions() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		acf_form_head();
		$args = array(
			'post_id' => 'new',
			'field_groups' => array(16,'famelo-reservations')
		);

		acf_form( $args );
	}

	function showFormShortcode($atts) {
		// extract attributes
		extract(shortcode_atts(array(
			'id' => NULL
		), $atts));

		global $fields;
		$fields = array();
		foreach (apply_filters('acf/field_group/get_fields', array(), $id) as $field) {
			if(!isset($field['value'])) {
				$field['value'] = apply_filters('acf/load_value', false, $post_id, $field);
				$field['value'] = apply_filters('acf/format_value', $field['value'], $post_id, $field);
			}
			$key = $field['name'];
			$field['key'] = $key;
			$field['name'] = 'fields[' . $field['name'] . ']';

			// required
			$required_class = "";
			$required_label = "";

			if( $field['required'] ) {
				$required_class = ' required';
				$required_label = ' <span class="required">*</span>';
			}

			$fields[$key] = $field;
		}

		get_form_template_part('Form/Layout');
	}
}

function get_form_template_part($slug, $name = '') {
	do_action( "get_form_template_part_{$slug}", $slug, $name );

	$templates = array();
	$name = (string) $name;
	if ( '' !== $name )
		$templates[] = "{$slug}-{$name}.php";

	$templates[] = "{$slug}.php";

	locate_form_template($templates, true, false);
}

function locate_form_template($template_names, $load = false, $require_once = true ) {
	$located = '';
	foreach ( (array) $template_names as $template_name ) {
		if ( !$template_name )
			continue;
		if ( file_exists(STYLESHEETPATH . '/' . $template_name)) {
			$located = STYLESHEETPATH . '/' . $template_name;
			break;
		}
		if ( file_exists(TEMPLATEPATH . '/' . $template_name) ) {
			$located = TEMPLATEPATH . '/' . $template_name;
			break;
		}

	    $pluginDir = WP_PLUGIN_DIR . '/' . str_replace( basename( __FILE__), "", plugin_basename(__FILE__));
		if ( file_exists($pluginDir . '/' . $template_name) ) {
			$located = $pluginDir . '/' . $template_name;
			break;
		}
	}

	if ( $load && '' != $located )
		include($located);
		//load_template( $located, $require_once );

	return $located;
}


function the_form_fields() {
	global $fields;
	return $fields;
}

function the_form_input($field) {
	echo do_action('acf/create_field', $field);
}

function the_form_field($fieldName) {
	global $field;
	if (is_string($fieldName)) {
		global $fields;
		$field = $fields[$fieldName];
	} else {
		$field = $fieldName;
	}
	get_form_template_part('Form/Field', $field['key']);
}

function the_field_property($name) {
	global $field;
	return $field[$name];
}

$fameloReservations = new FameloReservations();

?>