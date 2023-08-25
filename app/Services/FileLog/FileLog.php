<?php
declare(strict_types=1);

namespace App\Services\FileLog;

class FileLog {

  public string $logFilePath;

  public function __construct(string $logFilePath)
  {
   $this->logFilePath = storage_path('logs/'.$logFilePath);
  }

  protected function echo_flush($var): void
  {
    if (php_sapi_name() != 'cli') return;

    if (is_array($var) || is_object($var)) {
      print_r($var);
    } else {
      echo $var;
    }
  }

  public function log(string $message, bool $clear = false) : void
  {
    $date = date('d.m.Y H:i:s');
    $message = $message ? "{$date}: {$message}" : $message;
    $message = $message . PHP_EOL;
    $this->echo_flush($message);
    if (!$clear) {
      file_put_contents($this->logFilePath, $message, FILE_APPEND);
    } else {
      file_put_contents($this->logFilePath, $message);
    }


  }




}
