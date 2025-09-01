<template>
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-gray-50 to-emerald-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="flex items-center justify-center mb-6">
                    <span class="text-4xl font-black text-emerald-600 mr-1"
                        >CAL</span
                    >
                    <span class="text-4xl font-light text-slate-600"
                        >TRACK</span
                    >
                </div>
                <h2 class="text-xl font-medium text-slate-700">
                    Aici e suferința 🥹
                </h2>
            </div>

            <!-- Login Form -->
            <div
                class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-8"
            >
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Error Message -->
                    <div
                        v-if="form.errors.username"
                        class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200/50 rounded-xl p-4"
                    >
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-6 h-6 bg-red-100 rounded-lg flex items-center justify-center"
                            >
                                <svg
                                    class="w-4 h-4 text-red-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-red-800">
                                {{ form.errors.username }}
                            </p>
                        </div>
                    </div>

                    <!-- Username Field -->
                    <div>
                        <label
                            for="username"
                            class="block text-sm font-medium text-slate-700 mb-2"
                        >
                            Utilizator
                        </label>
                        <input
                            id="username"
                            v-model="form.username"
                            type="text"
                            autocomplete="username"
                            required
                            :class="[
                                'w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white/50 backdrop-blur-sm transition-all duration-200',
                                {
                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                        form.errors.username,
                                },
                            ]"
                            placeholder="Numele de utilizator"
                        />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label
                            for="password"
                            class="block text-sm font-medium text-slate-700 mb-2"
                        >
                            Parola
                        </label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            autocomplete="current-password"
                            required
                            :class="[
                                'w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white/50 backdrop-blur-sm transition-all duration-200',
                                {
                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                        form.errors.password,
                                },
                            ]"
                            placeholder="••••••••"
                        />
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        :class="[
                            'w-full bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02]',
                            {
                                'opacity-50 cursor-not-allowed transform-none hover:scale-100':
                                    form.processing,
                            },
                        ]"
                    >
                        <span
                            v-if="form.processing"
                            class="flex items-center justify-center space-x-2"
                        >
                            <svg
                                class="animate-spin w-5 h-5 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            <span>Se conectează...</span>
                        </span>
                        <span v-else>Conectează-te</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    username: "",
    password: "",
});

const submit = () => {
    form.post(route("login.submit"), {
        onFinish: () => form.reset("password"),
    });
};
</script>
