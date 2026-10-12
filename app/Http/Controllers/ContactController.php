<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnquiryRequest;
use App\Models\Enquiry;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreEnquiryRequest $request): RedirectResponse
    {
        Enquiry::query()->create($request->safe()->only(['name', 'email', 'topic', 'message']));

        return redirect()->to(route('page', ['slug' => 'contact']).'#contact-form')->with('contact_sent', true);
    }
}
