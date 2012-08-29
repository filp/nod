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
use mageekguy\atoum\writer as AtoumWriterAbstract;

/**
 * Nod\Adapter\AtoumWriter
 * Notification redirection adapter for the atoum runner. Can
 * be given to atoum\runner as a writer, and will also work
 * as a transparent proxy otherwise.
 */
class AtoumWriter extends AtoumWriterAbstract implements AdapterInterface
{
    /**
     * @see Nod\Adapter\AdapterInterface::__construct
     * @see mageekguy\atoum\writer::adapter
     */
    protected $childAdapter;

    /**
     * AtoumWriter merely acts as a redirection adapter, and expects
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
    public function process()
    {
        return call_user_func_array($this->childAdapter, func_get_args());
    }

    /**
     * @see   mageekguy\atoum\writer::write
     * @see   Nod\Adapter\AtoumWriter::process
     * @param string $string
     */
    public function write($string)
    {
        $this->process('atoum', $string, 'normal', 3000);
    }
}