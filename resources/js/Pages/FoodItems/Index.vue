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

            <!-- Collapsible Add Form -->
            <div
                v-if="canAddItems"
                class="bg-white border border-gray-200 rounded-lg shadow-sm"
            >
                <!-- Accordion Header -->
                <button
                    @click="showForm = !showForm"
                    class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center mr-4"
                        >
                            <svg
                                class="w-5 h-5 text-emerald-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                />
                            </svg>
                        </div>
                        <div class="text-left">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Adaugă aliment nou
                            </h3>
                            <p class="text-sm text-gray-500">
                                Click pentru a deschide formularul
                            </p>
                        </div>
                    </div>
                    <svg
                        :class="[
                            'w-5 h-5 text-gray-400 transition-transform duration-300',
                            showForm ? 'rotate-180' : '',
                        ]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </button>

                <!-- Accordion Content -->
                <div
                    v-show="showForm"
                    class="border-t border-gray-200 p-6 bg-gray-50"
                >
                    <form @submit.prevent="submitForm" class="space-y-6">
                        <!-- Name & Brand Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Nume aliment *
                                </label>
                                <input
                                    v-model="itemForm.name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white"
                                    placeholder="ex: Piept de pui"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Brand (opțional)
                                </label>
                                <input
                                    v-model="itemForm.brand"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white"
                                    placeholder="ex: Carrefour"
                                />
                            </div>
                        </div>

                        <!-- Quantity & Unit Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white"
                                    placeholder="100"
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white"
                                    placeholder="g, ml, bucată"
                                />
                            </div>
                        </div>

                        <!-- Calories & Protein Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Calorii (kcal) *
                                </label>
                                <input
                                    v-model="itemForm.kcal"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white"
                                    placeholder="165"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Proteine (g) *
                                </label>
                                <input
                                    v-model="itemForm.protein"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white"
                                    placeholder="31.5"
                                />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex justify-end space-x-3 pt-4 border-t border-gray-200"
                        >
                            <button
                                type="button"
                                @click="showForm = false"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors"
                            >
                                Anulează
                            </button>
                            <button
                                type="submit"
                                :disabled="itemForm.processing"
                                class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-medium rounded-lg hover:from-emerald-600 hover:to-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm"
                            >
                                <span
                                    v-if="itemForm.processing"
                                    class="flex items-center"
                                >
                                    <svg
                                        class="w-4 h-4 animate-spin mr-2"
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
                                    Se salvează...
                                </span>
                                <span v-else>Salvează alimentul</span>
                            </button>
                        </div>
                    </form>
                </div>
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
                                    <!-- Both icons use the exact same wrapper structure -->
                                    <div
                                        class="inline-flex items-center justify-center w-8 h-8"
                                    >
                                        <button
                                            v-if="
                                                item.entries_count === 0 &&
                                                canDeleteItems
                                            "
                                            @click="deleteItem(item.id)"
                                            type="button"
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
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
                                        <div
                                            v-else-if="item.entries_count > 0"
                                            class="inline-flex items-center justify-center w-8 h-8 text-gray-400"
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
                                        </div>
                                    </div>
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
    auth: Object,
});

const searchQuery = ref(props.search || "");
const showForm = ref(false);

const canAddItems = computed(() => {
    return props.auth?.user?.name === "Călina";
});

const canDeleteItems = computed(() => {
    return props.auth?.user?.name === "Călina";
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

const submitForm = () => {
    itemForm.post(route("food-items.store"), {
        onSuccess: () => {
            itemForm.reset();
            showForm.value = false;
        },
    });
};

const deleteItem = (itemId) => {
    if (confirm("Sigur vrei să ștergi acest aliment?")) {
        router.delete(route("food-items.destroy", itemId));
    }
};
</script>
