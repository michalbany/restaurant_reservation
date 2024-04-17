<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { computed, defineProps } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    form: Object,
})

const schema = computed(() => ({
    Datum: "required|minDate",
    "Čas rezervace": `required|minTime:${props.form.date}`,
    "Počet osob": "required|minVal:1|maxVal:10",
}));


const submit = () => {
    router.reload({
        preserveState: true,
        only: ["freeTables"],
        data: {
            date: props.form.date,
            time: props.form.time,
            capacity: props.form.capacity,
        },
    });
};

</script>

<template>
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
            <ErrorMessage class="text-red-500" name="Čas rezervace" />
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
        <PrimaryButton type="submit"> Ověřit dostupnost </PrimaryButton>
    </VeeForm>
</template>
