<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Attendance Scan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full {{ $status === 'success' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-100' : 'bg-rose-100 text-rose-700 dark:bg-rose-900 dark:text-rose-100' }}">
                            @if ($status === 'success')
                                ✓
                            @else
                                !
                            @endif
                        </span>
                        <div>
                            <h3 class="text-lg font-semibold">
                                {{ $status === 'success' ? 'Scan recorded' : 'Scan failed' }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $message }}
                            </p>
                        </div>
                    </div>

                    @if ($status === 'success' && $timestamp)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-4 text-sm text-gray-700 dark:text-gray-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold">Action</span>
                                <span class="capitalize">{{ str_replace('_', ' ', $action) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-semibold">Time</span>
                                <span>{{ $timestamp->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                    @endif

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        You can close this page after scanning.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
