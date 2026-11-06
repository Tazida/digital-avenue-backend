<?php

namespace App\Exceptions;

/**
 * A requested record does not exist (or is not published); controllers answer with 404.
 * Other DAExceptions are business-rule failures and get 409.
 */
class NotFoundException extends DAException {}
