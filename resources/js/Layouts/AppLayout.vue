<!-- resources/js/Layouts/AppLayout.vue - FIXED VERSION -->
<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Simple Navigation -->
        <nav
            class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-50"
        >
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo and Navigation Links -->
                    <div class="flex items-center space-x-6">
                        <Link
                            href="/"
                            class="text-2xl font-bold text-green-600"
                        >
                            🍗 Caltrack
                        </Link>

                        <div class="hidden md:flex space-x-4">
                            <Link
                                href="/"
                                :class="[
                                    'px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                                    currentPage?.component?.startsWith(
                                        'FoodEntries',
                                    )
                                        ? 'bg-green-100 text-green-700'
                                        : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100',
                                ]"
                            >
                                📊 Jurnal
                            </Link>

                            <Link
                                href="/food-items"
                                :class="[
                                    'px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                                    currentPage?.component?.startsWith(
                                        'FoodItems',
                                    )
                                        ? 'bg-green-100 text-green-700'
                                        : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100',
                                ]"
                            >
                                📋 Catalog
                            </Link>

                            <span
                                class="px-3 py-2 text-sm font-medium text-gray-400 cursor-not-allowed"
                            >
                                🏋️‍♀️ Exerciții
                            </span>
                        </div>
                    </div>

                    <!-- Right side: Calculator + User -->
                    <div class="flex items-center space-x-4">
                        <!-- Calculator Button -->
                        <div class="relative">
                            <button
                                @click="showCalculator = !showCalculator"
                                class="p-2 text-gray-500 hover:text-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                                <i class="fa fa-calculator text-lg"></i>
                            </button>

                            <!-- Calculator Dropdown -->
                            <div
                                v-if="showCalculator"
                                class="absolute right-0 top-full mt-2 w-72 bg-white rounded-xl shadow-lg border p-4 z-50"
                                @click.stop
                            >
                                <h4
                                    class="text-sm font-semibold text-gray-700 mb-3"
                                >
                                    Calculator rapid
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-2">
                                        <input
                                            v-model="calculator.calories"
                                            type="number"
                                            placeholder="calorii"
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500"
                                        />
                                        <span
                                            class="text-sm font-bold text-gray-700 w-16 text-right"
                                        >
                                            {{ calculatedCalories }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input
                                            v-model="calculator.protein"
                                            type="number"
                                            step="0.01"
                                            placeholder="proteine"
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500"
                                        />
                                        <span
                                            class="text-sm font-bold text-gray-700 w-16 text-right"
                                        >
                                            {{ calculatedProtein }}
                                        </span>
                                    </div>
                                    <input
                                        v-model="calculator.multiplier"
                                        type="number"
                                        step="0.01"
                                        placeholder="multiplier"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- User Info (Simple) -->
                        <div class="flex items-center space-x-2">
                            <span class="text-lg">
                                {{ getUserIcon }}
                            </span>
                            <span class="text-sm font-medium text-gray-700">
                                {{ getUserName }}
                            </span>

                            <!-- Logout Button -->
                            <form @submit.prevent="logout" class="inline">
                                <button
                                    type="submit"
                                    class="ml-2 text-sm text-gray-500 hover:text-gray-700 px-2 py-1 rounded transition-colors"
                                    title="Deconectare"
                                >
                                    <i class="fa fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Mobile menu button -->
                        <button
                            @click="showMobileMenu = !showMobileMenu"
                            class="md:hidden p-2 text-gray-500 hover:text-gray-700 rounded-lg"
                        >
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div
                    v-if="showMobileMenu"
                    class="md:hidden py-4 border-t border-gray-200"
                >
                    <div class="space-y-2">
                        <Link
                            href="/"
                            class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100"
                            :class="{
                                'bg-green-100 text-green-700':
                                    currentPage?.component?.startsWith(
                                        'FoodEntries',
                                    ),
                            }"
                        >
                            📊 Jurnal
                        </Link>
                        <Link
                            href="/food-items"
                            class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100"
                            :class="{
                                'bg-green-100 text-green-700':
                                    currentPage?.component?.startsWith(
                                        'FoodItems',
                                    ),
                            }"
                        >
                            📋 Catalog
                        </Link>
                        <span
                            class="block px-3 py-2 text-base font-medium text-gray-400"
                        >
                            🏋️‍♀️ Exerciții
                        </span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div v-if="flashSuccess" class="mb-4">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fa fa-check-circle text-green-400 mr-2"></i>
                        <p class="text-sm font-medium text-green-800">
                            {{ flashSuccess }}
                        </p>
                    </div>
                </div>
            </div>

            <div v-if="flashError" class="mb-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i
                            class="fa fa-exclamation-circle text-red-400 mr-2"
                        ></i>
                        <p class="text-sm font-medium text-red-800">
                            {{ flashError }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 py-8">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";

// Get page data safely
const page = usePage();
const currentPage = computed(() => page);

const showCalculator = ref(false);
const showMobileMenu = ref(false);

const calculator = ref({
    calories: 0,
    protein: 0,
    multiplier: 1,
});

const calculatedCalories = computed(() => {
    return (
        (calculator.value.calories || 0) * (calculator.value.multiplier || 1)
    ).toFixed(0);
});

const calculatedProtein = computed(() => {
    return (
        (calculator.value.protein || 0) * (calculator.value.multiplier || 1)
    ).toFixed(2);
});

// Flash messages
const flashSuccess = computed(() => page.props?.flash?.success);
const flashError = computed(() => page.props?.flash?.error);

// User helper methods
const getUserName = computed(() => {
    return page.props?.auth?.user?.name || page.props?.user?.name || "User";
});

const getUserIcon = computed(() => {
    const name = getUserName.value;
    return name === "Călina" ? "👩🏻‍💻" : "👱🏼‍♂️";
});

const logout = () => {
    router.post("/logout");
};

// Close dropdowns when clicking outside
document.addEventListener("click", (e) => {
    if (!e.target.closest(".relative")) {
        showCalculator.value = false;
        showMobileMenu.value = false;
    }
});
</script>
