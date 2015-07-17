<?php

if (!function_exists('app')) {

    class View
    {
        public function getFinder()
        {
            return $this;
        }

        public function getPaths()
        {
            return [ dirname(__FILE__) . '/views' ];
        }
    }

    function app() {
        $view = new View();

        return array(
            'view' => $view,
        );
    }
}
