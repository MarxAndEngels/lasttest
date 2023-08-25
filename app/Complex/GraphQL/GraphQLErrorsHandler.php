<?php

declare(strict_types=1);

namespace App\Complex\GraphQL;

use Error as PhpError;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Rebing\GraphQL\Error\AuthorizationError;
use Rebing\GraphQL\Error\AutomaticPersistedQueriesError;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\GraphQL as VendorGraphQL;
use Exception;
final class GraphQLErrorsHandler extends VendorGraphQL
{

  public static function customHandleErrors(array $errors, callable $formatter): array
  {
    $handler = app()->make(ExceptionHandler::class);

    foreach ($errors as $error) {
      // Try to unwrap exception
      $error = $error->getPrevious() ?: $error;

      // Don't report certain GraphQL errors
      if ($error instanceof ValidationError ||
        $error instanceof AuthorizationError ||
        $error instanceof AutomaticPersistedQueriesError ||
        !(
          $error instanceof Exception ||
          $error instanceof PhpError
        )) {
        continue;
      }

      if (!$error instanceof Exception) {
        $error = new Exception(
          $error->getMessage(),
          $error->getCode(),
          $error
        );
      }

      $handler->report($error);
    }

    return array_map($formatter, $errors);
  }
}
