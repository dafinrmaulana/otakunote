<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Button from "@/Components/Buttons/Button.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { ArrowLeftIcon } from "@heroicons/vue/20/solid";
import { Head, router, useForm } from "@inertiajs/vue3";

defineProps({
  status: {
    type: String,
  },
});

const form = useForm({
  email: "",
});

const submit = () => {
  form.post(route("password.email"), {
    onSuccess: () => router.push({ name: "login" }),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Forgot Password" />

    <div
      class="flex justify-start flex-col items-start sm:items-center text-start w-full gap-5 sm:gap-3"
    >
      <ApplicationLogo class="size-10 text-indigo-500" />
      <div class="space-y-1 sm:text-center">
        <h1 class="font-semibold text-2xl">Forgot Password?</h1>
        <p class="text-sm leading-4 text-gray-500">
          No worries, we'll send you a link to reset your password
        </p>
      </div>
    </div>

    <div
      v-if="status"
      class="text-xs w-full font-medium bg-green-100 text-green-600 p-2 rounded-md text-start -my-5 sm:my-0"
    >
      {{ status }}
    </div>

    <form @submit.prevent="submit" class="w-full">
      <div>
        <InputLabel for="email" value="Email" />

        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autofocus
          autocomplete="email"
          placeholder="Enter your registered email"
          :error="form.errors.email"
        />

        <InputError class="mt-1" :message="form.errors.email" />
      </div>

      <div class="mt-4 flex flex-col gap-3 items-center justify-end">
        <Button
          variant="primary"
          type="submit"
          class="!h-10 w-full !p-0"
          :disabled="form.processing"
        >
          Reset Password
        </Button>
        <Button
          variant="outline-primary"
          :type="form.processing ? 'button' : 'link'"
          :href="route('login')"
          class="!h-10 w-full !p-0 text-sm gap-2"
          :disabled="form.processing"
        >
          <ArrowLeftIcon class="size-4" />
          Back To Login
        </Button>
      </div>
    </form>
  </GuestLayout>
</template>
