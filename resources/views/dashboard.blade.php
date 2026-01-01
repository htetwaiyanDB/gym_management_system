<x-app-layout>
   <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

            <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-2xl shadow-sm p-6 border border-blue-100 bg-gradient-to-br from-blue-50 via-dark to-dark dark:from-blue-900/20 dark:via-gray-900 dark:to-gray-900 dark:border-blue-900/40">
                    <p class="text-sm uppercase tracking-wide text-blue-500 dark:text-blue-300">Total Users</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900 dark:text-dark">{{ number_format($totalUsers) }}</p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">New members added to the platform.</p>

                    </div>

                                    <div class="rounded-2xl shadow-sm p-6 border border-emerald-100 bg-gradient-to-br from-emerald-50 via-dark to-dark dark:from-emerald-900/20 dark:via-gray-900 dark:to-gray-900 dark:border-emerald-900/40">
                    <p class="text-sm uppercase tracking-wide text-emerald-500 dark:text-emerald-300">Active Subscriptions</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900 dark:text-dark">{{ number_format($totalSubscriptions) }}</p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">Membership plans across the gym.</p>
                </div>
                <div class="rounded-2xl shadow-sm p-6 border border-orange-100 bg-gradient-to-br from-orange-50 via-dark to-dark dark:from-orange-900/20 dark:via-gray-900 dark:to-gray-900 dark:border-orange-900/40">
                    <p class="text-sm uppercase tracking-wide text-orange-500 dark:text-orange-300">Trainer Bookings</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900 dark:text-dark">{{ number_format($totalTrainerBookings) }}</p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">Sessions booked with trainers.</p>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-3">
                <div class="bg-dark dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-dark">Users Growth</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Last 6 months</span>
                    </div>
                    <div class="mt-6 h-56 rounded-xl bg-gradient-to-br from-blue-50 via-dark to-dark dark:from-blue-900/20 dark:via-gray-900 dark:to-gray-900 p-4">
                        <canvas id="usersChart"></canvas>
                    </div>
                </div>
                <div class="bg-dark dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-dark">Subscriptions</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Last 6 months</span>
                    </div>
                    <div class="mt-6 h-56 rounded-xl bg-gradient-to-br from-emerald-50 via-dark to-dark dark:from-emerald-900/20 dark:via-gray-900 dark:to-gray-900 p-4">
                        <canvas id="subscriptionsChart"></canvas>
                    </div>
                </div>
                <div class="bg-dark dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-dark">Trainer Bookings</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Last 6 months</span>
                    </div>
                    <div class="mt-6 h-56 rounded-xl bg-gradient-to-br from-orange-50 via-dark to-dark dark:from-orange-900/20 dark:via-gray-900 dark:to-gray-900 p-4">
                        <canvas id="trainerBookingsChart"></canvas>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-3">
                <div class="bg-dark dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-dark">Latest Users</h3>
                        <a class="text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('users.index') }}">View all</a>
                    </div>
                    <div class="mt-4 space-y-4">
                        @forelse ($latestUsers as $user)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-dark">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $user->created_at?->format('M d, Y') }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">No users yet.</p>
                        @endforelse
                    </div>
                </div>
                <div class="bg-dark dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-dark">Latest Subscriptions</h3>
                        <a class="text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('subscriptions.index') }}">View all</a>
                    </div>
                    <div class="mt-4 space-y-4">
                        @forelse ($latestSubscriptions as $subscription)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-dark">
                                        {{ $subscription->member?->name ?? 'Member' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $subscription->plan?->name ?? 'Membership' }}
                                    </p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $subscription->start_date?->format('M d, Y') }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">No subscriptions yet.</p>
                        @endforelse
                    </div>
                </div>
                <div class="bg-dark dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-dark">Latest Trainer Bookings</h3>
                        <a class="text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('trainer-bookings.index') }}">View all</a>
                    </div>
                    <div class="mt-4 space-y-4">
                        @forelse ($latestTrainerBookings as $booking)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-dark">
                                        {{ $booking->member?->name ?? 'Member' }} → {{ $booking->trainer?->name ?? 'Trainer' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $booking->status ?? 'Scheduled' }}
                                    </p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $booking->session_datetime?->format('M d, Y') }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">No trainer bookings yet.</p>
                        @endforelse
                    </div>
                </div>
            </section>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartLabels = @json($chartLabels);
        const userCounts = @json($userCounts);
        const subscriptionCounts = @json($subscriptionCounts);
        const trainerBookingCounts = @json($trainerBookingCounts);

        const sharedOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: {
                            size: 11,
                        },
                    },
                },
                y: {
                    beginAtZero: true,
                    grace: '15%',
                    ticks: {
                        color: '#94a3b8',
                        precision: 0,
                    },
                    grid: {
                        color: 'rgba(148, 163, 184, 0.2)',
                    },
                },
            },
        };

        const gradientFill = (context, color) => {
            const gradient = context.createLinearGradient(0, 0, 0, 220);
            gradient.addColorStop(0, color.replace('1)', '0.35)'));
            gradient.addColorStop(1, color.replace('1)', '0)'));
            return gradient;
        };

        const createLineChart = (canvasId, data, borderColor) => {
            const ctx = document.getElementById(canvasId).getContext('2d');
            const backgroundColor = gradientFill(ctx, borderColor);

            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        data,
                        borderColor,
                        backgroundColor,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: borderColor,
                        pointBorderWidth: 2,
                        borderWidth: 3,
                    }],
                },
                options: sharedOptions,
            });
        };

        createLineChart('usersChart', userCounts, 'rgba(59, 130, 246, 1)');
        createLineChart('subscriptionsChart', subscriptionCounts, 'rgba(16, 185, 129, 1)');
        createLineChart('trainerBookingsChart', trainerBookingCounts, 'rgba(249, 115, 22, 1)');
    </script>
</x-app-layout>


