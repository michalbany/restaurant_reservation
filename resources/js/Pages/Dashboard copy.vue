<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, usePage, router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { watchEffect, ref, computed  } from "vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";

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

const reservationSchema = computed(() => ({
    Datum: "required|minDate",
    'Čas rezervace': `required|minTime:${form.date}`,
    'Počet osob': "required|minVal:1|maxVal:10",
    'Číslo stolu': "required|numeric",
    'Poznámka': "max:255",
}));

const schema = computed(() => ({
    Datum: "required|minDate",
    "Čas rezervace": `required|minTime:${form.date}`,
    "Počet osob": "required|minVal:1|maxVal:10",
}));

const submit = () => {
    router.reload({
        preserveState: true,
        only: ["freeTables"],
        data: {
            date: form.date,
            time: form.time,
            capacity: form.capacity,
        },
    });
};

const reservationSubmit = () => {
    console.log(reservationForm);
    console.log(form);
    router.post(route("reservation.store"), {
        date: form.date,
        time: form.time,
        capacity: form.capacity,
        tableId: reservationForm.tableId,
        note: reservationForm.note,
        },
        {
        preserveScroll: true,
        onSuccess: () => showModal.value = false,
    });

}


const isTablefree = (table) => {
    if (freeTables.value) {
        return freeTables.value.some(freeTable => freeTable.id === table);
    }
}

const tableClass = (table) => {
    if (freeTables.value) {
        if (isTablefree(table)) {
            return "bg-green-400 cursor-pointer";
        } else {
            return "bg-red-400 cursor-not-allowed";
        }

    }
}

const selectTable = (tableId) => {
    if (isTablefree(tableId)) {
        reservationForm.tableId = tableId;
        showModal.value = true;
    }
}
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
                <VeeForm
                    :validation-schema="schema"
                    @submit="submit"
                    class="mb-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 flex gap-3 justify-start items-start"
                >
                    <div class="flex flex-col">
                        <VeeField
                            v-model="form.date"
                            name="Datum"
                            type="date"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            placeholder="Datum rezervace"
                        />
                        <ErrorMessage class="text-red-500" name="Datum" />
                    </div>
                    <div class="flex flex-col">
                        <VeeField
                            type="time"
                            v-model="form.time"
                            name="Čas rezervace"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            placeholder="Čas rezervace"
                        />
                        <ErrorMessage
                            class="text-red-500"
                            name="Čas rezervace"
                        />
                    </div>

                    <div class="flex flex-col">
                        <VeeField
                            v-model="form.capacity"
                            name="Počet osob"
                            type="number"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            placeholder="Počet osob"
                        />
                        <ErrorMessage class="text-red-500" name="Počet osob" />
                    </div>
                    <PrimaryButton type="submit">
                        Ověřit dostupnost
                    </PrimaryButton>
                </VeeForm>

                <!-- <button @click="showModal = true" class="bg-indigo-500 text-white font-bold rounded-lg p-2 mt-4">
                    otevrit Modal
                </button> -->

                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <div class="flex flex-wrap gap-3">

                        <div class="w-full grid grid-cols-4 justify-items-center items-center gap-1 h-full min-h-96 rounded-lg bg-indigo-100 p-3">
                            <a @click="selectTable(table.id)" :class="tableClass(table.id)" class="h-20 flex items-center justify-center text-white font-bold rounded w-20 bg-gray-600" v-for="table in tables" :key="table.id">
                            {{ table.id }}
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="showModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Vytvořit rezeraci</h2>

                <p class="mt-1 text-sm text-gray-600">Zkontrolujte níže uvedené informace a potvrďte rezervaci nebo se vraťte zpět k výběru stolu.</p>

                <VeeForm
                    :validation-schema="reservationSchema"
                    @submit="reservationSubmit"
                    class="mt-6 flex flex-col gap-3"
                >

                <div class="flex flex-col">
                    <InputLabel for="date" value="Datum rezervace" />
                    <VeeField
                    v-model="form.date"
                    name="Datum"
                    type="date"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm cursor-not-allowed"
                    placeholder="Datum rezervace"
                    disabled
                    />
                    <ErrorMessage class="text-red-500" name="Datum" />
                </div>
                
                <div class="flex flex-col">
                    <InputLabel for="time" value="Čas rezervace" />
                    <VeeField
                    type="time"
                    v-model="form.time"
                    name="Čas rezervace"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm cursor-not-allowed"
                    placeholder="Čas rezervace"
                    disabled
                    />
                    <ErrorMessage class="text-red-500" name="Čas rezervace" />
                </div>
                
                <div class="flex flex-col">
                    <InputLabel for="number" value="Počet osob" />
                    <VeeField
                    type="number"
                    v-model="form.capacity"
                    name="Počet osob"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm cursor-not-allowed"
                    placeholder="Počet osob"
                    disabled
                    />
                    <ErrorMessage class="text-red-500" name="Počet osob" />
                </div>
                
                <div class="flex flex-col">
                    <InputLabel for="number" value="Číslo stolu" />
                    <VeeField
                    type="number"
                    v-model="reservationForm.tableId"
                    name="Číslo stolu"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm cursor-not-allowed"
                    placeholder="Číslo stolu"
                    disabled
                    />
                    <ErrorMessage class="text-red-500" name="Číslo stolu" />
                </div>
                
                <div class="flex flex-col">
                    <InputLabel for="textarea" value="Poznámka k rezervaci" />
                    <VeeField
                        v-model="reservationForm.note"
                        name="Poznámka"
                        as="textarea"
                        rows="6"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        placeholder="Poznámka"
                    />
                    <ErrorMessage class="text-red-500" name="Poznámka" />
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <PrimaryButton @click="submit"> Vytvořit </PrimaryButton>
                    <SecondaryButton @click="showModal = false"> Zrušit </SecondaryButton>
                </div>
                </VeeForm>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
