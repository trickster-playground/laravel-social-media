<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class StorePostRequest extends FormRequest
{
  public static array $extensions =
  [
    'jpg', 'png', 'jpeg', 'gif', 'svg', 'pdf', 'doc', 'docx', 'mp3', 'mp4', 'csv', 'xlsx', 'xls', 'ppt', 'pptx', 'zip'
  ];


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
      'body' => 'nullable|string',
      'attachments' => [
        'nullable', 'array', 'max:10',
        function ($attribute, $value, $fail) {
          $totalSize = collect($value)->sum(fn (UploadedFile $file) => $file->getSize());
          $maxSize = 1 * 1024 * 1024 * 1024;
          if ($totalSize > $maxSize) {
            $fail('The total size of the attachments must not exceed 1GB.');
          }
        }
      ],
      'attachments.*' => [
        'file',
        File::types(self::$extensions),
      ],
      'user_id' => 'required|exists:users,id',
    ];
  }

  protected function prepareForValidation()
  {
    $this->merge([
      'user_id' => auth()->user()->id,
      'body' => $this->input('body') ?: '',
    ]);
  }

  public function messages()
  {
    return [
      'attachments.*.file' => 'Trickster say must be a file.',
      'attachments.*.mimes' => 'Trickster say you must see the rules',
    ];
  }
}
