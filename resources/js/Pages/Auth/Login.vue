<template>
    <div
        class="min-h-screen bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-primary-600 mb-2">
                    🍗 Caltrack
                </h1>
                <h2 class="text-2xl font-semibold text-gray-900">
                    Aici e suferința 🥹
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <div
                        v-if="form.errors.username"
                        class="bg-red-50 border border-red-200 rounded-lg p-4"
                    >
                        <p class="text-sm text-red-600">
                            {{ form.errors.username }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="username"
                            class="block text-sm font-medium text-gray-700 mb-2"
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
                                'input-field',
                                {
                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                        form.errors.username,
                                },
                            ]"
                            placeholder=""
                        />
                    </div>

                    <div>
                        <label
                            for="password"
                            class="block text-sm font-medium text-gray-700 mb-2"
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
                                'input-field',
                                {
                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                        form.errors.password,
                                },
                            ]"
                            placeholder="••••••••"
                        />
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        :class="[
                            'w-full btn-primary py-3 text-base',
                            {
                                'opacity-50 cursor-not-allowed':
                                    form.processing,
                            },
                        ]"
                    >
                        <span
                            v-if="form.processing"
                            class="flex items-center justify-center"
                        >
                            <svg
                                class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
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
                            Se conectează...
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
