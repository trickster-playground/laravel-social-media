<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

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
      'attachments' => 'nullable|array|max:10',
      'attachments.*' => [
        'file',
        File::types(self::$extensions)->max(500 * 1024 * 1024),
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
      'attachments.*' => 'Trickster say no to this file.',
    ];
  }
}
