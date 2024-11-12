<script setup>
import Button from "@/Components/Buttons/Button.vue";
import InputGroup from "@/Components/Forms/InputGroup.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
  current_password: "",
  password: "",
  password_confirmation: "",
});

const updatePassword = () => {
  form.put(route("password.update"), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset("password", "password_confirmation");
        passwordInput.value.focus();
      }
      if (form.errors.current_password) {
        form.reset("current_password");
        currentPasswordInput.value.focus();
      }
    },
  });
};
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">Update Password</h2>

      <p class="mt-1 text-sm text-gray-600">
        Ensure your account is using a long, random password to stay secure.
      </p>
    </header>

    <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
      <InputGroup
        label="Current Password"
        v-model="form.current_password"
        :error="form.errors.current_password"
        type="password"
        placeholder="•••••"
      />

      <InputGroup
        label="New Password"
        v-model="form.password"
        :error="form.errors.password"
        type="password"
        placeholder="•••••"
      />

      <InputGroup
        label="Confirm new password"
        v-model="form.password_confirmation"
        :error="form.errors.password_confirmation"
        type="password"
        placeholder="•••••"
      />

      <div class="flex items-center gap-4">
        <Button variant="primary" type="submit" :disabled="form.processing">
          Save
        </Button>
      </div>
    </form>
  </section>
</template>
