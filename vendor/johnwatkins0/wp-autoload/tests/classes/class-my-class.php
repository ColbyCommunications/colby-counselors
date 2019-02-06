<?php

namespace MyNamespace;


class My_Class implements My_Interface {
	use My_Trait;

	const MY_CONST = '';

	public static function double_it( $it ) {
		return "$it$it";
	}
}