<?php
    namespace Controllers;

    use App\View;

    class TestController {
        public function test () {
            View::render();
        }

        public function user($user_id) {
            echo "User id is " . $user_id;
        }
    }