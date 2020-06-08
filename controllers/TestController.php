<?php

    namespace Controllers;

    class TestController {
        public function test () {
            echo "hello!";
        }

        public function user($user_id) {
            echo "User id is " . $user_id;
        }
    }