<?php

class X {
	function a() {
		parent::a();

		echo 'wow';
	}

	function b() {
		echo 'wow';

		parent::b();
	}

	function c() {
		$x = parent::c();
		echo $x;
	}

	function d() {
		wow(
			parent::c()
		);
		echo $x;
	}
}
