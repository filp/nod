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
 * Nod\Adapter\NotifySend
 * Basic notification adapter that sends messages
 * to the desktop through notify-send. 
 */
class NotifySend implements AdapterInterface
{
    /**
     * @var string
     */
    protected $notifySendBin = 'notify-send';

    /**
     * @var bool
     */
    protected $canUseNotifySend;

    /**
     * Maps urgency levels from Nod\Notification to
     * values understood by notify-send.
     * @var array
     */
    protected $urgencyMap = array(
        'low'    => 'low',
        'normal' => 'normal',
        'high'   => 'critical'
    );

    /**
     * Optionally accepts a string path to the notify-send
     * (or compatible) binary. Note that this string is
     * not escaped in any way - be sure it's only provided
     * from a reliable source (i.e: you).
     * @param string $notifySendBin
     */
    public function __construct($notifySendBin = null)
    {
        if($notifySendBin !== null) {
            $this->notifySendBin = $notifySendBin;
        }
    }

    /**
     * Checks if notify-send is usable by querying WHICH(1).
     * Probably not 100% reliable, but serves its purpose.
     * @return bool
     */
    public function canNotify()
    {
        if($this->canUseNotifySend === null) {
            exec("which {$this->notifySendBin} > /dev/null", $output, $code);
            $this->canUseNotifySend = ($code === 0);
        }

        return $this->canUseNotifySend ?: 
            "{$this->notifySendBin} cannot be found (check your PATH, maybe?)";
    }

    /**
     * Summary of notify-send usage (that we care about)
     * -u : URGENCY [low, normal, critical]
     * -t : EXPIRE TIME (ms)
     * -i : ICON
     * 
     * @param  string $title
     * @param  string $message
     * @param  string $urgency
     * @param  int    $expiry
     * @param  string $icon
     * @return bool
     */
    public function process($title, $message, $urgency, $expiry, $icon)
    {
        $command  = "$this->notifySendBin ";
        $command .= escapeshellarg($title) . ' ';
        $command .= escapeshellarg($message) . ' ';

        // Map $urgency to an urgency level understood by notify-send
        // (any of [low, normal, critical])
        if($urgency && isset($this->urgencyMap[$urgency])) {
            $urgency = $this->urgencyMap[$urgency];
            $command .= "-u $urgency ";
        }

        // $expiry must be an integer, and we don't care about it
        // if it's falsy.
        if((int) $expiry) {
            $command .= "-t " . (int) $expiry . " ";
        }

        if($icon) {
            $command .= "-i '" . escapeshellarg($icon) . "' ";
        }

        $escapedCommand = escapeshellcmd($command) . ' > /dev/null';
        exec($escapedCommand, $output, $code);
        return $code === 0;
    }
}