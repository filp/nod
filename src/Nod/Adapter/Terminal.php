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

namespace Nod\Adapter;
use Nod\Adapter\AdapterInterface;

/**
 * Nod\Adapter\Terminal
 * Mock adapter that simply outputs notifications
 * to the terminal.
 */
class Terminal implements AdapterInterface
{
    /**
     * @return bool
     */
    public function canNotify()
    {
        return php_sapi_name() == 'cli' ?: 
         "you're not running php through the command-line interface!";
    }

    /**
     * @param  string $title
     * @param  string $message
     * @param  string $urgency
     * @param  int    $expiry
     * @param  string $icon
     * @return bool
     */
    public function process($title, $message, $urgency, $expiry, $icon)
    {
        static $bold   = "\033[1m";
        static $yellow = "\033[33m";
        static $reset  = "\033[0m";
        static $line   = "\033[4m";
        static $noLine = "\033[24m";

        print "{$bold}[ ! $urgency ! ] {$line}" . __CLASS__ . "{$noLine} says:\n{$reset}";
        print "{$bold}{$yellow}{$title}{$reset}\n";
        print "$message\n";

        return true;
    }
}