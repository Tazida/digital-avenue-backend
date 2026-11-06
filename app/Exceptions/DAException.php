<?php

namespace App\Exceptions;

use Exception;

/**
 * Base exception for Digital Avenue business rules. Services throw it when a request
 * breaks a rule (e.g. publishing a project with no photos); controllers answer with 409.
 */
class DAException extends Exception {}
