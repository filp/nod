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

namespace Nod;
use Nod\Adapter\AdapterInterface;

/**
 * Nod\Notification
 */
class Notification
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $urgency = 'normal';

    /**
     * Time, in ms, after which to expire the notification.
     * @var int
     */
    protected $expiry = 3000;

    /**
     * @var string
     */
    protected $icon;

    /**
     * An array mapping OS-name (per php_uname) to a known
     * adapter for notifications in that environment.
     * This serves merely as a convenience for quick-use
     * of this library in quick-n-dirty prototypes.
     * @var array
     */
    protected $notificationAdapters = array(
        'linux' => 'Nod\\Adapter\\NotifySend'
    );

    /**
     * @var Nod\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * If no instance of Nod\Adapter\AdapterInterface is
     * provided, Nod attempts to find the best possible
     * adapter for the current environment.
     * @param Nod\Adapter\AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter = null)
    {
        if($adapter === null) {
            $adapter = $this->getGuessedAdapterInstance();
        }

        $this->setAdapter($adapter);
    }

    /**
     * Sends the notification to the adapter, to be processed
     * (displayed, logged, whatever). An optional expiry par-
     * -meter can be passed, as a convenience.
     * @see    Nod\Notification::setExpiry
     * @param  int $expiry
     * @return bool
     */
    public function send($expiry = null)
    {
        if($expiry !== null) {
            $this->setExpiry($expiry);
        }

        // The adapter may opt-out of processing the notification:
        if(($canNotify = $this->adapter->canNotify()) !== true) {
            throw new Exception\CannotNotify(
                "Adapter " . get_class($this->adapter) . " cannot send notification because: $canNotify"
            );
        }

        return $this->adapter->process(
            $this->title,
            $this->message,
            $this->urgency,
            $this->expiry,
            $this->icon
        );
    }

    /**
     * @see    Nod\Notification::title
     * @param  string $title
     * @return Nod\Notification
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
        return $this;
    }

    /**
     * @see    Nod\Notification::icon
     * @param  string $message
     * @return Nod\Notification
     */
    public function setMessage($message)
    {
        $this->message = (string) $message;
        return $this;
    }

    /**
     * @see    Nod\Notification::icon
     * @param  string $iconPath
     * @return Nod\Notification
     */
    public function setIcon($icon)
    {
        $this->icon = (string) $icon;
        return $this;
    }

    /**
     * @see    Nod\Notification::urgency
     * @param  string $urgencyPath
     * @return Nod\Notification
     */
    public function setUrgency($urgency)
    {
        static $urgencyLevels = array('low', 'normal', 'high');

        if(!in_array($urgency, $urgencyLevels)) {
            throw new Exception\UnknownUrgencyLevel("$urgency is not a known urgency level.");
        }

        $this->urgency = $urgency;
        return $this;
    }

    /**
     * @see    Nod\Notification::expiry
     * @param  int $urgencyPath
     * @return Nod\Notification
     */
    public function setExpiry($expiry)
    {
        $this->expiry = (int) $expiry;
        return $this;
    }

    /**
     * @see    Nod\Notification::adapter
     * @return Nod\Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @see    Nod\Notification::adapter
     * @param  Nod\Adapter\AdapterInterface $adapter
     * @return Nod\Notification
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * @see    Nod\Notification::notificationAdapters
     * @param  array $adapterMap
     * @return Nod\Notification
     */
    public function registerAdapters(array $adapterMap)
    {
        $this->notificationAdapters += $adapterMap;
        return $this;
    }

    /**
     * @see    Nod\Notification::notificationAdapters
     * @return array
     */
    public function getRegisteredAdapters()
    {
        return $this->notificationAdapters;
    }

    /**
     * As the name implies, this method tries to make an informed
     * guess as to what the best adapter for this environment
     * may be.
     * @throws Nod\Exception\NoAdapterFound If no valid adapter was found.
     * @return Nod\Adapter\AdapterInterface
     */
    protected function getGuessedAdapterInstance()
    {
        // Possible values of osId (that we may care about):
        // [darwin, linux, winnt, freebsd]
        $osId = strtolower(php_uname('s'));
        if(isset($this->notificationAdapters[$osId])) {
            return (new $this->notificationAdapters[$osId]);
        }

        throw new Exception\NoAdapterFound("No adapter found for '$osId'");
    }
}