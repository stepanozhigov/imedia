<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return match($this->method()) {
            'POST' => $this->store(),
            'PUT','PATCH' => $this->update()
        };
    }

    public function store() {
        return [
            'name' => ['required','unique:users,name'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/']
        ];
    }

    public function update() {
        return [
            'name' => ['required',Rule::unique('users')->ignore($this->user())],
            'email' => ['required','email',Rule::unique('users')->ignore($this->user())],
            'password' => ['required','regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/']
        ];
    }
}
