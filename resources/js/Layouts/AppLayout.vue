<!-- resources/js/Layouts/AppLayout.vue - Modern Version -->
<template>
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-gray-50 to-emerald-50"
    >
        <!-- Modern Navigation -->
        <nav
            class="backdrop-blur-lg bg-white/80 border-b border-white/20 sticky top-0 z-50 shadow-sm"
        >
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo and Navigation Links -->
                    <div class="flex items-center space-x-8">
                        <Link href="/" class="flex items-center">
                            <span
                                class="text-2xl font-black text-emerald-600 mr-1"
                                >CAL</span
                            >
                            <span class="text-2xl font-light text-slate-600"
                                >TRACK</span
                            >
                        </Link>

                        <div class="hidden md:flex items-center space-x-2">
                            <Link
                                href="/"
                                :class="[
                                    'px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center space-x-2',
                                    currentPage?.component?.startsWith(
                                        'FoodEntries',
                                    )
                                        ? 'bg-emerald-100/80 text-emerald-700 shadow-sm'
                                        : 'text-slate-600 hover:text-slate-900 hover:bg-white/60',
                                ]"
                            >
                                <span>Jurnal</span>
                            </Link>

                            <Link
                                href="/food-items"
                                :class="[
                                    'px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center space-x-2',
                                    currentPage?.component?.startsWith(
                                        'FoodItems',
                                    )
                                        ? 'bg-emerald-100/80 text-emerald-700 shadow-sm'
                                        : 'text-slate-600 hover:text-slate-900 hover:bg-white/60',
                                ]"
                            >
                                <span>Catalog</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Right side: User -->
                    <div class="flex items-center space-x-4">
                        <!-- User Info -->
                        <div
                            class="flex items-center space-x-3 bg-white/60 rounded-lg px-3 py-2 shadow-sm"
                        >
                            <span class="text-lg">{{ getUserIcon }}</span>
                            <span class="text-sm font-medium text-slate-700">{{
                                getUserName
                            }}</span>

                            <!-- Logout Button -->
                            <form @submit.prevent="logout" class="inline">
                                <button
                                    type="submit"
                                    class="p-1 text-slate-400 hover:text-slate-600 rounded transition-colors"
                                    title="Deconectare"
                                >
                                    <i class="fa fa-sign-out-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <div class="max-w-7xl mx-auto px-6 pt-4">
            <div v-if="flashSuccess" class="mb-4">
                <div
                    class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200/50 rounded-2xl p-4 shadow-sm backdrop-blur-sm"
                >
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center"
                        >
                            <i class="fa fa-check text-emerald-600 text-sm"></i>
                        </div>
                        <p class="text-sm font-medium text-emerald-800">
                            {{ flashSuccess }}
                        </p>
                    </div>
                </div>
            </div>

            <div v-if="flashError" class="mb-4">
                <div
                    class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200/50 rounded-2xl p-4 shadow-sm backdrop-blur-sm"
                >
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center"
                        >
                            <i
                                class="fa fa-exclamation text-red-600 text-sm"
                            ></i>
                        </div>
                        <p class="text-sm font-medium text-red-800">
                            {{ flashError }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-6">
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

const showMobileMenu = ref(false);

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
</script>
