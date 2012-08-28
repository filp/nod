<?php

/**
 * This file is part of Nod, a desktop notifications
 * library for PHP. Nod is distributed freely under
 * the MIT license, available at:
 * http://opensource.org/licenses/mit-license.php
 *
 * @package  Nod
 * @author   Filipe Dobreira <http://github.com/filp>
 * @license  MIT
 */
call_user_func(function() {
    $loader = require __DIR__ . '/../vendor/autoload.php';
    $loader->add('Nod\\', __DIR__);

    return $loader;
});