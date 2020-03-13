<?php

namespace CDS;

class View {
    /**
     * Show page layout
     * @param string $path
     * @param array $arguments
     */
    public static function showView(string $path,array $arguments = []) {
        include(PROJECT_ROOT . 'CDS' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'layout.phtml');
    }
}