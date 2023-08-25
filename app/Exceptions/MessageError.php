<?php

namespace App\Exceptions;

use Exception;

class MessageError extends Exception
{
  protected $message = 'Unknown exception';
  protected $code = 0;

  public function __construct(string $message = null, int $code = 0)
  {
    if (!$message) throw new $this('Unknown ' . $this::class);
    parent::__construct($message, $code);
  }
}
