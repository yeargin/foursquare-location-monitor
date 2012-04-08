<?php

function listTags($tags = array(), $template = null) {

	if (!is_array($tags))
		return false;

	// Use default template if not set
	if (is_null($template)):
		$template = ' <span class="label">%s</span>';
	endif;

	// Build return output
	$output = '';
	
	foreach ($tags as $tag):
		$output .= sprintf($template, $tag);
	endforeach;
	
	return $output;
}