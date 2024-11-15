<?php

namespace App\Http\Requests\Episode;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EpisodeStoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      "number" => [
        'required',
        'integer',
        'min:0',
        Rule::unique('episodes', 'number')
          ->where('series_id', $this->series_id)
          ->where('user_id', Auth::user()->id)
      ],
      "source_url" => ['nullable'],
      "description" => ['nullable'],
      "series_id" => [
        'required',
        Rule::exists('series', 'id')->where(function ($query) {
          $query->where('user_id', Auth::id());
        })
      ],
    ];
  }

  public function messages(): array
  {
    return [
      'number.unique' => 'The last episode has already been added.'
    ];
  }

  public function data(): array
  {
    return [
      'number' => $this->number,
      'source_url' => $this->source_url,
      'description' => $this->description,
      'series_id' => $this->series_id,
      'user_id' => Auth::user()->id
    ];
  }
}
