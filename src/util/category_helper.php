<?php

function get_root_category($category) {
    $category = is_object($category) ? $category : get_category($category);

    if ($category) {
        while ($category->parent > 0) $category = get_category($category->parent);
    }

    return $category;
}

function build_vtp_category_tree ($activeCatId, $activeParentId, $parentId = 0) {

    $output = '';
    $terms = get_terms( array(
        'taxonomy' => 'vitaplace_categories',
        'hide_empty' => true,
        'hierarchical' => true,
        'parent' => $parentId
    ) );

    if (count($terms)) {

        $output .= '<ul class="vtp-cat-list">';

        foreach ($terms as $term) {

            $activeClass = $term->term_id === $activeParentId || $term->term_id === $activeCatId ? ' active' : '';
            $selectedClass = $term->term_id === $activeCatId ? ' selected' : '';

            $output .= '<li class="vtp-cat' . $activeClass . $selectedClass . '">';
            $output .=  '<span class="vtp-cat-link">' . $term->name . '</span>';
            $output .=  build_vtp_category_tree($activeCatId, $activeParentId, $term->term_id);
            $output .= '</li>';
        }

        $output .= '</ul>';
    }

    return $output;
}

function get_parameter ($name, $default = '') {

	if (array_key_exists($name, $_GET)) {
	    return trim($_GET[$name]);
	}
	return $default;
}