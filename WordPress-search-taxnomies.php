<?php
function eo_search_terms_by($field, $value, $taxonomy, $output = OBJECT) {
	global $wpdb;

	if ( ! taxonomy_exists($taxonomy) )
		return false;

	if ( 'slug' == $field ) {
		$field = 't.slug';
		$value = sanitize_title($value);
		if ( empty($value) )
			return false;

	} else if ( 'name' == $field ) {
		// Assume already escaped
		$value = stripslashes($value);
		$field = 't.name';
	} else {
		return false;
	}

	$terms = $wpdb->get_results( $wpdb->prepare( "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = %s AND  ($field LIKE'%%%s%%')", $taxonomy, $value) );

	if ( !$terms )
		return false;

	$terms = apply_filters('get_terms', $terms, array($taxonomy), array());

	if ( $output == OBJECT ) {
		return $terms;
	} elseif ( $output == ARRAY_A ) {
		return array_map('get_object_vars',$terms);
	} elseif ( $output == ARRAY_N ) {
		$terms = array_map('get_object_vars',$terms);
		return array_map('array_values',$terms);
	} else {
		return $terms;
	}

	return $terms;
}
?>
