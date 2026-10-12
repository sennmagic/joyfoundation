<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Enquiry;
use App\Models\Page;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnquiryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        abort_unless(Page::query()->where('slug', 'contact')->where('is_published', true)->exists(), 404);

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email:rfc', 'max:254'],
            'topic' => ['required', 'string', Rule::in(array_keys(Enquiry::TOPICS))],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'website' => ['prohibited'],
        ];
    }

    protected function getRedirectUrl(): string
    {
        return route('page', ['slug' => 'contact']).'#contact-form';
    }
}
