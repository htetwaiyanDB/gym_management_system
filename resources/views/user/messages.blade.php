<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Message the Administrator</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Send requests or updates directly to the admin panel.
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    @if(session('status'))
                        <div class="bg-emerald-50 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-100 rounded-lg px-4 py-3">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-rose-50 text-rose-700 dark:bg-rose-900 dark:text-rose-100 rounded-lg px-4 py-3">
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(!$admin)
                        <p class="text-sm text-rose-500">Administrator account not found. Please contact support.</p>
                    @else
                        <div class="space-y-3 max-h-96 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            @forelse($messages as $message)
                                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-md rounded-lg px-4 py-2 text-sm {{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-900 dark:text-gray-200' }}">
                                        <p>{{ $message->body }}</p>
                                        <p class="mt-1 text-xs opacity-70">
                                            {{ $message->created_at->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No messages yet.</p>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('user.messages.store') }}" class="space-y-3">
                            @csrf
                            <textarea
                                name="body"
                                rows="4"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Write your message to the admin..."
                                required
                            >{{ old('body') }}</textarea>
                            <div class="flex justify-end">
                                <x-primary-button>Send Message</x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
