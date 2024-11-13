<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Button from "@/Components/Buttons/Button.vue";
import { ref, watchEffect } from "vue";
import { ArrowLeftIcon } from "@heroicons/vue/20/solid";
import { computed } from "vue";
import { XMarkIcon } from "@heroicons/vue/16/solid";

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const isOnPassword = ref(false);
const isErrorsShow = ref(false);

const submit = () => {
  form.post(route("register"), {
    onFinish: () => form.reset("password", "password_confirmation"),
    onError: () => (isErrorsShow.value = true),
  });
};

watchEffect(() => console.log(form.errors));
</script>

<template>
  <GuestLayout class="sm:*:mt-10 *:relative *:!overflow-y-auto *:!gap-5">
    <Head title="Register" />

    <button
      @click="isOnPassword = false"
      class="absolute right-5 top-8 sm:left-5 sm:top-5 z-10 duration-500"
      type="button"
      :class="
        isOnPassword
          ? 'visible opacity-100 translate-x-0'
          : 'invisible opacity-0 -translate-x-10'
      "
    >
      <ArrowLeftIcon class="size-5" />
    </button>

    <div
      class="flex justify-start flex-col items-start sm:items-center text-start w-full gap-5 sm:gap-3"
    >
      <ApplicationLogo class="size-10 text-violet-600" />
      <div class="space-y-1 sm:text-center">
        <h1 class="font-semibold text-2xl">Welcome To Family!</h1>
        <p class="text-sm leading-4 text-gray-500">
          Please enter your details before get started
        </p>
      </div>
    </div>

    <ul
      v-if="isErrorsShow"
      class="flex flex-col bg-red-100 text-red-500 list-disc list-inside w-full p-3 rounded-md text-xs relative"
    >
      <li v-if="form.errors.name">
        {{ form.errors.name }}
      </li>
      <li v-if="form.errors.email">
        {{ form.errors.email }}
      </li>
      <li v-if="form.errors.password">
        {{ form.errors.password }}
      </li>

      <button
        @click="isErrorsShow = false"
        class="hover:bg-red-500 bg-transparent duration-150 hover:text-white absolute right-1 top-1 p-1 rounded"
      >
        <XMarkIcon class="size-3" />
      </button>
    </ul>

    <form @submit.prevent="submit" class="w-full">
      <div
        class="flex flex-col duration-500"
        :class="
          isOnPassword
            ? '-translate-x-[calc(100%+2rem)] invisible opacity-0'
            : 'visible opacity-100'
        "
      >
        <div>
          <InputLabel for="name" value="Name" />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            placeholder="Your full name"
            :error="form.errors.name"
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>
        <div class="mt-4">
          <InputLabel for="email" value="Email" />
          <TextInput
            id="email"
            type="text"
            class="mt-1 block w-full"
            v-model="form.email"
            placeholder="Your active email"
            :error="form.errors.email"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>
      </div>

      <transition name="fade-slide-form">
        <div
          v-show="isOnPassword"
          class="flex flex-col w-full translate-x-0 left-0 absolute -top-4"
        >
          <div class="mt-4">
            <InputLabel for="password" value="Password" />
            <TextInput
              id="password"
              type="password"
              class="mt-1 block w-full"
              v-model="form.password"
              placeholder="•••••"
              :error="form.errors.password"
            />
            <InputError class="mt-2" :message="form.errors.password" />
          </div>
          <div class="mt-4">
            <InputLabel for="password_confirmation" value="Confirm Password" />
            <TextInput
              id="password_confirmation"
              type="password"
              class="mt-1 block w-full"
              v-model="form.password_confirmation"
              placeholder="•••••"
              :error="form.errors.password_confirmation"
            />
            <InputError
              class="mt-2"
              :message="form.errors.password_confirmation"
            />
          </div>
        </div>
      </transition>

      <div class="mt-4 flex flex-col gap-3 items-center justify-end relative">
        <Button
          variant="primary"
          class="!h-10 w-full !p-0 text-sm z-10"
          @click="() => (isOnPassword = true)"
          type="button"
          :disabled="form.processing || form.name == '' || form.email == ''"
          v-if="!isOnPassword"
        >
          Continue
        </Button>
        <Button
          variant="primary"
          class="!h-10 w-full !p-0 text-sm z-10"
          type="submit"
          :disabled="form.processing"
          v-if="isOnPassword"
        >
          Register
        </Button>
        <div
          class="text-center text-sm text-gray-500 w-full flex items-center gap-2 before:flex-1 before:h-[1px] before:bg-gray-300 after:flex-1 after:h-[1px] after:bg-gray-300"
        >
          Already Have An Account?
        </div>
        <Button
          variant="outline-primary"
          :type="form.processing ? 'button' : 'link'"
          :href="route('login')"
          class="!h-10 w-full !p-0 text-sm"
          :disabled="form.processing"
        >
          Login
        </Button>
      </div>
    </form>
  </GuestLayout>
</template>

<style lang="postcss" scoped>
.fade-no-slide-enter-from,
.fade-no-slide-leave-to {
  @apply opacity-0;
}
.fade-no-slide-enter-active,
.fade-no-slide-leave-active {
  @apply duration-200;
}

.fade-slide-form-enter-from,
.fade-slide-form-leave-to {
  @apply translate-x-4 left-full;
}

.fade-slide-form-enter-to,
.fade-slide-form-leave-from {
  @apply translate-x-0 left-0;
}

.fade-slide-form-enter-active,
.fade-slide-form-leave-active {
  @apply duration-500;
}
</style>
