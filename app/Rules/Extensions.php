<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Extensions implements Rule
{
  public array $extensions;

  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct(array $extensions)
  {
    $this->extensions = $extensions;
  }

  /**
   * Determine if the validation rule passes.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    // $name = $value->getClientOriginalName();
    // $exloded = explode('.', $name);
    // if (count($exloded) > 2) return false;
    return in_array(mb_strtolower($value->getClientOriginalExtension()), $this->extensions);
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    $extensions = $this->stringifyExtensions();
    return "Ошибка загрузки файла. Допустимые типы файлов: {$extensions}";
  }

  private function stringifyExtensions()
  {
    return implode(", ", $this->extensions);
  }
}
