<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Check In / Out') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-3 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Scan the gym QR code</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        When you arrive, scan the trainer QR code displayed at the gym to record your check-in.
                        Scan it again when you leave to record your check-out.
                    </p>
                    @if($latestScan)
                        <div class="mt-4 flex flex-col gap-1 text-sm text-gray-700 dark:text-gray-200">
                            <span>
                                Latest action: <strong class="uppercase">{{ str_replace('_', ' ', $latestScan->action) }}</strong>
                            </span>
                            <span>Time: {{ $latestScan->scanned_at->format('M d, Y h:i A') }}</span>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            No scans recorded yet.
                        </p>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Recent scans</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold">Action</th>
                                    <th class="px-4 py-2 text-left font-semibold">Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($recentScans as $scan)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                            {{ ucwords(str_replace('_', ' ', $scan->action)) }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                            {{ $scan->scanned_at->format('M d, Y h:i A') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                            No scans yet.
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
