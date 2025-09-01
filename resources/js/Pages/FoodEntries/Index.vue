<!-- resources/js/Pages/FoodEntries/Index.vue - Complete file with dual-mode entry -->
<template>
    <AppLayout>
        <!-- Notifications -->
        <div
            v-if="notifications && notifications.length > 0"
            class="mb-6 space-y-2"
        >
            <div
                v-for="notification in notifications"
                :key="notification.id"
                class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center justify-between"
            >
                <span class="text-blue-800">{{
                    notification.description
                }}</span>
                <div class="flex space-x-2">
                    <button
                        @click="goToNotificationDate(notification)"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm"
                    >
                        Mergi la zi
                    </button>
                    <button
                        @click="markNotificationRead(notification.id)"
                        class="text-blue-600 hover:text-blue-800"
                    >
                        ×
                    </button>
                </div>
            </div>
        </div>
        <!-- Day Header -->
        <div
            class="group relative overflow-hidden bg-gradient-to-br from-white via-gray-50 to-emerald-50 text-emerald-800 rounded-2xl shadow-lg border border-emerald-200/50 px-6 py-4 text-center transition-all duration-300 hover:shadow-xl"
        >
            <div
                class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
            ></div>
            <div
                class="absolute -right-2 -top-2 w-12 h-12 bg-emerald-500/20 rounded-full"
            ></div>
            <div
                class="absolute -left-1 -bottom-1 w-6 h-6 bg-emerald-500/10 rounded-full"
            ></div>
            <h2 class="relative text-2xl font-bold">
                {{ selectedDay.dayNameHuman }} -
                {{ formatDateLong(selectedDay.date) }}
            </h2>
        </div>

        <!-- Week Navigation -->
        <div class="mb-8">
            <div class="p-4 w-full">
                <div class="grid grid-cols-7 gap-2">
                    <button
                        v-for="(dayData, index) in foodEntries"
                        :key="index"
                        @click="selectDay(dayData, index)"
                        :class="[
                            'px-2 md:px-4 py-3 rounded-xl text-center transition-all duration-200 min-w-0',
                            selectedDayIndex === index
                                ? 'bg-emerald-500 text-white shadow-lg transform scale-105'
                                : 'bg-white/70 backdrop-blur-sm text-gray-700 hover:bg-white/90 shadow-md hover:shadow-lg border border-white/20',
                        ]"
                    >
                        <!-- Mobile: Show only first letter + day -->
                        <div class="md:hidden">
                            <div class="font-semibold text-xs">
                                {{ dayData.dayNameHuman.charAt(0) }}
                            </div>
                            <div class="text-xs opacity-75">
                                {{
                                    formatDateShort(dayData.date).split(" ")[0]
                                }}
                            </div>
                        </div>
                        <!-- Desktop: Show full name + day -->
                        <div class="hidden md:block">
                            <div class="font-semibold">
                                {{ dayData.dayNameHuman }}
                            </div>
                            <div class="text-xs opacity-75">
                                {{ formatDateShort(dayData.date) }}
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Selected Day Content -->
        <div v-if="selectedDay" class="space-y-6">
            <!-- Food Entries -->
            <div
                class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"
            >
                <div
                    class="bg-gradient-to-r from-emerald-100 via-teal-50 to-green-100 px-6 py-4 border-b border-gray-200"
                >
                    <h3
                        class="text-lg font-bold text-gray-800 flex items-center"
                    >
                        <i class="fa fa-utensils mr-3 text-emerald-600"></i>
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
                                    {{ formatNumber(entry.protein, 2) }}
                                </td>
                                <td
                                    class="px-4 py-4 whitespace-nowrap text-center"
                                >
                                    <!-- Actions for Călina -->
                                    <div v-if="isCalina">
                                        <button
                                            v-if="!selectedDay.dayObject"
                                            @click="deleteEntry(entry.id)"
                                            class="text-red-400 hover:text-red-600 transition-colors p-1"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <span v-else class="text-gray-400">
                                            <i class="fa fa-lock"></i>
                                        </span>
                                    </div>
                                    <!-- Actions for others (thumbs up/down) -->
                                    <div v-else>
                                        <div
                                            v-if="!entry.hasThumbs"
                                            class="flex space-x-1 justify-center"
                                        >
                                            <button
                                                @click="
                                                    addThumb(
                                                        entry.id,
                                                        entry.food_item_id,
                                                        'up',
                                                    )
                                                "
                                                class="text-green-500 hover:text-green-600 p-1"
                                                title="Thumbs up"
                                            >
                                                <i class="fa fa-thumbs-up"></i>
                                            </button>
                                            <button
                                                @click="
                                                    addThumb(
                                                        entry.id,
                                                        entry.food_item_id,
                                                        'down',
                                                    )
                                                "
                                                class="text-red-500 hover:text-red-600 p-1"
                                                title="Thumbs down"
                                            >
                                                <i
                                                    class="fa fa-thumbs-down"
                                                ></i>
                                            </button>
                                        </div>
                                        <div
                                            v-else
                                            class="text-xs text-gray-400"
                                        >
                                            Evaluat
                                        </div>
                                    </div>
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
                            <div v-if="isCalina">
                                <button
                                    v-if="!selectedDay.dayObject"
                                    @click="deleteEntry(entry.id)"
                                    class="text-red-400 hover:text-red-600 p-1"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                            <div v-else>
                                <div
                                    v-if="!entry.hasThumbs"
                                    class="flex space-x-1"
                                >
                                    <button
                                        @click="
                                            addThumb(
                                                entry.id,
                                                entry.food_item_id,
                                                'up',
                                            )
                                        "
                                        class="text-green-500 hover:text-green-600 p-1"
                                        title="Thumbs up"
                                    >
                                        <i class="fa fa-thumbs-up"></i>
                                    </button>
                                    <button
                                        @click="
                                            addThumb(
                                                entry.id,
                                                entry.food_item_id,
                                                'down',
                                            )
                                        "
                                        class="text-red-500 hover:text-red-600 p-1"
                                        title="Thumbs down"
                                    >
                                        <i class="fa fa-thumbs-down"></i>
                                    </button>
                                </div>
                                <div v-else class="text-xs text-gray-400">
                                    Evaluat
                                </div>
                            </div>
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
                                    formatNumber(entry.protein, 2)
                                }}</strong
                                >g proteine</span
                            >
                        </div>
                    </div>
                </div>

                <!-- Add Entry Button -->
                <div
                    v-if="canEdit && !selectedDay.dayObject"
                    class="p-6 border-t border-gray-200"
                >
                    <button
                        @click="showAddModal = true"
                        class="w-full bg-emerald-100 hover:bg-emerald-200 font-semibold py-3 px-4 rounded-lg transition-colors duration-200"
                    >
                        <i class="fa fa-plus mr-2"></i> Adaugă intrare
                        alimentară
                    </button>
                </div>
            </div>

            <!-- Exercises, Goals, and Daily Summary Combined Section -->
            <div
                class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"
            >
                <div
                    class="bg-gradient-to-r from-emerald-100 via-teal-50 to-green-100 px-6 py-4 border-b border-gray-200"
                >
                    <h3
                        class="text-lg font-bold text-gray-800 flex items-center"
                    >
                        <i class="fa fa-dumbbell mr-3 text-emerald-600"></i>
                        Exerciții și obiective
                    </h3>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Goals -->
                    <div
                        v-if="goalTypes && goalTypes.length > 0"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"
                    >
                        <div
                            v-for="goalType in goalTypes"
                            :key="goalType.id"
                            class="group relative overflow-hidden bg-gradient-to-br from-emerald-50 via-teal-50 to-green-50 rounded-xl border border-emerald-100 p-4 hover:shadow-lg transition-all duration-300 hover:scale-105"
                        >
                            <!-- Background decoration -->
                            <div
                                class="absolute -top-2 -right-2 w-12 h-12 bg-emerald-200/20 rounded-full"
                            ></div>
                            <div
                                class="absolute -bottom-1 -left-1 w-6 h-6 bg-emerald-300/30 rounded-full"
                            ></div>

                            <div
                                class="relative flex items-center justify-between"
                            >
                                <div class="flex-1">
                                    <h4
                                        class="font-semibold text-gray-800 text-sm mb-1"
                                    >
                                        {{ goalType.name }}
                                    </h4>

                                    <!-- Cool fraction display -->
                                    <div class="flex items-baseline space-x-1">
                                        <span
                                            class="text-2xl font-bold tabular-nums transition-all duration-300"
                                            :class="
                                                getGoalProgress(goalType.id) >=
                                                goalType.target_qty
                                                    ? 'text-emerald-600'
                                                    : 'text-gray-700'
                                            "
                                        >
                                            {{ getGoalProgress(goalType.id) }}
                                        </span>
                                        <span
                                            class="text-gray-400 text-lg font-light"
                                            >/</span
                                        >
                                        <span
                                            class="text-lg font-medium text-gray-600 tabular-nums"
                                        >
                                            {{ goalType.target_qty }}
                                        </span>

                                        <!-- Goal completion indicator -->
                                        <div
                                            v-if="
                                                getGoalProgress(goalType.id) >=
                                                goalType.target_qty
                                            "
                                            class="ml-2"
                                        >
                                            <div
                                                class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"
                                            ></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action button with enhanced styling -->
                                <button
                                    v-if="canEdit && !selectedDay.dayObject"
                                    @click="updateGoal(goalType.id)"
                                    class="group/btn relative overflow-hidden bg-emerald-500 hover:bg-emerald-600 text-white w-8 h-8 rounded-full shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-110 flex items-center justify-center"
                                >
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent opacity-0 group-hover/btn:opacity-100 transition-opacity duration-200"
                                    ></div>
                                    <span class="relative text-sm font-bold"
                                        >+</span
                                    >
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Totals and Exercises -->
                    <div>
                        <div class="flex flex-col sm:flex-row flex-wrap gap-4">
                            <!-- Calories Card -->
                            <div
                                :class="[
                                    'group relative overflow-hidden text-white rounded-2xl px-6 py-4 shadow-lg transform hover:scale-105 transition-all duration-300 hover:shadow-xl sm:flex-1 min-w-0',
                                    getKcalClass(selectedDay.sumKcal).includes(
                                        'green',
                                    )
                                        ? 'bg-gradient-to-br from-green-400 via-green-500 to-emerald-500'
                                        : getKcalClass(
                                                selectedDay.sumKcal,
                                            ).includes('blue')
                                          ? 'bg-gradient-to-br from-blue-400 via-blue-500 to-indigo-500'
                                          : getKcalClass(
                                                  selectedDay.sumKcal,
                                              ).includes('yellow')
                                            ? 'bg-gradient-to-br from-yellow-400 via-yellow-500 to-orange-500'
                                            : 'bg-gradient-to-br from-red-400 via-red-500 to-red-600',
                                ]"
                            >
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                ></div>
                                <div
                                    class="relative flex items-center space-x-3"
                                >
                                    <div
                                        class="bg-white/20 rounded-full p-2 flex-shrink-0"
                                    >
                                        <svg
                                            class="w-6 h-6 text-white"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.5-7 2 0 5.5 1.5 5.5 4 0 0 0 1 0 1h4.036z"
                                            />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-sm truncate">
                                            {{ selectedDay.sumKcal }} kcal
                                        </div>
                                        <div
                                            v-if="isCalina"
                                            class="text-xs text-white/80 opacity-90 truncate"
                                        >
                                            {{
                                                1300 - selectedDay.sumKcal >= 0
                                                    ? "+"
                                                    : ""
                                            }}{{ 1300 - selectedDay.sumKcal }}
                                            din 1300
                                        </div>
                                        <div
                                            v-else
                                            class="text-xs text-white/80 opacity-90 truncate"
                                        >
                                            Calorii totale
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="absolute -right-2 -top-2 w-8 h-8 bg-white/10 rounded-full"
                                ></div>
                                <div
                                    class="absolute -left-1 -bottom-1 w-4 h-4 bg-white/5 rounded-full"
                                ></div>
                            </div>

                            <!-- Protein Card -->
                            <div
                                :class="[
                                    'group relative overflow-hidden text-white rounded-2xl px-6 py-4 shadow-lg transform hover:scale-105 transition-all duration-300 hover:shadow-xl sm:flex-1 min-w-0',
                                    getProteinClass(
                                        selectedDay.sumProtein,
                                    ).includes('green')
                                        ? 'bg-gradient-to-br from-green-400 via-green-500 to-emerald-500'
                                        : getProteinClass(
                                                selectedDay.sumProtein,
                                            ).includes('yellow')
                                          ? 'bg-gradient-to-br from-yellow-400 via-yellow-500 to-orange-500'
                                          : 'bg-gradient-to-br from-red-400 via-red-500 to-red-600',
                                ]"
                            >
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                ></div>
                                <div
                                    class="relative flex items-center space-x-3"
                                >
                                    <div
                                        class="bg-white/20 rounded-full p-2 flex-shrink-0"
                                    >
                                        <svg
                                            class="w-6 h-6 text-white"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                            />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-sm truncate">
                                            {{
                                                formatNumber(
                                                    selectedDay.sumProtein,
                                                )
                                            }}g protein
                                        </div>
                                        <div
                                            v-if="isCalina"
                                            class="text-xs text-white/80 opacity-90 truncate"
                                        >
                                            {{
                                                90 - selectedDay.sumProtein <= 0
                                                    ? ""
                                                    : "+"
                                            }}{{
                                                formatNumber(
                                                    90 - selectedDay.sumProtein,
                                                )
                                            }}g din 90g
                                        </div>
                                        <div
                                            v-else
                                            class="text-xs text-white/80 opacity-90 truncate"
                                        >
                                            Proteine totale
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="absolute -right-2 -top-2 w-8 h-8 bg-white/10 rounded-full"
                                ></div>
                                <div
                                    class="absolute -left-1 -bottom-1 w-4 h-4 bg-white/5 rounded-full"
                                ></div>
                            </div>

                            <!-- Exercise Badges -->
                            <div
                                v-for="exercise in selectedDay.exercises"
                                :key="exercise.id"
                                class="group relative overflow-hidden bg-gradient-to-br from-emerald-400 via-emerald-500 to-emerald-600 text-white rounded-2xl px-6 py-4 shadow-lg transform hover:scale-105 transition-all duration-300 hover:shadow-xl sm:flex-1 min-w-0"
                            >
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                ></div>

                                <!-- Delete button -->
                                <button
                                    v-if="isCalina && !selectedDay.dayObject"
                                    @click="deleteExercise(exercise.id)"
                                    class="absolute top-2 right-2 w-6 h-6 bg-red-500/80 hover:bg-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200 z-10"
                                >
                                    <i
                                        class="fa fa-times text-white text-xs"
                                    ></i>
                                </button>

                                <div
                                    class="relative flex items-center space-x-3"
                                >
                                    <div
                                        class="bg-white/20 rounded-full p-2 flex-shrink-0"
                                    >
                                        <i
                                            :class="`fa fa-lg fa-${exercise.exercise_type.icon} text-white`"
                                        ></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-sm truncate">
                                            {{ exercise.exercise_type.name }}
                                        </div>
                                        <div
                                            class="text-xs text-emerald-50 opacity-90 truncate"
                                        >
                                            {{ exercise.label }}
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="absolute -right-2 -top-2 w-8 h-8 bg-emerald-300/30 rounded-full"
                                ></div>
                                <div
                                    class="absolute -left-1 -bottom-1 w-4 h-4 bg-emerald-300/20 rounded-full"
                                ></div>
                            </div>

                            <!-- Add Exercise Badge -->
                            <button
                                v-if="canEdit && !selectedDay.dayObject"
                                @click="showExerciseModal = true"
                                class="group relative overflow-hidden bg-gradient-to-br from-white via-gray-50 to-emerald-50 text-emerald-700 rounded-2xl px-6 py-4 shadow-lg transform hover:scale-105 transition-all duration-300 hover:shadow-xl border border-emerald-200/50 sm:flex-1 min-w-0"
                            >
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                ></div>
                                <div
                                    class="relative flex items-center space-x-3"
                                >
                                    <div
                                        class="bg-emerald-500/20 rounded-full p-2 flex-shrink-0"
                                    >
                                        <i
                                            class="fa fa-lg fa-plus text-emerald-600"
                                        ></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-sm truncate">
                                            Adaugă exercițiu
                                        </div>
                                        <div
                                            class="text-xs text-emerald-600/70 opacity-90 truncate"
                                        >
                                            Nou
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="absolute -right-2 -top-2 w-8 h-8 bg-emerald-500/20 rounded-full"
                                ></div>
                                <div
                                    class="absolute -left-1 -bottom-1 w-4 h-4 bg-emerald-500/10 rounded-full"
                                ></div>
                            </button>
                        </div>
                    </div>
                    <!-- Day Actions -->
                    <div class="border-t border-gray-200 pt-6">
                        <div v-if="isCalina" class="text-center">
                            <button
                                v-if="!selectedDay.dayObject"
                                @click="closeDay()"
                                class="w-full bg-emerald-100 hover:bg-emerald-200 font-semibold py-3 px-4 rounded-lg transition-colors duration-200"
                            >
                                <i class="fa fa-lock mr-2"></i> Închide ziua
                            </button>
                            <div
                                v-else-if="
                                    selectedDay.dayObject &&
                                    selectedDay.dayObject.rating
                                "
                                class="text-center"
                            >
                                <div class="text-sm text-gray-600 mb-2">
                                    Rating primit:
                                </div>
                                <div class="text-lg font-semibold">
                                    {{
                                        getRatingEmoji(
                                            selectedDay.dayObject.rating,
                                        )
                                    }}
                                </div>
                            </div>
                            <div v-else class="text-gray-600">
                                <i class="fa fa-check-circle mr-2"></i>Ziua este
                                închisă
                            </div>
                        </div>

                        <div v-else>
                            <!-- Rating section for non-Călina users -->
                            <div v-if="selectedDay.dayObject">
                                <div v-if="selectedDay.dayObject.done">
                                    <div
                                        v-if="selectedDay.dayObject.rating"
                                        class="text-center"
                                    >
                                        <div class="text-sm text-gray-600 mb-2">
                                            Rating acordat:
                                        </div>
                                        <div class="text-lg font-semibold">
                                            {{
                                                getRatingEmoji(
                                                    selectedDay.dayObject
                                                        .rating,
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div v-else class="text-center">
                                        <div
                                            class="text-sm font-medium text-gray-700 mb-4"
                                        >
                                            Acordă un rating pentru această zi:
                                        </div>
                                        <div
                                            class="flex flex-wrap gap-2 justify-center"
                                        >
                                            <button
                                                v-for="(
                                                    rating, index
                                                ) in ratingOptions"
                                                :key="index"
                                                @click="addRating(rating.value)"
                                                :class="[
                                                    'text-sm px-4 py-2 rounded-lg font-medium transition-all duration-200 transform hover:scale-105',
                                                    rating.class,
                                                ]"
                                            >
                                                {{ rating.emoji }}
                                                {{ rating.label }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-center text-gray-600">
                                    <i class="fa fa-clock mr-2"></i>Ziua nu e
                                    gata încă. Ratingul mai trebuie să aștepte.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Week Navigation Footer -->
        <div class="mt-12 -mx-4 sm:-mx-6 lg:-mx-8 p-6">
            <div
                class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center sm:justify-between"
            >
                <!-- Special Buttons -->
                <button
                    v-if="!isCalina && lastUnreviewedDate"
                    @click="goToDate(lastUnreviewedDate)"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center space-x-2 order-3 sm:order-1"
                >
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 616 0z"
                        ></path>
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                        ></path>
                    </svg>
                    <span>Ultima nevăzută</span>
                </button>

                <!-- Navigation Controls -->
                <div
                    class="flex items-center space-x-2 bg-gray-50/80 backdrop-blur-sm rounded-xl p-2 border border-gray-200/50 order-1 sm:order-2 sm:flex-1 sm:mx-4 sm:min-w-0"
                >
                    <button
                        @click="goToPreviousWeek"
                        class="bg-white/70 hover:bg-white/90 text-gray-600 font-bold px-3 sm:px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 border border-gray-200/50 backdrop-blur-sm"
                        title="Săptămâna precedentă"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 19l-7-7 7-7m8 14l-7-7 7-7"
                            ></path>
                        </svg>
                    </button>
                    <button
                        @click="goToPreviousDay"
                        class="bg-white/70 hover:bg-white/90 text-gray-600 font-bold px-3 sm:px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 border border-gray-200/50 backdrop-blur-sm"
                        title="Ziua precedentă"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            ></path>
                        </svg>
                    </button>

                    <div
                        class="px-2 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-gray-100/80 rounded-lg flex-1 text-center min-w-0 backdrop-blur-sm"
                    >
                        {{ formatDateRange() }}
                    </div>

                    <button
                        @click="goToNextDay"
                        class="bg-white/70 hover:bg-white/90 text-gray-600 font-bold px-3 sm:px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 border border-gray-200/50 backdrop-blur-sm"
                        title="Ziua următoare"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            ></path>
                        </svg>
                    </button>
                    <button
                        @click="goToNextWeek"
                        class="bg-white/70 hover:bg-white/90 text-gray-600 font-bold px-3 sm:px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 border border-gray-200/50 backdrop-blur-sm"
                        title="Săptămâna următoare"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 5l7 7-7 7M5 5l7 7-7 7"
                            ></path>
                        </svg>
                    </button>
                </div>

                <!-- Current Week Button -->
                <button
                    @click="goToCurrentWeek"
                    :class="[
                        'font-semibold px-6 py-3 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center space-x-2 order-2 sm:order-3',
                        isCurrentWeek
                            ? 'bg-emerald-700 hover:bg-emerald-800 text-white'
                            : 'bg-emerald-600 hover:bg-emerald-700 text-white',
                    ]"
                >
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        ></path>
                    </svg>
                    <span class="text-sm sm:text-base">{{
                        isCurrentWeek ? "Săptămâna curentă" : "Săptămâna asta"
                    }}</span>
                    <span
                        v-if="isCurrentWeek"
                        class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs"
                        >Aici</span
                    >
                </button>
            </div>
        </div>

        <!-- Add Food Entry Modal with Dual Mode -->
        <div
            v-if="showAddModal"
            class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
            @click="showAddModal = false"
        >
            <div
                class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto"
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

                <form @submit.prevent="addFoodEntry" class="space-y-6">
                    <!-- Time field -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Ora
                        </label>
                        <input
                            v-model="entryForm.time"
                            type="time"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                        />
                    </div>

                    <!-- Entry type tabs -->
                    <div class="border-b border-gray-200">
                        <nav class="flex space-x-8" aria-label="Tabs">
                            <button
                                type="button"
                                @click="entryMode = 'catalog'"
                                :class="[
                                    'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm',
                                    entryMode === 'catalog'
                                        ? 'border-green-500 text-green-600'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                ]"
                            >
                                Din catalog
                            </button>
                            <button
                                type="button"
                                @click="entryMode = 'direct'"
                                :class="[
                                    'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm',
                                    entryMode === 'direct'
                                        ? 'border-green-500 text-green-600'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                ]"
                            >
                                Direct
                            </button>
                        </nav>
                    </div>

                    <!-- Catalog mode -->
                    <div v-if="entryMode === 'catalog'" class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Aliment din catalog
                            </label>
                            <div class="relative food-dropdown-container">
                                <input
                                    v-model="foodSearchTerm"
                                    @focus="showFoodDropdown = true"
                                    @input="showFoodDropdown = true"
                                    type="text"
                                    :placeholder="
                                        selectedFoodItem
                                            ? `${selectedFoodItem.name}${selectedFoodItem.brand ? ` (${selectedFoodItem.brand})` : ''}`
                                            : 'Caută aliment...'
                                    "
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                    :class="{ 'bg-gray-50': selectedFoodItem }"
                                    autocomplete="off"
                                />

                                <!-- Dropdown -->
                                <div
                                    v-if="
                                        showFoodDropdown &&
                                        filteredFoodItems.length > 0
                                    "
                                    class="absolute z-20 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto"
                                >
                                    <div
                                        v-for="foodItem in filteredFoodItems.slice(
                                            0,
                                            10,
                                        )"
                                        :key="foodItem.id"
                                        @click="selectFoodItem(foodItem)"
                                        class="px-4 py-3 hover:bg-green-50 cursor-pointer flex items-start justify-between transition-colors duration-150 border-b border-gray-100 last:border-b-0"
                                    >
                                        <div class="flex-1 min-w-0">
                                            <div
                                                class="font-medium text-gray-900 truncate"
                                            >
                                                {{ foodItem.name }}
                                            </div>
                                            <div
                                                v-if="foodItem.brand"
                                                class="text-sm text-gray-500 truncate"
                                            >
                                                {{ foodItem.brand }}
                                            </div>
                                            <div
                                                class="text-xs text-gray-400 mt-1"
                                            >
                                                {{
                                                    Math.round(
                                                        foodItem.kcal || 0,
                                                    )
                                                }}
                                                kcal,
                                                {{
                                                    formatNumber(
                                                        foodItem.protein,
                                                    )
                                                }}g protein
                                            </div>
                                        </div>
                                        <div
                                            class="ml-3 text-xs text-green-600 font-medium whitespace-nowrap"
                                        >
                                            {{
                                                foodItem.unit_base_quantity == 1
                                                    ? foodItem.unit_name
                                                    : `${formatNumber(foodItem.unit_base_quantity)} ${foodItem.unit_name}`
                                            }}
                                        </div>
                                    </div>

                                    <div
                                        v-if="filteredFoodItems.length > 10"
                                        class="px-4 py-2 text-xs text-gray-500 bg-gray-50 border-t"
                                    >
                                        {{ filteredFoodItems.length - 10 }} mai
                                        multe rezultate...
                                    </div>
                                </div>

                                <!-- Clear button -->
                                <button
                                    v-if="selectedFoodItem"
                                    @click="clearFoodSelection"
                                    type="button"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1"
                                >
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div
                            v-if="selectedFoodItem"
                            class="flex items-center space-x-4"
                        >
                            <div class="flex-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Cantitate
                                </label>
                                <input
                                    v-model="entryForm.multiplier"
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    placeholder="1"
                                    :required="entryMode === 'catalog'"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                />
                            </div>
                            <div
                                class="flex items-center text-xl text-gray-600 mt-6"
                            >
                                ×
                            </div>
                            <div class="flex-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Unitate
                                </label>
                                <div
                                    class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-600"
                                >
                                    {{ unitDisplay }}
                                </div>
                            </div>
                        </div>

                        <!-- Preview of calculated values -->
                        <div
                            v-if="selectedFoodItem && entryForm.multiplier"
                            class="bg-green-50 border border-green-200 rounded-lg p-4"
                        >
                            <div
                                class="text-sm font-medium text-green-800 mb-2"
                            >
                                Previzualizare:
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-green-600">Calorii:</span>
                                    {{
                                        Math.round(
                                            (selectedFoodItem.kcal || 0) *
                                                (entryForm.multiplier || 1),
                                        )
                                    }}
                                    kcal
                                </div>
                                <div>
                                    <span class="text-green-600"
                                        >Proteine:</span
                                    >
                                    {{
                                        formatNumber(
                                            parseFloat(
                                                selectedFoodItem.protein || 0,
                                            ) * (entryForm.multiplier || 1),
                                            2,
                                        )
                                    }}g
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Direct mode -->
                    <div v-if="entryMode === 'direct'" class="space-y-4">
                        <!-- Hidden inputs for form submission -->
                        <input type="hidden" v-model="entryForm.direct_kcal" />
                        <input
                            type="hidden"
                            v-model="entryForm.direct_protein"
                        />

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Nume aliment
                            </label>
                            <input
                                v-model="entryForm.direct_name"
                                type="text"
                                :required="entryMode === 'direct'"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                placeholder="ex: Piept de pui"
                            />
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Calorii per unitate
                                </label>
                                <input
                                    v-model="directCaloriesPerUnit"
                                    type="number"
                                    min="0"
                                    :required="entryMode === 'direct'"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                    placeholder="250"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Proteine per unitate
                                </label>
                                <input
                                    v-model="directProteinPerUnit"
                                    type="number"
                                    step="0.1"
                                    min="0"
                                    :required="entryMode === 'direct'"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                    placeholder="25.5"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Cantitate
                                </label>
                                <input
                                    v-model="directMultiplier"
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    :required="entryMode === 'direct'"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                    placeholder="1"
                                />
                            </div>
                        </div>

                        <!-- Preview of calculated values -->
                        <div
                            v-if="
                                directCaloriesPerUnit &&
                                directProteinPerUnit &&
                                directMultiplier
                            "
                            class="bg-green-50 border border-green-200 rounded-lg p-4"
                        >
                            <div
                                class="text-sm font-medium text-green-800 mb-2"
                            >
                                Previzualizare:
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-green-600">Calorii:</span>
                                    {{
                                        Math.round(
                                            (directCaloriesPerUnit || 0) *
                                                (directMultiplier || 1),
                                        )
                                    }}
                                    kcal
                                </div>
                                <div>
                                    <span class="text-green-600"
                                        >Proteine:</span
                                    >
                                    {{
                                        formatNumber(
                                            (directProteinPerUnit || 0) *
                                                (directMultiplier || 1),
                                            2,
                                        )
                                    }}g
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description field (common for both modes) -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Descriere (opțional)
                        </label>
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
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
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

        <!-- Add Exercise Modal -->
        <div
            v-if="showExerciseModal"
            class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
            @click="showExerciseModal = false"
        >
            <div
                class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md"
                @click.stop
            >
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Adaugă exercițiu
                    </h3>
                    <button
                        @click="showExerciseModal = false"
                        class="text-gray-400 hover:text-gray-500 p-1"
                    >
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <form @submit.prevent="addExercise" class="space-y-4">
                    <!-- Custom Exercise Type Dropdown -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Tip exercițiu</label
                        >
                        <div class="relative">
                            <button
                                type="button"
                                @click="
                                    showExerciseDropdown = !showExerciseDropdown
                                "
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 text-left flex items-center justify-between bg-white"
                            >
                                <span
                                    v-if="selectedExerciseType"
                                    class="flex items-center space-x-2"
                                >
                                    <i
                                        :class="`fa fa-${selectedExerciseType.icon} text-gray-600`"
                                    ></i>
                                    <span>{{ selectedExerciseType.name }}</span>
                                </span>
                                <span v-else class="text-gray-400"
                                    >Selectează tipul</span
                                >
                                <i
                                    :class="`fa fa-chevron-${showExerciseDropdown ? 'up' : 'down'} text-gray-400 transition-transform`"
                                ></i>
                            </button>

                            <div
                                v-if="showExerciseDropdown"
                                class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto"
                            >
                                <div
                                    v-for="exerciseType in exerciseTypes"
                                    :key="exerciseType.id"
                                    @click="selectExerciseType(exerciseType)"
                                    class="px-4 py-3 hover:bg-gray-100 cursor-pointer flex items-center space-x-3 transition-colors duration-150"
                                >
                                    <i
                                        :class="`fa fa-${exerciseType.icon} text-gray-600 w-4`"
                                    ></i>
                                    <span>{{ exerciseType.name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Etichetă</label
                        >
                        <input
                            v-model="exerciseForm.label"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                            placeholder="Scurtă descriere"
                        />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            @click="showExerciseModal = false"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200"
                        >
                            Renunță
                        </button>
                        <button
                            type="submit"
                            :disabled="
                                exerciseForm.processing || !selectedExerciseType
                            "
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="exerciseForm.processing"
                                >Se adaugă...</span
                            >
                            <span v-else>Adaugă exercițiu</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch, nextTick } from "vue";
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
    auth: Object,
});

// State
const selectedDayIndex = ref(0);
const showAddModal = ref(false);
const showExerciseModal = ref(false);
const entryMode = ref("catalog"); // 'catalog' or 'direct'
const selectedFoodItem = ref(null);
const showExerciseDropdown = ref(false);
const selectedExerciseType = ref(null);
const foodSearchTerm = ref("");
const showFoodDropdown = ref(false);

// Computed
const selectedDay = computed(() => {
    if (!props.foodEntries || !props.foodEntries[selectedDayIndex.value]) {
        return null;
    }

    // Get the base day data
    const dayData = { ...props.foodEntries[selectedDayIndex.value] };

    // The exercises should already be part of the day data from the backend
    // but make sure the array exists
    if (!dayData.exercises) {
        dayData.exercises = [];
    }

    return dayData;
});

const canEdit = computed(() => {
    return props.auth?.user?.name === "Călina";
});

const isCalina = computed(() => {
    return props.auth?.user?.name === "Călina";
});

const isCurrentWeek = computed(() => {
    const now = new Date();
    const startOfWeek = new Date(now);
    startOfWeek.setDate(now.getDate() - now.getDay() + 1); // Monday
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6); // Sunday

    const pageStartDate = new Date(props.startDate);
    return pageStartDate >= startOfWeek && pageStartDate <= endOfWeek;
});

const unitDisplay = computed(() => {
    if (!selectedFoodItem.value) return "";

    const unitName = selectedFoodItem.value.unit_name || "";
    const baseQty = selectedFoodItem.value.unit_base_quantity || 1;

    if (baseQty == 1) {
        return unitName;
    }
    return `${baseQty} ${unitName}`;
});

const filteredFoodItems = computed(() => {
    if (!foodSearchTerm.value) return props.foodItems || [];

    const searchLower = foodSearchTerm.value.toLowerCase();
    return (props.foodItems || []).filter(
        (item) =>
            item.name.toLowerCase().includes(searchLower) ||
            (item.brand && item.brand.toLowerCase().includes(searchLower)),
    );
});

const ratingOptions = [
    {
        value: 1,
        emoji: "😤",
        label: "Jale extremală",
        class: "bg-red-500 hover:bg-red-600 text-white",
    },
    {
        value: 2,
        emoji: "🥴",
        label: "Nasol",
        class: "bg-orange-500 hover:bg-orange-600 text-white",
    },
    {
        value: 3,
        emoji: "🤷‍♂️",
        label: "Meh",
        class: "bg-gray-500 hover:bg-gray-600 text-white",
    },
    {
        value: 4,
        emoji: "✅",
        label: "OK",
        class: "bg-blue-500 hover:bg-blue-600 text-white",
    },
    {
        value: 5,
        emoji: "🎉",
        label: "Forță",
        class: "bg-green-500 hover:bg-green-600 text-white",
    },
];

// Initialize selected day
if (props.foodEntries && props.foodEntries.length > 0) {
    const foundIndex = props.foodEntries.findIndex(
        (day) => day.dayNameHuman === props.selectedDayName,
    );
    selectedDayIndex.value = foundIndex >= 0 ? foundIndex : 0;
}

// Forms
const entryForm = useForm({
    time: new Date().toLocaleTimeString("en-GB", {
        hour: "2-digit",
        minute: "2-digit",
    }),
    // Catalog mode fields
    food_item_id: "",
    multiplier: 1,
    // Direct mode fields
    direct_name: "",
    direct_kcal: "",
    direct_protein: "",
    // Common fields
    description: "",
    date: "",
});

const exerciseForm = useForm({
    exercise_type_id: "",
    label: "",
    date: "",
});

// Watch for selected day changes to update form dates
watch(
    selectedDay,
    (newDay) => {
        if (newDay) {
            const dateStr = new Date(newDay.date).toISOString().split("T")[0];
            entryForm.date = dateStr;
            exerciseForm.date = dateStr;
        }
    },
    { immediate: true },
);

// Watch for entry mode changes to clear opposing fields
watch(entryMode, (newMode) => {
    if (newMode === "catalog") {
        // Clear direct mode fields
        entryForm.direct_name = "";
        entryForm.direct_kcal = "";
        entryForm.direct_protein = "";
    } else {
        // Clear catalog mode fields
        entryForm.food_item_id = "";
        entryForm.multiplier = 1;
        selectedFoodItem.value = null;
        foodSearchTerm.value = "";
        showFoodDropdown.value = false;
    }
});

// Watch for modal changes to reset form
watch(showAddModal, (newValue) => {
    if (newValue) {
        resetEntryForm();
    }
});

// Click outside handler for food dropdown
const handleClickOutside = (event) => {
    // Check if click is outside the dropdown but inside the modal
    const isInsideDropdown = event.target.closest(".food-dropdown-container");
    const isInsideModal = event.target.closest(".bg-white.rounded-2xl"); // The modal content

    if (!isInsideDropdown && isInsideModal) {
        showFoodDropdown.value = false;
        // If nothing is selected, clear the search term
        if (!selectedFoodItem.value) {
            foodSearchTerm.value = "";
        }
    }
};

// Add click outside listener when dropdown is shown
watch(showFoodDropdown, (isOpen) => {
    if (isOpen) {
        nextTick(() => {
            const modal = document.querySelector(".bg-white.rounded-2xl");
            if (modal) {
                modal.addEventListener("click", handleClickOutside);
            }
        });
    } else {
        const modal = document.querySelector(".bg-white.rounded-2xl");
        if (modal) {
            modal.removeEventListener("click", handleClickOutside);
        }
    }
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
    return new Date(dateString).toLocaleTimeString("en-GB", {
        hour: "2-digit",
        minute: "2-digit",
    });
};

const formatDateRange = () => {
    const startDate = new Date(props.startDate);
    // Calculate the end of a 7-day week from start date (6 days later)
    const endDate = new Date(startDate);
    endDate.setDate(startDate.getDate() + 6);

    const startDay = startDate.getDate();
    const endDay = endDate.getDate();
    const startMonth =
        props.roMonthNames[String(startDate.getMonth() + 1).padStart(2, "0")];
    const endMonth =
        props.roMonthNames[String(endDate.getMonth() + 1).padStart(2, "0")];

    if (startMonth === endMonth) {
        return `${startDay}-${endDay} ${startMonth}`;
    }
    return `${startDay} ${startMonth} - ${endDay} ${endMonth}`;
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

const getRemainingKcalClass = (remaining) => {
    if (remaining >= 0) return "bg-green-100 text-green-800";
    if (remaining >= -200) return "bg-yellow-100 text-yellow-800";
    return "bg-red-100 text-red-800";
};

const getRemainingProteinClass = (remaining) => {
    if (remaining <= 0) return "bg-green-100 text-green-800";
    if (remaining <= 29) return "bg-yellow-100 text-yellow-800";
    return "bg-red-100 text-red-800";
};

const getRatingEmoji = (rating) => {
    const option = ratingOptions.find((r) => r.value === rating);
    return option ? `${option.emoji} ${option.label}` : "";
};

// Utility function to format numbers nicely (remove unnecessary decimals)
const formatNumber = (value, decimals = 1) => {
    const num = parseFloat(value || 0);
    const formatted = num.toFixed(decimals);
    // Remove trailing zeros and decimal point if not needed
    return formatted.replace(/\.?0+$/, "");
};

// Method to handle food item selection
const selectFoodItem = (foodItem) => {
    selectedFoodItem.value = foodItem;
    entryForm.food_item_id = foodItem.id;
    foodSearchTerm.value = "";
    showFoodDropdown.value = false;

    // Clear direct fields when using catalog
    entryForm.direct_name = "";
    entryForm.direct_kcal = "";
    entryForm.direct_protein = "";
};

const clearFoodSelection = () => {
    selectedFoodItem.value = null;
    entryForm.food_item_id = "";
    foodSearchTerm.value = "";
    showFoodDropdown.value = false;
};

const directCaloriesPerUnit = ref("");
const directProteinPerUnit = ref("");
const directMultiplier = ref(1);

// Helper method to reset the form
const resetEntryForm = () => {
    entryForm.reset();
    entryForm.time = new Date().toLocaleTimeString("en-GB", {
        hour: "2-digit",
        minute: "2-digit",
    });
    entryForm.multiplier = 1;
    // Set the date from the currently selected day
    if (selectedDay.value) {
        const dateStr = new Date(selectedDay.value.date)
            .toISOString()
            .split("T")[0];
        entryForm.date = dateStr;
        exerciseForm.date = dateStr; // Also set exercise form date
    }
    entryMode.value = "catalog";
    selectedFoodItem.value = null;
    foodSearchTerm.value = "";
    showFoodDropdown.value = false;
    // Reset direct mode variables
    directCaloriesPerUnit.value = "";
    directProteinPerUnit.value = "";
    directMultiplier.value = 1;
};

// Actions
const addFoodEntry = () => {
    // Prevent double submission
    if (entryForm.processing) return;

    // Validate catalog mode
    if (entryMode.value === "catalog") {
        if (!entryForm.food_item_id || !selectedFoodItem.value) {
            alert("Te rog selectează un aliment din catalog.");
            return;
        }
        // Clear direct fields when using catalog
        entryForm.direct_name = "";
        entryForm.direct_kcal = "";
        entryForm.direct_protein = "";
    } else {
        // Validate direct mode
        if (
            !entryForm.direct_name ||
            !directCaloriesPerUnit.value ||
            !directProteinPerUnit.value ||
            !directMultiplier.value
        ) {
            alert(
                "Te rog completează toate câmpurile pentru intrarea directă.",
            );
            return;
        }
        // Calculate totals and set hidden fields
        entryForm.direct_kcal = Math.round(
            (directCaloriesPerUnit.value || 0) * (directMultiplier.value || 1),
        );
        entryForm.direct_protein =
            (directProteinPerUnit.value || 0) * (directMultiplier.value || 1);
        // Clear catalog fields when using direct
        entryForm.food_item_id = null;
        entryForm.multiplier = 1;
    }

    entryForm.post(route("food-entries.store"), {
        onSuccess: () => {
            showAddModal.value = false;
            resetEntryForm();
        },
        onError: (errors) => {
            console.error("Form submission errors:", errors);
        },
    });
};

const selectExerciseType = (exerciseType) => {
    selectedExerciseType.value = exerciseType;
    exerciseForm.exercise_type_id = exerciseType.id;
    showExerciseDropdown.value = false;
};

const addExercise = () => {
    exerciseForm.post(route("exercises.store"), {
        onSuccess: () => {
            showExerciseModal.value = false;
            exerciseForm.reset();
            selectedExerciseType.value = null;
        },
    });
};

const deleteEntry = (entryId) => {
    if (confirm("Sigur vrei să ștergi această intrare?")) {
        router.delete(route("food-entries.destroy", entryId));
    }
};

const updateGoal = (goalTypeId) => {
    const currentProgress = getGoalProgress(goalTypeId);
    router.post(route("goals.update", goalTypeId), {
        date: new Date(selectedDay.value.date).toISOString().split("T")[0],
        goal_type_id: goalTypeId,
        qty: currentProgress + 1,
    });
};

const addThumb = (entryId, foodItemId, type) => {
    if (
        confirm(
            `Sigur vrei să dai ${type === "up" ? "thumbs up" : "thumbs down"}?`,
        )
    ) {
        const url = foodItemId
            ? route("thumbs.store", {
                  food_entry_id: entryId,
                  food_item_id: foodItemId,
              })
            : route("thumbs.store", { food_entry_id: entryId });

        router.post(url, {
            type: type,
            date: new Date(selectedDay.value.date).toISOString().split("T")[0],
        });
    }
};

const closeDay = () => {
    if (confirm("Sigur vrei să închizi această zi?")) {
        router.post(route("days.store"), {
            date: new Date(selectedDay.value.date).toISOString().split("T")[0],
            done: 1,
        });
    }
};

const addRating = (rating) => {
    router.post(route("days.update", selectedDay.value.dayObject.id), {
        rating: rating,
    });
};

// Navigation
const goToDate = (date) => {
    router.get(route("food-entries.index", { date: date }));
};

const goToPreviousWeek = () => {
    const newDate = new Date(props.startDate);
    newDate.setDate(newDate.getDate() - 7);
    goToDate(newDate.toISOString().split("T")[0]);
};

const goToPreviousDay = () => {
    const newDate = new Date(props.startDate);
    newDate.setDate(newDate.getDate() - 1);
    goToDate(newDate.toISOString().split("T")[0]);
};

const goToNextDay = () => {
    const newDate = new Date(props.startDate);
    newDate.setDate(newDate.getDate() + 7);
    goToDate(newDate.toISOString().split("T")[0]);
};

const goToNextWeek = () => {
    const newDate = new Date(props.startDate);
    newDate.setDate(newDate.getDate() + 7);
    goToDate(newDate.toISOString().split("T")[0]);
};

const goToCurrentWeek = () => {
    const now = new Date();
    goToDate(now.toISOString().split("T")[0]);
};

const goToNotificationDate = (notification) => {
    const data = JSON.parse(notification.data);
    goToDate(data.date);
};

const markNotificationRead = (notificationId) => {
    router.get(route("notification.read", notificationId));
};

const deleteExercise = (exerciseId) => {
    if (confirm("Sigur vrei să ștergi acest exercițiu?")) {
        router.delete(route("exercises.destroy", exerciseId));
    }
};
</script>
