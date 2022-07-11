<?php

/** Se a requisição é do tipo GET */
define('IS_GET', !IS_TERMINAL && strtoupper($_SERVER['REQUEST_METHOD']) == 'GET');

/** Se a requisição é do tipo POST */
define('IS_POST', !IS_TERMINAL && strtoupper($_SERVER['REQUEST_METHOD']) == 'POST');

/** Se a requisição é do tipo PUT */
define('IS_PUT', !IS_TERMINAL && strtoupper($_SERVER['REQUEST_METHOD']) == 'PUT');

/** Se a requisição é do tipo DELETE */
define('IS_DELETE', !IS_TERMINAL && strtoupper($_SERVER['REQUEST_METHOD']) == 'DELETE');