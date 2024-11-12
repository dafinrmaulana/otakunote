<?php

namespace App\Http\Requests\Series;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SeriesUpdateRequest extends FormRequest
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
      'title' => [
        'required',
        'max:255',
        'min:3',
        Rule::unique('series', 'title')
          ->where('user_id', Auth::user()->id)
          ->ignore($this->series->id, 'id')
      ],
      'slug' => [
        'required',
        'max:255',
        'min:3',
        'regex:/^[a-z0-9-]+$/',
        Rule::unique('series', 'slug')
          ->where('user_id', Auth::user()->id)
          ->ignore($this->series->id, 'id')
      ],
      'thumbnail' => ['nullable', 'max:2048'],
      'rating' => ['required', 'numeric', 'between:0,10'],
      'author' => ['nullable', 'string', 'max:255', 'min:3'],
      'source_url' => ['nullable', 'url'],
      'category_id' => ['required', 'exists:categories,id'],
      'status_id' => ['required', 'exists:statuses,id'],
      'media' => ['required', 'in:video,book'],
      'description' => ['nullable']
    ];
  }

  public function messages(): array
  {
    return [
      'status_id.required' => 'The status field is required.',
      'status_id.exists' => 'The selected status is invalid.',
      'category_id.required' => 'The category field is required.',
      'category_id.exists' => 'The selected category is invalid.',
    ];
  }

  public function data(): array
  {
    if ($this->hasFile('thumbnail')) {
      $this->series->thumbnail && Storage::delete($this->series->thumbnail);

      $thumbnailPath = $this->file('thumbnail')->store('images/series/thumbnail', 'local');
    } else {
      $thumbnailPath = $this->thumbnail;
    }

    return [
      ...$this->only([
        'title',
        'slug',
        'rating',
        'author',
        'studio',
        'is_pinned',
        'source_url',
        'media',
        'category_id',
        'status_id'
      ]),
      'thumbnail' => $thumbnailPath,
      'user_id' => Auth::user()->id
    ];
  }
}
