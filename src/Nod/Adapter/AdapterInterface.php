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

/**
 * Nod\Adapter\AdapterInterface
 */
interface AdapterInterface
{
    /**
     * Are all requirements for this adapter to send notifications
     * met? e.g: display is available for notify-send
     * @return bool
     */
    public function canNotify();

    /**
     * Gather the notification info and process it.
     * @param  string $title
     * @param  string $message
     * @param  string $urgency
     * @param  int    $expiry
     * @param  string $icon
     * @return bool
     */
    public function process($title, $message, $urgency, $expiry, $icon);
}