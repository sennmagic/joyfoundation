<x-layouts.app :title="$page->meta_title ?? $page->title" :description="$page->meta_description" :site="$site">
    <main class="bg-white">
        <x-site.header :nav="$site['nav']['items'] ?? []" :overlay="false" />

        @php $intro = $site['contact']['intro'] ?? ''; @endphp
        <section id="contact-details" class="px-gutter pt-stack-md pb-section-y sm:px-gutter-lg sm:pb-section-y-lg" aria-labelledby="contact-heading">
            <div class="mx-auto max-w-7xl">
                <header class="mb-stack-md">
                    @forelse ($sections->where('type', 'hero') as $section)
                        <h1 @if ($loop->first) id="contact-heading" @endif class="font-heading text-5xl font-semibold tracking-tight sm:text-heading">
                            {{ $section->data['heading_prefix'] ?? '' }}{{ $section->data['heading_highlight'] ?? '' }}{{ $section->data['heading_suffix'] ?? '' }}
                        </h1>
                    @empty
                        <h1 id="contact-heading" class="font-heading text-5xl font-semibold tracking-tight sm:text-heading">{{ $page->title }}</h1>
                    @endforelse
                    <p class="mt-stack-sm max-w-xl whitespace-pre-line text-description break-words text-body [overflow-wrap:anywhere]">{{ is_string($intro) && trim($intro) !== '' ? $intro : 'For enquiries about our work, volunteering or partnerships.' }}</p>
                </header>

                <div data-contact-layout class="grid items-start gap-stack-md xl:grid-cols-12 xl:gap-stack-lg">
                    <div id="contact-form" class="min-w-0 scroll-mt-stack-sm xl:col-span-7">
                        <h2 id="enquiry-heading" class="mb-stack-sm font-heading text-xl font-semibold">Send a message</h2>

                        @if (session('contact_sent'))
                            <div role="status" tabindex="-1" autofocus class="mb-stack-md border-l-2 border-primary bg-cream p-5">
                                <p class="font-semibold">Your message has been received.</p>
                                <p class="mt-1 text-sm text-body">Thank you for contacting JOY.</p>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div role="alert" tabindex="-1" autofocus class="mb-stack-md border-l-2 border-red-700 bg-red-50 p-5 text-red-800">
                                <p class="font-semibold">Please check your message.</p>
                                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                    @foreach ($errors->messages() as $field => $messages)
                                        <li>
                                            @if (in_array($field, ['name', 'email', 'topic', 'message'], true))
                                                <a href="#contact-{{ $field }}" class="underline underline-offset-4">{{ $messages[0] }}</a>
                                            @else
                                                {{ $messages[0] }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST" aria-labelledby="enquiry-heading" class="space-y-6">
                            @csrf
                            <p class="text-sm text-muted">All fields are required.</p>
                            <div class="grid gap-6 xl:grid-cols-2">
                                @foreach (['name' => 'Your name', 'email' => 'Email address'] as $field => $label)
                                    <div class="min-w-0">
                                        <label for="contact-{{ $field }}" class="mb-2 block text-sm font-semibold">{{ $label }}</label>
                                        <input id="contact-{{ $field }}" name="{{ $field }}" type="{{ $field === 'email' ? 'email' : 'text' }}" autocomplete="{{ $field }}" required maxlength="{{ $field === 'email' ? 254 : 120 }}" value="{{ is_string(old($field)) ? old($field) : '' }}" class="form-control" aria-invalid="{{ $errors->has($field) ? 'true' : 'false' }}" @if ($errors->has($field)) aria-describedby="contact-{{ $field }}-error" @endif>
                                        @error($field)<p id="contact-{{ $field }}-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                                    </div>
                                @endforeach
                            </div>

                            <div>
                                <label for="contact-topic" class="mb-2 block text-sm font-semibold">Subject</label>
                                <select id="contact-topic" name="topic" required class="form-control" aria-invalid="{{ $errors->has('topic') ? 'true' : 'false' }}" @if ($errors->has('topic')) aria-describedby="contact-topic-error" @endif>
                                    <option value="">Select a subject</option>
                                    @foreach (\App\Models\Enquiry::TOPICS as $value => $label)
                                        <option value="{{ $value }}" @selected(old('topic') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('topic')<p id="contact-topic-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="contact-message" class="mb-2 block text-sm font-semibold">Your message</label>
                                <textarea id="contact-message" name="message" rows="5" required minlength="10" maxlength="5000" class="form-control resize-y" aria-invalid="{{ $errors->has('message') ? 'true' : 'false' }}" aria-describedby="contact-message-hint{{ $errors->has('message') ? ' contact-message-error' : '' }}">{{ is_string(old('message')) ? old('message') : '' }}</textarea>
                                <p id="contact-message-hint" class="mt-2 text-sm text-muted">10–5,000 characters.</p>
                                @error('message')<p id="contact-message-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                            </div>

                            <div hidden aria-hidden="true">
                                <label for="contact-website">Leave this field empty</label>
                                <input id="contact-website" name="website" type="text" tabindex="-1" autocomplete="off">
                            </div>

                            <div>
                                <button type="submit" class="inline-flex min-h-14 w-full items-center justify-center rounded-md bg-primary px-7 py-3.5 text-base font-semibold text-navy transition hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-navy sm:w-auto">
                                    Send message
                                </button>
                                <p class="mt-4 max-w-xl text-sm text-muted">Your details are saved so our team can reply. Please don't send sensitive personal information.</p>
                            </div>
                        </form>
                    </div>

                    <aside id="contact-information" aria-labelledby="contact-information-heading" class="min-w-0 border-t border-navy/15 pt-stack-md xl:col-span-5 xl:border-t-0 xl:pt-0">
                        <h2 id="contact-information-heading" class="mb-stack-sm font-heading text-xl font-semibold">Contact details</h2>
                        <x-site.contact-details :contact="$site['contact'] ?? []" />
                    </aside>
                </div>

                @php
                    $mapEmbed = $site['contact']['map_embed_url'] ?? '';
                    $mapEmbed = is_string($mapEmbed) ? trim($mapEmbed) : '';
                @endphp
                @if (strlen($mapEmbed) <= 4096 && filter_var($mapEmbed, FILTER_VALIDATE_URL) && preg_match(\App\Models\Section::MAP_EMBED_URL_PATTERN, $mapEmbed))
                    <section aria-labelledby="contact-map-heading" class="mt-stack-lg border-t border-navy/15 pt-stack-md">
                        <h2 id="contact-map-heading" class="font-heading text-2xl font-semibold">Find us</h2>
                        <iframe data-contact-map src="{{ $mapEmbed }}" title="JOY Foundation office location on Google Maps"
                            class="mt-stack-sm h-80 w-full rounded-md border-0 sm:h-96" width="1200" height="384"
                            loading="lazy" referrerpolicy="no-referrer" allowfullscreen></iframe>
                    </section>
                @endif
            </div>
        </section>

        {{-- Keep edited page sections; the original seed intro is replaced by the contact introduction. --}}
        @foreach ($sections->where('type', '!=', 'hero') as $section)
            @unless ($section->type === 'text_band' && ($section->data['text'] ?? '') === config('home.intro.text'))
                @include("sections.{$section->type}", ['content' => $section->data])
            @endunless
        @endforeach
    </main>
</x-layouts.app>
