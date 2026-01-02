<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Subscriptions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Your Booked Sessions</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        View upcoming sessions, duty times, and member details for your bookings.
                    </p>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold">Member</th>
                                    <th class="px-4 py-2 text-left font-semibold">Session Time</th>
                                    <th class="px-4 py-2 text-left font-semibold">Duration</th>
                                    <th class="px-4 py-2 text-left font-semibold">Sessions</th>
                                    <th class="px-4 py-2 text-left font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($bookings as $booking)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-gray-800 dark:text-gray-100">
                                                {{ $booking->member?->name ?? 'Member' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $booking->member?->email ?? '—' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                            {{ $booking->session_datetime->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                            {{ $booking->duration_minutes }} mins
                                        </td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                            {{ $booking->sessions_count }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $booking->status === 'completed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-900 dark:text-gray-300' }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                            No bookings found yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
