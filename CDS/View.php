<?php

namespace CDS;

class View {
    public static function showView(string $path,array $arguments = []) {
        include(PROJECT_ROOT . 'Views' . DIRECTORY_SEPARATOR . 'layout.phtml');
    }
}