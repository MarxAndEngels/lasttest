<?php

declare(strict_types=1);

namespace App\Services\Filter;

use function collect;

class GetChosenFieldsService
{
  protected array $input;
  public array $output;
  protected array $availableKeys = [
    'mark_slug_array' => 'mark',
    'folder_slug_array' => 'folder',
    'generation_slug_array' => 'generation',
    'gearbox_id_array' => 'gearbox',
    'body_type_id_array' => 'bodyType',
    'drive_type_id_array' => 'driveType',
    'engine_type_id_array' => 'engineType',
    'owner_id_array' => 'owner',
    'year_from' => 'yearFrom',
    'year_to' => 'yearTo',
    'price_from' => 'priceFrom',
    'price_to' => 'priceTo',
    "run_from" => 'runFrom',
    "run_to" => 'runTo',
    'engine_power_from' => 'enginePowerFrom',
    'engine_power_to' => 'enginePowerTo'
  ];

  public function __construct(array $input, array $output)
  {
    $this->input = $input;
    $this->output = $output;
    if ($this->input && $this->output) {
      $this->handle();
    }
  }

  protected function handle(): void
  {
    $this->output['chosen'] = collect($this->availableKeys)
      ->intersectByKeys($this->input)
      ->filter(fn($item, $key) => $this->input[$key] && isset($this->output[$item]))
      ->mapWithKeys(fn($value, $key) => [$value => call_user_func([$this, $value], $this->input[$key])])->all();
  }

  public function getChosenFields(): array
  {
    return $this->output;
  }

  protected function mark(array $values): array
  {
    return $this->getChosenWhereIn($this->output[__FUNCTION__], 'slug', $values);
  }

  protected function folder(array $values): array
  {
    return $this->getChosenWhereIn($this->output[__FUNCTION__], 'slug', $values);
  }

  protected function generation(array $values): array
  {
    return $this->getChosenWhereIn($this->output[__FUNCTION__], 'slug', $values);
  }

  protected function gearbox(array $values): array
  {
    return $this->getChosenWhereIn($this->output[__FUNCTION__], 'id', $values);
  }

  protected function bodyType(array $values): array
  {
    return $this->getChosenWhereIn($this->output[__FUNCTION__], 'id', $values);
  }

  protected function driveType(array $values): array
  {
    return $this->getChosenWhereIn($this->output[__FUNCTION__], 'id', $values);
  }

  protected function engineType(array $values): array
  {
    return $this->getChosenWhereIn($this->output[__FUNCTION__], 'id', $values);
  }

  protected function owner(array $values): array
  {
    return $this->getChosenWhereIn($this->output[__FUNCTION__], 'id', $values);
  }

  protected function yearFrom(int $year): ?int
  {
    return $this->getChosenBetween($year, $this->output['year']);
  }

  protected function yearTo(int $year): ?int
  {
    return $this->getChosenBetween($year, $this->output['year']);
  }

  protected function priceFrom(int $priceFrom): ?int
  {
    return $this->getChosenBetween($priceFrom, $this->output['price']);
  }

  protected function priceTo(int $priceTo): ?int
  {
    return $this->getChosenBetween($priceTo, $this->output['price']);
  }

  protected function runFrom(int $run): ?int
  {
    return $this->getChosenBetween($run, $this->output['run']);
  }

  protected function runTo(int $run): ?int
  {
    return $this->getChosenBetween($run, $this->output['run']);
  }

  protected function enginePowerFrom(int $enginePower): ?int
  {
    return $this->getChosenBetween($enginePower, $this->output['enginePower']);
  }

  protected function enginePowerTo(int $enginePower): ?int
  {
    return $this->getChosenBetween($enginePower, $this->output['enginePower']);
  }

  #Common methods
  protected function getChosenWhereIn(array $array, string $key, array $values): array
  {
    return collect($array)->whereIn($key, $values)->all();
  }

  protected function getChosenBetween(int $key, array $array): ?int
  {
    if ($key >= $array[0] && $key <= $array[1]) {
      return $key;
    }
    return null;
  }

}
