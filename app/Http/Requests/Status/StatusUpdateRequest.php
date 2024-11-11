<?php

namespace App\Http\Requests\Status;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StatusUpdateRequest extends FormRequest
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
      'name' => [
        'required',
        'max:255',
        'min:3',
        Rule::unique('statuses', 'name')
          ->where('user_id', Auth::user()->id)
          ->ignore($this->status->id, 'id')
      ],
      'slug' => [
        'required',
        'max:255',
        'min:3',
        'regex:/^[a-z0-9-]+$/',
        Rule::unique('statuses', 'slug')
          ->where('user_id', Auth::user()->id)
          ->ignore($this->status->id, 'id')
      ],
      'color' => ['nullable', 'max:255', 'min:3'],
      'description' => ['nullable']
    ];
  }

  public function data(): array
  {
    return [
      ...$this->only(['name', 'slug', 'color', 'description', 'is_pinned']),
      'user_id' => Auth::user()->id
    ];
  }
}
