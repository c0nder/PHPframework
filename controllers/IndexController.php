<?php
    namespace Controllers;

    use App\Controller;
    use Models\Animal;

    class IndexController extends Controller {
        public function index()
        {
            $this->renderLayout('index');
        }
    }