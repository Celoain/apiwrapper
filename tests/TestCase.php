<?php

namespace Tests;

use Celoain\ApiWrapper\Contracts\Api\RequestInterface;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public RequestInterface $request;
}

