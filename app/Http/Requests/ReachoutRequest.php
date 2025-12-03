<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReachoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'message' => ['required'],
            'is_read' => ['boolean'],
            'user_id' => ['required', 'exists:users'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
