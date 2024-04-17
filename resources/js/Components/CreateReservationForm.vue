<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { router } from "@inertiajs/vue3";
import { computed, defineProps, defineEmits, ref } from "vue";


const props = defineProps({
    form: Object,
    reservationForm: Object,
});

const emit = defineEmits(['close']);


const formProcessing = ref(false);

const schema = computed(() => ({
    Datum: "required|minDate",
    'Čas rezervace': `required|minTime:${props.form.date}`,
    'Počet osob': "required|minVal:1|maxVal:10",
    'Číslo stolu': "required|numeric",
    'Poznámka': "max:255",
}));

const submit = () => {
    router.post(route("reservation.store"), {
        date: props.form.date,
        time: props.form.time,
        capacity: props.form.capacity,
        tableId: props.reservationForm.tableId,
        note: props.reservationForm.note,
        },
        {
        preserveScroll: true,
        onBefore: () => {
            formProcessing.value = true;
        },
        onFinish: () => {
            formProcessing.value = false;
            emit("close");
        },
        onSuccess: () => props.reservationForm.reset(),
    });

}


</script>

<template>
    <VeeForm
        :validation-schema="schema"
        @submit="submit"
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
            <PrimaryButton @click="submit" :disabled="formProcessing"> Vytvořit </PrimaryButton>
            <SecondaryButton @click="emit('close')">
                Zrušit
            </SecondaryButton>
        </div>
    </VeeForm>
</template>
