<?php

declare( strict_types=1 );

namespace WordPress_Related;

use function DI\create;

return [
	'taxonomy'  => create( 'WordPress_Related\Taxonomy\Taxonomy' ),
	'post_type' => create( 'WordPress_Related\Post_Type\Post_Type' ),
];
