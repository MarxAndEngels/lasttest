<?php
declare(strict_types=1);

namespace App\Complex\GraphQL;

use App\Exceptions\MessageError;
use Rebing\GraphQL\Support\Query as VendorQuery;

abstract class Query extends VendorQuery
{
  /**
   * @throws MessageError
   */
  public static function notFound(string $message = 'Not found')
  {
    throw new MessageError($message, 404);
  }
}
