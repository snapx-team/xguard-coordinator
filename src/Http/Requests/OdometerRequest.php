<?php

namespace Xguard\Coordinator\Http\Requests;

class OdometerRequest extends BaseFormRequest
{

    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
    public function messages(): array
    {
        return [];
    }
}
