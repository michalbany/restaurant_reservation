<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TableFilterForm from "@/Components/TableFilterForm.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { watchEffect, ref } from "vue";
import CreateReservationForm from "@/Components/CreateReservationForm.vue";
import Modal from "@/Components/Modal.vue";

const freeTables = ref([]);
const { tables } = usePage().props;

const showModal = ref(false);

watchEffect(() => {
    freeTables.value = usePage().props.freeTables;
});

const form = useForm({
    date: "",
    time: "",
    capacity: "",
});

const reservationForm = useForm({
    tableId: "",
    note: "",
});

const isTablefree = (table) => {
    if (freeTables.value) {
        return freeTables.value.some((freeTable) => freeTable.id === table);
    }
};

const tableClass = (table) => {
    if (freeTables.value) {
        if (isTablefree(table)) {
            return "bg-green-400 cursor-pointer";
        } else {
            return "bg-red-400 cursor-not-allowed";
        }
    }
};

const selectTable = (tableId) => {
    if (isTablefree(tableId)) {
        reservationForm.tableId = tableId;
        showModal.value = true;
    }
};

const closeModal = () => {
    showModal.value = false;
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <TableFilterForm :form="form" />

                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <div class="flex flex-wrap gap-3">
                        <div
                            class="w-full grid grid-cols-4 justify-items-center items-center gap-1 h-full min-h-96 rounded-lg bg-indigo-100 p-3"
                        >
                            <a
                                @click="selectTable(table.id)"
                                :class="[tableClass(table.id), `table-${table.capacity}`]"
                                class="h-20 flex relative z-30 items-center justify-center text-white font-bold rounded w-20 bg-gray-600"
                                v-for="table in tables"
                                :key="table.id"
                            >
                                {{ table.id }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="showModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Vytvořit rezeraci
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Zkontrolujte níže uvedené informace a potvrďte rezervaci
                    nebo se vraťte zpět k výběru stolu.
                </p>

                <CreateReservationForm
                    :form="form"
                    :reservationForm="reservationForm"
                    @close="closeModal"
                />
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style scoped>

.table-2::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 130%;
    border-radius: 20px;
    background: linear-gradient(90deg, #000 0%, #000 10%, transparent 10%, transparent 90%, #000 90%, #000 100%);
    height: 50%;
    transform: translate(-50%, -50%);
}

.table-4::before {
    content: "";
    position: absolute;
    bottom: 0%;
    left: 50%;
    width: 130%;
    border-radius: 20px;
    background: linear-gradient(90deg, #000 0%, #000 10%, transparent 10%, transparent 90%, #000 90%, #000 100%);
    height: 50%;
    transform: translateX(-50%);
}

.table-4::after {
    content: "";
    position: absolute;
    top: 0%;
    left: 50%;
    width: 130%;
    border-radius: 20px;
    background: linear-gradient(90deg, #000 0%, #000 10%, transparent 10%, transparent 90%, #000 90%, #000 100%);
    height: 50%;
    transform: translateX(-50%);
}

.table-6::before {
    content: "";
    position: absolute;
    top: 0%;
    left: 50%;
    width: 150%;
    border-radius: 20px;
    height: 100%;
    background: linear-gradient(90deg, #000 0%, #000 13%, transparent 13%, transparent 87%, #000 87%, #000 100%);
    transform: translateX(-50%);
}

.table-6::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 50%;
    border-radius: 20px;
    background: linear-gradient(0deg, #000 0%, #000 10%, transparent 10%, transparent 90%, #000 90%, #000 100%);
    height: 130%;
    transform: translate(-50%, -50%);
}

</style>
