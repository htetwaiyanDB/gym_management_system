<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Subscriptions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-4 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Subscription Management</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                Track active members, place subscriptions on hold, and resume when they return.
                            </p>
                        </div>
                        <button
                            type="button"
                            id="add-subscription-button"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        >
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full border border-white">
                                +
                            </span>
                            Add Subscription
                        </button>
                    </div>

                    <div id="subscriptions-message" class="rounded-md bg-gray-50 dark:bg-gray-900 px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                        Subscriptions are loading.
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-4 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold">ID</th>
                                    <th class="px-4 py-2 text-left font-semibold">Username</th>
                                    <th class="px-4 py-2 text-left font-semibold">Type</th>
                                    <th class="px-4 py-2 text-left font-semibold">Details</th>
                                    <th class="px-4 py-2 text-left font-semibold">Price</th>
                                    <th class="px-4 py-2 text-left font-semibold">Activated Date</th>
                                    <th class="px-4 py-2 text-left font-semibold">Expire Date</th>
                                    <th class="px-4 py-2 text-left font-semibold">Status</th>
                                    <th class="px-4 py-2 text-left font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody id="subscriptions-table" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr>
                                    <td colspan="9" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">No subscriptions loaded.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="subscription-modal" class="fixed inset-0 z-50 hidden">
        <div data-subscription-modal-close class="absolute inset-0 bg-gray-900/60"></div>
        <div class="relative mx-auto mt-12 w-[92%] max-w-md sm:mt-20 sm:max-w-lg rounded-xl bg-white p-5 sm:p-6 shadow-xl dark:bg-gray-800 max-h-[85vh] overflow-y-auto">

            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Add Subscription</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Choose a member and subscription plan.</p>
                </div>
                <button
                    type="button"
                    data-subscription-modal-close
                    class="inline-flex h-8 w-8 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700"
                >
                    &times;
                </button>
            </div>

            <div id="subscription-modal-message" class="mt-4 rounded-md bg-gray-50 px-4 py-3 text-sm text-gray-700 dark:bg-gray-900 dark:text-gray-200">
                Select a member and plan to create a subscription.
            </div>

            <form id="subscription-form" class="mt-4 space-y-4">
                <div>
                    <label for="subscription-member" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Member</label>
                    <select
                        id="subscription-member"
                        name="member_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                    >
                        <option value="">Loading members...</option>
                    </select>
                </div>
                <div>
                    <label for="subscription-plan" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Plan</label>
                    <select
                        id="subscription-plan"
                        name="membership_plan_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                    >
                        <option value="">Loading plans...</option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button
                        type="button"
                        data-subscription-modal-close
                        class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-sm font-semibold text-white hover:bg-blue-500"
                    >
                        Save Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        const subscriptionMessage = document.getElementById('subscriptions-message');
        const subscriptionsTable = document.getElementById('subscriptions-table');
        const addSubscriptionButton = document.getElementById('add-subscription-button');
        const subscriptionModal = document.getElementById('subscription-modal');
        const subscriptionForm = document.getElementById('subscription-form');
        const subscriptionMemberSelect = document.getElementById('subscription-member');
        const subscriptionPlanSelect = document.getElementById('subscription-plan');
        const subscriptionModalMessage = document.getElementById('subscription-modal-message');
        const subscriptionModalCloseButtons = document.querySelectorAll('[data-subscription-modal-close]');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let subscriptionOptionsLoaded = false;

        const setMessage = (message, type = 'info') => {
            const base = 'rounded-md px-4 py-3 text-sm ';
            const styles = {
                info: 'bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-200',
                success: 'bg-emerald-50 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-100',
                error: 'bg-rose-50 dark:bg-rose-900 text-rose-700 dark:text-rose-100',
            };
            subscriptionMessage.className = base + (styles[type] || styles.info);
            subscriptionMessage.textContent = message;
        };

                const setModalMessage = (message, type = 'info') => {
            const base = 'rounded-md px-4 py-3 text-sm ';
            const styles = {
                info: 'bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-200',
                success: 'bg-emerald-50 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-100',
                error: 'bg-rose-50 dark:bg-rose-900 text-rose-700 dark:text-rose-100',
            };
            subscriptionModalMessage.className = 'mt-4 ' + base + (styles[type] || styles.info);
            subscriptionModalMessage.textContent = message;
        };


        const apiFetch = async (url, options = {}) => {
            const response = await fetch(url, {
                credentials: 'same-origin',
                ...options,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    ...(options.headers || {}),
                },
            });

            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                const message = data.message || 'Request failed.';
                setMessage(message, 'error');
                throw new Error(message);
            }
            return data;
        };

            const populateSelect = (selectElement, options, placeholder) => {
            if (!options.length) {
                selectElement.innerHTML = `<option value="">${placeholder}</option>`;
                selectElement.disabled = true;
                return;
            }

            selectElement.disabled = false;
            selectElement.innerHTML = [
                '<option value="">Select an option</option>',
                ...options,
            ].join('');
        };

        const loadSubscriptionOptions = async () => {
            if (subscriptionOptionsLoaded) return;

            try {
                setModalMessage('Loading members and plans...');
                const data = await apiFetch('/admin/subscriptions/options');
                const memberOptions = (data.members || []).map((member) => {
                    const phone = member.phone ? ` (${member.phone})` : '';
                    return `<option value="${member.id}">${member.name}${phone}</option>`;
                });
                const planOptions = (data.plans || []).map((plan) => {
                    const duration = plan.duration_days ? `${plan.duration_days} days` : 'No duration';
                    return `<option value="${plan.id}">${plan.name} - ${duration}</option>`;
                });

                populateSelect(subscriptionMemberSelect, memberOptions, 'No active members found.');
                populateSelect(subscriptionPlanSelect, planOptions, 'No plans available.');
                subscriptionOptionsLoaded = true;
                setModalMessage('Select a member and plan to create a subscription.');
            } catch (error) {
                console.error(error);
                setModalMessage('Failed to load members or plans.', 'error');
            }
        };

        const openSubscriptionModal = async () => {
            subscriptionModal.classList.remove('hidden');
            await loadSubscriptionOptions();
        };

        const closeSubscriptionModal = () => {
            subscriptionModal.classList.add('hidden');
            subscriptionForm.reset();
        };


        const formatDate = (value) => {
            if (!value) return '-';
            const date = new Date(value);
            if (Number.isNaN(date.getTime())) return value;
            return new Intl.DateTimeFormat('en-GB', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
            }).format(date);
        };

        const formatCurrency = (value) => {
            if (value === null || value === undefined) return '-';
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'MMK',
                maximumFractionDigits: 0,
            }).format(value);
        };

        const statusBadge = (status) => {
            const classes = {
                Active: 'bg-emerald-100 text-emerald-700',
                'On Hold': 'bg-amber-100 text-amber-700',
                Expired: 'bg-rose-100 text-rose-700',
            };
            const className = classes[status] || 'bg-gray-100 text-gray-700';
            return `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold ${className}">${status}</span>`;
        };

        const renderSubscriptions = (subscriptions) => {
            if (!subscriptions.length) {
                subscriptionsTable.innerHTML = '<tr><td colspan="9" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">No subscriptions found.</td></tr>';
                return;
            }

            subscriptionsTable.innerHTML = subscriptions.map((subscription) => {
                const details = subscription.duration_days
                    ? `${Math.ceil(subscription.duration_days / 30)} month(s)`
                    : '-';
                const buttonLabel = subscription.is_on_hold ? 'Resume' : 'Hold';
                const buttonClass = subscription.is_on_hold
                    ? 'bg-blue-600 hover:bg-blue-500'
                    : 'bg-amber-600 hover:bg-amber-500';
                const action = subscription.is_on_hold ? 'resume' : 'hold';
                const disabled = subscription.status === 'Expired';

                return `
                    <tr>
                        <td class="px-4 py-3">${subscription.id}</td>
                        <td class="px-4 py-3">${subscription.member_name}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                ${subscription.plan_name}
                            </span>
                        </td>
                        <td class="px-4 py-3">${details}</td>
                        <td class="px-4 py-3">${formatCurrency(subscription.price)}</td>
                        <td class="px-4 py-3">${formatDate(subscription.start_date)}</td>
                        <td class="px-4 py-3">${formatDate(subscription.end_date)}</td>
                        <td class="px-4 py-3">${statusBadge(subscription.status)}</td>
                        <td class="px-4 py-3">
                            <button
                                type="button"
                                data-id="${subscription.id}"
                                data-action="${action}"
                                class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold text-white ${buttonClass} ${disabled ? 'opacity-50 cursor-not-allowed' : ''}"
                                ${disabled ? 'disabled' : ''}
                            >
                                ${buttonLabel}
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        };

        const loadSubscriptions = async () => {
            try {
                setMessage('Loading subscriptions...');
                const data = await apiFetch('/admin/subscriptions');
                renderSubscriptions(data.subscriptions || []);
                setMessage('Subscriptions updated.', 'success');
            } catch (error) {
                console.error(error);
            }
        };

        addSubscriptionButton.addEventListener('click', () => {
            setModalMessage('Select a member and plan to create a subscription.');
            openSubscriptionModal();
        });

        subscriptionModalCloseButtons.forEach((button) => {
            button.addEventListener('click', closeSubscriptionModal);
        });

        subscriptionForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const memberId = subscriptionMemberSelect.value;
            const planId = subscriptionPlanSelect.value;

            if (!memberId || !planId) {
                setModalMessage('Please select both a member and a plan.', 'error');
                return;
            }

            try {
                setModalMessage('Creating subscription...');
                await apiFetch('/admin/subscriptions', {
                    method: 'POST',
                    body: JSON.stringify({
                        member_id: memberId,
                        membership_plan_id: planId,
                    }),
                });
                closeSubscriptionModal();
                await loadSubscriptions();
                setMessage('Subscription created successfully.', 'success');
            } catch (error) {
                console.error(error);
                setModalMessage('Unable to create subscription.', 'error');
            }
        });

        subscriptionsTable.addEventListener('click', async (event) => {
            const button = event.target.closest('button[data-action]');
            if (!button || button.disabled) return;

            const subscriptionId = button.dataset.id;
            const action = button.dataset.action;

            try {
                setMessage('Updating subscription...');
                await apiFetch(`/admin/subscriptions/${subscriptionId}/${action}`, { method: 'POST' });
                await loadSubscriptions();
            } catch (error) {
                console.error(error);
            }
        });

        loadSubscriptions();
    </script>
</x-app-layout>
