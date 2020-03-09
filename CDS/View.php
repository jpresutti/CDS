<?php

namespace CDS;

class View {
    public static function showView(string $path,array $arguments = []) {
        include(PROJECT_ROOT . 'CDS' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'layout.phtml');
    }
}