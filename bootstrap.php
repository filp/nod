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
require __DIR__ . '/vendor/autoload.php';
call_user_func(function() {
    $notification = new Nod\Notification;
    $notification
      ->setTitle("Test Message!")
      ->setMessage("a Amanda É FIXE")
      ->setUrgency("normal")
      ->setIcon('/usr/share/icons/Faenza/emblems/48/emblem-favorite.png')
      ->send();
});