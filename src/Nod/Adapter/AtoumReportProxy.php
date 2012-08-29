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
use mageekguy\atoum\reports\asynchronous as AtoumAsyncReport;
use mageekguy\atoum\observable as AtoumObservable;
use mageekguy\atoum\test as AtoumTest;

/**
 * Nod\Adapter\AtoumReportProxy
 * Notification redirection adapter for the atoum runner. Can
 * be given to atoum\runner as a writer, and will also work
 * as a transparent proxy otherwise.
 */
class AtoumReportProxy extends AtoumAsyncReport implements AdapterInterface
{
    /**
     * @see Nod\Adapter\AdapterInterface::__construct
     */
    protected $childAdapter;

    /**
     * AtoumReportProxy merely acts as a redirection adapter, and expects
     * a second adapter to be provided, to which it redirects.
     * @param Nod\Adapter\AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $childAdapter)
    {
        $this->childAdapter = $childAdapter;
        parent::__construct();
    }

    /**
     * @see    Nod\Adapter\AdapterInterface::canNotify
     * @return bool
     */
    public function canNotify()
    {
        return $this->childAdapter->canNotify();
    }

    /**
     * @see    Nod\Adapter\AdapterInterface::process
     * @param  string $title
     * @param  string $message
     * @param  string $urgency
     * @param  int    $expiry
     * @param  string $icon
     * @return bool
     */
    public function process($title = 'atoum', $message = '', $urgency = 'normal', $expiry = 3000, $icon = null)
    {
        return $this->childAdapter->process($title, $message, $urgency, $expiry, $icon);
    }

    /**
     * @see    mageekguy\atoum\reports\asynchronous::handleEvent
     * @see    Nod\Adapter\AtoumReportProxy::process
     * @param  mixed $event
     * @param  mageekguy\atoum\observable $observable
     * @return Nod\Adapter\AtoumReportProxy
     */
    public function handleEvent($event, AtoumObservable $observable)
    {
        switch($event) {
            case AtoumTest::fail:
            case AtoumTest::error:
            case AtoumTest::exception:
                $this->process('atoum', 'A test has failed', 'normal', 3000);
        }

        return $this;
    }
}