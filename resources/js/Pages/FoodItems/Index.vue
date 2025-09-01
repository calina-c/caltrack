<template>
    <AppLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
            >
                <h1 class="text-2xl font-bold text-gray-900">
                    📋 Catalog alimente
                </h1>
                <div class="mt-4 sm:mt-0">
                    <form
                        @submit.prevent="searchFoodItems"
                        class="flex space-x-2"
                    >
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Caută după nume sau brand..."
                            class="input-field flex-1 min-w-0 sm:w-64"
                        />
                        <button type="submit" class="btn-primary">
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Add new item form (for Călina only) -->
            <div v-if="canAddItems" class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Adaugă aliment nou
                </h3>
                <form @submit.prevent="addFoodItem" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Nume *
                            </label>
                            <input
                                v-model="itemForm.name"
                                type="text"
                                required
                                class="input-field"
                                placeholder="ex: Piept de pui"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Brand
                            </label>
                            <input
                                v-model="itemForm.brand"
                                type="text"
                                class="input-field"
                                placeholder="ex: Carrefour"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Cantitate de bază *
                            </label>
                            <input
                                v-model="itemForm.unit_base_quantity"
                                type="number"
                                step="0.01"
                                min="0"
                                required
                                class="input-field"
                                placeholder="ex: 100"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Unitate de măsură *
                            </label>
                            <input
                                v-model="itemForm.unit_name"
                                type="text"
                                required
                                class="input-field"
                                placeholder="ex: g, ceașcă, felie"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Calorii (per unitate) *
                            </label>
                            <input
                                v-model="itemForm.kcal"
                                type="number"
                                step="0.01"
                                min="0"
                                required
                                class="input-field"
                                placeholder="ex: 165"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Proteine (per unitate) *
                            </label>
                            <input
                                v-model="itemForm.protein"
                                type="number"
                                step="0.01"
                                min="0"
                                required
                                class="input-field"
                                placeholder="ex: 31"
                            />
                        </div>
                        <div class="flex items-end">
                            <button
                                type="submit"
                                :disabled="itemForm.processing"
                                class="btn-primary"
                            >
                                <span v-if="itemForm.processing"
                                    >Se adaugă...</span
                                >
                                <span v-else>Adaugă aliment</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Food items table -->
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Nume
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Brand
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Unitate de bază
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Kcal
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Proteine
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Utilizări
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Acțiuni
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr
                                v-for="item in foodItems"
                                :key="item.id"
                                class="hover:bg-gray-50 transition-colors duration-150"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">
                                        {{ item.name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-600">
                                        {{ item.brand || "-" }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="text-gray-900"
                                        v-html="item.qtyForHumans"
                                    ></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-900">
                                        {{ Math.round(item.kcal) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-900">
                                        {{
                                            parseFloat(item.protein).toFixed(2)
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <span
                                        :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            item.entries_count > 0
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-gray-100 text-gray-800',
                                        ]"
                                    >
                                        {{ item.entries_count }} utilizări
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <button
                                        v-if="
                                            item.entries_count === 0 &&
                                            canDeleteItems
                                        "
                                        @click="deleteItem(item.id)"
                                        class="text-red-400 hover:text-red-600 transition-colors"
                                        title="Șterge alimentul"
                                    >
                                        <svg
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            />
                                        </svg>
                                    </button>
                                    <span
                                        v-else-if="item.entries_count > 0"
                                        class="text-gray-400"
                                        title="Nu se poate șterge - are intrări asociate"
                                    >
                                        <svg
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                            />
                                        </svg>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="foodItems.length === 0" class="text-center py-12">
                    <svg
                        class="mx-auto h-12 w-12 text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        Nu am găsit alimente
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{
                            search
                                ? "Încearcă o căutare diferită."
                                : "Începe prin a adăuga primul aliment."
                        }}
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    foodItems: Array,
    search: String,
});

const searchQuery = ref(props.search || "");

const canAddItems = computed(() => {
    return window.Laravel?.user?.name === "Călina";
});

const canDeleteItems = computed(() => {
    return window.Laravel?.user?.name === "Călina";
});

const itemForm = useForm({
    name: "",
    brand: "",
    unit_name: "",
    unit_base_quantity: "",
    kcal: "",
    protein: "",
});

const searchFoodItems = () => {
    router.get(
        route("food-items.index"),
        {
            search: searchQuery.value,
        },
        {
            preserveState: true,
        },
    );
};

const addFoodItem = () => {
    itemForm.post(route("food-items.store"), {
        onSuccess: () => {
            itemForm.reset();
        },
    });
};

const deleteItem = (itemId) => {
    if (confirm("Sigur vrei să ștergi acest aliment?")) {
        router.delete(route("food-items.destroy", itemId));
    }
};
</script>
