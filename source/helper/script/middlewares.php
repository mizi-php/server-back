<?php

use Mizi\Middleware;

Middleware::register('#error', 'ServerBack.Error');

Middleware::register('#response', 'ServerBack.Response');