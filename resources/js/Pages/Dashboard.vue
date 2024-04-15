<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, usePage, router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { watchEffect, ref } from "vue";

const freeTables = ref([]);
const { tables } = usePage().props;


watchEffect(() => {
    freeTables.value = usePage().props.freeTables;
});


const form = useForm({
    data: "",
    time: "",
    capacity: "",
});

const schema = {
    Datum: "required",
    "Čas rezervace": "required",
    "Počet osob": "required|minVal:1|maxVal:10",
};

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

const tableClass = (table) => {
    
    if (freeTables.value) {
        if (freeTables.value.some(freeTable => freeTable.id === table)) {
            return "bg-green-400";
        } else {
            return "bg-red-400";
        }

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
                            placeholder="Datum a čas rezervace"
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

                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <div class="flex flex-wrap gap-3">

                        <div class="w-full grid grid-cols-4 justify-items-center items-center gap-1 h-full min-h-96 rounded-lg bg-indigo-100 p-3">
                            <a :class="tableClass(table.id)" class="h-20 flex items-center justify-center text-white font-bold rounded w-20 bg-gray-600" v-for="table in tables" :key="table.id">
                            {{ table.id }}
                            </a>
                        </div>
                        
                        
                        
                        <p v-for="table in freeTables" :key="table.id">
                        {{ table.id }}</p>
                        
                        <!-- <a
                            v-for="table in tables">
                        {{ table.id }}
                        </a>

                        

                        <a
                            :href="route('reservation.create')"
                            class="bg-white p-4 shadow rounded-lg"
                            v-for="table in freeTables"
                            :key="table.id"
                        >
                            <p>id: {{ table.id }}</p>
                            <p>capacity: {{ table.capacity }}</p>
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
