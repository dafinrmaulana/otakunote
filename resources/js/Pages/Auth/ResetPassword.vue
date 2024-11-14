<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import Button from "@/Components/Buttons/Button.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const props = defineProps({
  email: {
    type: String,
    required: true,
  },
  token: {
    type: String,
    required: true,
  },
});

const form = useForm({
  token: props.token,
  email: props.email,
  password: "",
  password_confirmation: "",
});

const submit = () => {
  form.post(route("password.store"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Reset Password" />

    <div
      class="flex justify-start flex-col items-start sm:items-center text-start w-full gap-5 sm:gap-3"
    >
      <ApplicationLogo class="size-10 text-indigo-500" />
      <div class="space-y-1 sm:text-center">
        <h1 class="font-semibold text-2xl">Set New Password</h1>
        <p class="text-sm leading-4 text-gray-500">
          Please enter your new password
        </p>
      </div>
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

      <div class="mt-4">
        <InputLabel for="password" value="Password" />

        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="current-password"
          placeholder="Enter password"
          :error="form.errors.password"
        />

        <InputError class="mt-1" :message="form.errors.password" />
      </div>
      <div class="mt-4">
        <InputLabel for="password" value="Confirm Password" />

        <TextInput
          id="password_confirmation"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password_confirmation"
          required
          autocomplete="current-password"
          placeholder="Enter password"
          :error="form.errors.password_confirmation"
        />

        <InputError class="mt-1" :message="form.errors.password_confirmation" />
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
      </div>
    </form>
  </GuestLayout>
</template>
