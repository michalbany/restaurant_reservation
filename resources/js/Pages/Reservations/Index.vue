<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const { reservations } = usePage().props;

// Seřadíme podle nejnovějších rezervací
const sortedReservations = computed(() => {
  return reservations.sort((a, b) => new Date(b.reservation_time) - new Date(a.reservation_time));
});

// Zjistíme, zda je rezervace minulá nebo nadcházející
function getStatus(reservation) {
  const now = new Date();
  const reservationDate = new Date(reservation.reservation_time);
  return reservationDate >= now ? 'nadcházející' : 'proběhlé';
}

</script>

<template>
    <Head title="Moje Rezervace" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Moje Rezervace
            </h2>
        </template>

            <div class="mt-6 overflow-hidden sm:rounded-lg">
                <table v-if="reservations && reservations.length"  class="table-auto table-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-2 text-md font-medium text-gray-900">Číslo Stolu</th>
                            <th class="px-6 py-2 text-md font-medium text-gray-900">Datum</th>
                            <th class="px-6 py-2 text-md font-medium text-gray-900">Čas</th>
                            <th class="px-6 py-2 text-md font-medium text-gray-900">Počet hostů</th>
                            <th class="px-6 py-2 text-md font-medium text-gray-900">Datum Vytvoření</th>
                            <th class="px-6 py-2 text-md font-medium text-gray-900">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="reservation in sortedReservations" :key="reservation.id">
                            <td class="px-6 py-4 whitespace-nowrap text-md font-bold text-gray-900">{{reservation.table_id}}</td>
                            <td class="px-6 py-4 text-md font-bold whitespace-nowrap text-gray-900">{{ new Date(reservation.reservation_time).toLocaleDateString() }}</td>
                            <td class="px-6 py-4 text-md font-bold whitespace-nowrap text-gray-900">{{ new Date(reservation.reservation_time).toLocaleTimeString() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-md text-gray-900">{{reservation.guest_number}}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-md text-gray-500">{{ new Date(reservation.created_at).toLocaleDateString() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-md font-medium"
                                :class="{
                                    'text-green-500': getStatus(reservation) === 'nadcházející',
                                    'text-red-500': getStatus(reservation) === 'proběhlé'
                                }">
                                {{ getStatus(reservation) }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-else class="p-6 bg-white border border-gray-200 rounded-lg">
                    <p class="text-gray-500 text-center">Nemáte žádné rezervace</p>
                </div>
            </div>
        
    </AuthenticatedLayout>
</template>

<style scoped>
        .table-full {
            width: 100%;
        }

        th, td {
            text-align: left;
        }
    </style>