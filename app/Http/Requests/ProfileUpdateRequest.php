<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
   */
  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255'],
      'email' => [
        'required',
        'string',
        'lowercase',
        'email',
        'max:255',
        Rule::unique(User::class)->ignore($this->user()->id),
        'profile_image' => ['required', 'image', 'max:2048'],
      ],
    ];
  }

  public function data(): array
  {
    if ($this->profile_image && $this->hasFile('profile_image')) {
      $this->user->profile_image && Storage::delete($this->user->profile_image);
      return [
        ...$this->only([
          'name',
          'email'
        ]),
        'profile_image' => $this->file('profile_image')->store('images/user/profile', 'local')
      ];
    } else {
      return [
        ...$this->only([
          'name',
          'email',
          'profile_image'
        ])
      ];
    }
  }
}
