<!-- resources/js/Pages/FoodEntries/Index.vue - FINAL CLEAN VERSION -->
<template>
    <AppLayout>
        <!-- Week Navigation -->
        <div class="mb-8">
            <div class="flex overflow-x-auto space-x-2 pb-4">
                <button
                    v-for="(dayData, index) in foodEntries"
                    :key="index"
                    @click="selectDay(dayData, index)"
                    :class="[
                        'flex-shrink-0 px-4 py-3 rounded-xl text-center transition-all duration-200 min-w-[100px]',
                        selectedDayIndex === index
                            ? 'bg-green-500 text-white shadow-lg transform scale-105'
                            : 'bg-white text-gray-700 hover:bg-gray-50 shadow-md hover:shadow-lg',
                    ]"
                >
                    <div class="font-semibold">{{ dayData.dayNameHuman }}</div>
                    <div class="text-xs opacity-75">
                        {{ formatDateShort(dayData.date) }}
                    </div>
                </button>
            </div>
        </div>

        <!-- Selected Day Content -->
        <div v-if="selectedDay" class="space-y-6">
            <!-- Day Header -->
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    {{ selectedDay.dayNameHuman }} -
                    {{ formatDateLong(selectedDay.date) }}
                </h2>
            </div>

            <!-- Goals Section -->
            <div
                v-if="goalTypes && goalTypes.length > 0"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
            >
                <div
                    v-for="goalType in goalTypes"
                    :key="goalType.id"
                    class="bg-white rounded-xl shadow-lg border border-gray-100 p-4"
                >
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-900">
                            {{ goalType.name }}
                        </h4>
                        <button
                            v-if="canEdit && !selectedDay.dayObject"
                            @click="updateGoal(goalType.id)"
                            class="text-green-600 hover:text-green-700 text-xl font-bold"
                        >
                            +
                        </button>
                    </div>

                    <div class="text-sm text-gray-600 mb-3">
                        {{ getGoalProgress(goalType.id) }} /
                        {{ goalType.target_qty }}
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div
                            class="bg-green-500 h-2 rounded-full transition-all duration-300"
                            :style="{
                                width: `${getGoalProgressPercentage(goalType.id)}%`,
                            }"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Food Entries -->
            <div
                class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"
            >
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Intrări alimentare
                    </h3>
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Ora
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Cantitate
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Aliment
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Kcal
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Proteine
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Acțiuni
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr
                                v-if="
                                    !selectedDay.entries ||
                                    selectedDay.entries.length === 0
                                "
                            >
                                <td
                                    colspan="6"
                                    class="px-4 py-8 text-center text-gray-500 italic"
                                >
                                    Nu există intrări pentru această zi.
                                </td>
                            </tr>
                            <tr
                                v-for="entry in selectedDay.entries"
                                :key="entry.id"
                                class="hover:bg-gray-50 transition-colors duration-150"
                            >
                                <td
                                    class="px-4 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ formatTime(entry.ate_at) }}
                                </td>
                                <td
                                    class="px-4 py-4 whitespace-nowrap text-sm text-gray-600"
                                    v-html="entry.qtyForHumans"
                                ></td>
                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-900">
                                        {{ entry.food_name }}
                                        <span
                                            v-if="entry.hasThumbs"
                                            class="ml-2"
                                        >
                                            <i
                                                :class="`fa fa-thumbs-${entry.thumbs_type} ${entry.thumbs_type === 'up' ? 'text-green-500' : 'text-red-500'}`"
                                            ></i>
                                        </span>
                                    </div>
                                    <div
                                        v-if="entry.description"
                                        class="text-sm text-gray-500"
                                    >
                                        {{ entry.description }}
                                    </div>
                                </td>
                                <td
                                    class="px-4 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ Math.round(entry.kcal) }}
                                </td>
                                <td
                                    class="px-4 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ parseFloat(entry.protein).toFixed(2) }}
                                </td>
                                <td
                                    class="px-4 py-4 whitespace-nowrap text-center"
                                >
                                    <button
                                        v-if="canEdit && !selectedDay.dayObject"
                                        @click="deleteEntry(entry.id)"
                                        class="text-red-400 hover:text-red-600 transition-colors p-1"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <span
                                        v-else-if="canEdit"
                                        class="text-gray-400"
                                    >
                                        <i class="fa fa-lock"></i>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden p-4 space-y-4">
                    <div
                        v-if="
                            !selectedDay.entries ||
                            selectedDay.entries.length === 0
                        "
                        class="text-center text-gray-500 italic py-8"
                    >
                        Nu există intrări pentru această zi.
                    </div>
                    <div
                        v-for="entry in selectedDay.entries"
                        :key="'mobile-' + entry.id"
                        class="bg-gray-50 rounded-lg p-4 border border-gray-200"
                    >
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">
                                    {{ entry.food_name }}
                                    <span v-if="entry.hasThumbs" class="ml-2">
                                        <i
                                            :class="`fa fa-thumbs-${entry.thumbs_type} ${entry.thumbs_type === 'up' ? 'text-green-500' : 'text-red-500'}`"
                                        ></i>
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ formatTime(entry.ate_at) }} •
                                    <span v-html="entry.qtyForHumans"></span>
                                </div>
                            </div>
                            <button
                                v-if="canEdit && !selectedDay.dayObject"
                                @click="deleteEntry(entry.id)"
                                class="text-red-400 hover:text-red-600 p-1"
                            >
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        <div
                            v-if="entry.description"
                            class="text-sm text-gray-500 mb-2"
                        >
                            {{ entry.description }}
                        </div>
                        <div class="flex gap-4 text-sm">
                            <span
                                ><strong>{{ Math.round(entry.kcal) }}</strong>
                                kcal</span
                            >
                            <span
                                ><strong>{{
                                    parseFloat(entry.protein).toFixed(2)
                                }}</strong
                                >g proteine</span
                            >
                        </div>
                    </div>
                </div>

                <!-- Totals Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div
                        class="flex justify-between items-center flex-wrap gap-4"
                    >
                        <div class="flex items-center space-x-4">
                            <span class="font-semibold text-gray-900"
                                >Total:</span
                            >
                            <span
                                :class="[
                                    'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                                    getKcalClass(selectedDay.sumKcal),
                                ]"
                            >
                                {{ selectedDay.sumKcal }} kcal
                            </span>
                            <span
                                :class="[
                                    'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                                    getProteinClass(selectedDay.sumProtein),
                                ]"
                            >
                                {{
                                    parseFloat(selectedDay.sumProtein).toFixed(
                                        1,
                                    )
                                }}g proteine
                            </span>
                        </div>
                        <button
                            v-if="canEdit && !selectedDay.dayObject"
                            @click="showAddModal = true"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200"
                        >
                            <i class="fa fa-plus mr-2"></i> Adaugă
                        </button>
                    </div>
                </div>
            </div>

            <!-- Add Entry Button (Mobile) -->
            <div
                v-if="canEdit && !selectedDay.dayObject"
                class="md:hidden text-center"
            >
                <button
                    @click="showAddModal = true"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold text-lg px-8 py-4 rounded-lg w-full transition-colors duration-200"
                >
                    <i class="fa fa-plus mr-2"></i> Adaugă intrare
                </button>
            </div>
        </div>

        <!-- Add Modal -->
        <div
            v-if="showAddModal"
            class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
            @click="showAddModal = false"
        >
            <div
                class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md"
                @click.stop
            >
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Adaugă intrare alimentară
                    </h3>
                    <button
                        @click="showAddModal = false"
                        class="text-gray-400 hover:text-gray-500 p-1"
                    >
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <form @submit.prevent="addFoodEntry" class="space-y-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Ora</label
                        >
                        <input
                            v-model="entryForm.time"
                            type="time"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Nume aliment</label
                        >
                        <input
                            v-model="entryForm.direct_name"
                            type="text"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                            placeholder="ex: Piept de pui"
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Calorii</label
                            >
                            <input
                                v-model="entryForm.direct_kcal"
                                type="number"
                                min="0"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                placeholder="250"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Proteine</label
                            >
                            <input
                                v-model="entryForm.direct_protein"
                                type="number"
                                step="0.1"
                                min="0"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                placeholder="25.5"
                            />
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Descriere (opțional)</label
                        >
                        <input
                            v-model="entryForm.description"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                            placeholder="Detalii suplimentare..."
                        />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            @click="showAddModal = false"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200"
                        >
                            Anulează
                        </button>
                        <button
                            type="submit"
                            :disabled="entryForm.processing"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200"
                        >
                            <span v-if="entryForm.processing"
                                >Se adaugă...</span
                            >
                            <span v-else>Adaugă</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    foodEntries: Array,
    foodItems: Array,
    startDate: String,
    endDate: String,
    selectedDay: String,
    selectedDayName: String,
    goalTypes: Array,
    exerciseTypes: Array,
    lastUnreviewedDate: String,
    notifications: Array,
    roMonthNames: Object,
});

// State
const selectedDayIndex = ref(0);
const showAddModal = ref(false);

// Computed
const selectedDay = computed(() => {
    return props.foodEntries && props.foodEntries[selectedDayIndex.value]
        ? props.foodEntries[selectedDayIndex.value]
        : null;
});

const canEdit = computed(() => true);

// Initialize selected day
if (props.foodEntries && props.foodEntries.length > 0) {
    const foundIndex = props.foodEntries.findIndex(
        (day) => day.dayNameHuman === props.selectedDayName,
    );
    selectedDayIndex.value = foundIndex >= 0 ? foundIndex : 0;
}

// Form
const entryForm = useForm({
    time: new Date().toLocaleTimeString("en-GB", {
        hour: "2-digit",
        minute: "2-digit",
    }),
    direct_name: "",
    direct_kcal: "",
    direct_protein: "",
    description: "",
    date: props.selectedDay.slice(0, 10),
});

// Methods
const selectDay = (dayData, index) => {
    selectedDayIndex.value = index;
};

const formatDateShort = (dateStr) => {
    const date = new Date(dateStr);
    return (
        date.getDate() +
        " " +
        (
            props.roMonthNames[String(date.getMonth() + 1).padStart(2, "0")] ||
            ""
        ).substring(0, 3)
    );
};

const formatDateLong = (dateStr) => {
    const date = new Date(dateStr);
    return (
        date.getDate() +
        " " +
        props.roMonthNames[String(date.getMonth() + 1).padStart(2, "0")] +
        " " +
        date.getFullYear()
    );
};

const formatTime = (dateString) => {
    return new Date(dateString).toLocaleTimeString("ro-RO", {
        hour: "2-digit",
        minute: "2-digit",
    });
};

const getGoalProgress = (goalTypeId) => {
    return selectedDay.value?.goals?.[goalTypeId]?.qty || 0;
};

const getGoalProgressPercentage = (goalTypeId) => {
    const goalType = props.goalTypes?.find((g) => g.id === goalTypeId);
    const progress = getGoalProgress(goalTypeId);
    return goalType?.target_qty > 0
        ? Math.min((progress / goalType.target_qty) * 100, 100)
        : 0;
};

const getKcalClass = (kcal) => {
    if (kcal <= 1300) return "bg-green-100 text-green-800";
    if (kcal <= 1500) return "bg-blue-100 text-blue-800";
    if (kcal <= 2000) return "bg-yellow-100 text-yellow-800";
    return "bg-red-100 text-red-800";
};

const getProteinClass = (protein) => {
    if (protein >= 90) return "bg-green-100 text-green-800";
    if (protein >= 61) return "bg-yellow-100 text-yellow-800";
    return "bg-red-100 text-red-800";
};

const addFoodEntry = () => {
    entryForm.post("/food-entries", {
        onSuccess: () => {
            showAddModal.value = false;
            entryForm.reset();
            router.reload();
        },
    });
};

const deleteEntry = (entryId) => {
    if (confirm("Sigur vrei să ștergi această intrare?")) {
        router.delete(`/food-entries/${entryId}`, {
            onSuccess: () => {
                router.reload();
            },
        });
    }
};

const updateGoal = (goalTypeId) => {
    const currentProgress = getGoalProgress(goalTypeId);
    router.post(
        `/goals/${goalTypeId}`,
        {
            date: props.selectedDay.slice(0, 10),
            qty: currentProgress + 1,
        },
        {
            onSuccess: () => {
                router.reload();
            },
        },
    );
};
</script>
