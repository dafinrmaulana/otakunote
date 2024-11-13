<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Button from "@/Components/Buttons/Button.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Log in" />

    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
      {{ status }}
    </div>

    <div
      class="flex justify-start flex-col items-start sm:items-center text-start w-full gap-5 sm:gap-3"
    >
      <ApplicationLogo class="size-10 text-violet-600" />
      <div class="space-y-1 sm:text-center">
        <h1 class="font-semibold text-2xl">Welcome back!</h1>
        <p class="text-sm leading-4 text-gray-500">
          Enter your details to get started
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

      <div class="mt-2 block">
        <Link
          v-if="canResetPassword"
          :href="route('password.request')"
          class="rounded-md text-sm text-violet-600 hover:text-violet-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
          Forgot your password?
        </Link>
      </div>

      <div class="mt-4 flex flex-col gap-3 items-center justify-end">
        <Button
          variant="primary"
          type="submit"
          class="!h-10 w-full !p-0"
          :disabled="form.processing"
        >
          Log In
        </Button>
        <div
          class="text-center text-sm text-gray-500 w-full flex items-center gap-2 before:flex-1 before:h-[1px] before:bg-gray-300 after:flex-1 after:h-[1px] after:bg-gray-300"
        >
          Doesn't Have An Account?
        </div>
        <Button
          variant="outline-primary"
          :type="form.processing ? 'button' : 'link'"
          :href="route('register')"
          class="!h-10 w-full !p-0 text-sm"
          :disabled="form.processing"
        >
          Register For Free
        </Button>
      </div>
    </form>
  </GuestLayout>
</template>
