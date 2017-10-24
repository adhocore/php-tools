<?php
/*
 * credits: stackoverflow.com/questions/12082507/php-most-lightweight-psr-0-compliant-autoloader
 */

spl_autoload_register(function ($c) {
    @include preg_replace('#\\\|_(?!.+\\\)#', '/', $c) . '.php';
});
