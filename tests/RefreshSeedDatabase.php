<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

trait RefreshSeedDatabase
{
    use RefreshDatabase;

    protected bool $seed = true;
}
