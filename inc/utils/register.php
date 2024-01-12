<?php

namespace Routiz\Inc\Utils;

class Register {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {
        // ..
    }

    public function register( $name, $inst = false ) {

		$this->$name = $inst;

	}

    public function __call( $method, $params ) {

        if ( isset( $this->$method ) ) {
            return $this->$method;
        }

        return;

    }

}
