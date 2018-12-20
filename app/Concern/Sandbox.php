<?php
/**
 * Created by PhpStorm.
 * User: catchme
 * Date: 2018/12/20
 * Time: 上午10:19
 */

namespace App\Concern;

use Illuminate\Container\Container;
use Illuminate\Http\Request;

class Sandbox
{
    private $container;
    private $snapshot;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->snapshot = clone $container;
        Context::setApp($this->snapshot);
    }

    public function setRequest(Request $request)
    {
        Context::setData('_request', $request);
        return $this;
    }

    public function run(Request $request)
    {
        Context::setData('_request', $request);
    }

    public function clear()
    {
        Context::clear();
    }

}
