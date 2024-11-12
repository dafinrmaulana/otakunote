<script setup>
import Button from "@/Components/Buttons/Button.vue";
import InputFile from "@/Components/Forms/InputFile.vue";
import InputGroup from "@/Components/Forms/InputGroup.vue";
import { Link, router, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const user = usePage().props.auth.user;
const fileName = ref("");
const disabled = ref(false);
const page = usePage();

const form = useForm({
  name: user.name,
  email: user.email,
  profile_image: user.profile_image,
});

const handleFileSelected = (e) => {
  fileName.value = e.name;
  form.profile_image = e;
};

const save = () => {
  router.post(
    route("profile.update", page.props.auth.user.id),
    {
      _method: "put",
      ...form.data(),
    },
    {
      onStart: () => (disabled.value = true),
      onFinish: () => (disabled.value = false),
      onError: (errors) => (form.errors = errors),
      onSuccess: () => (form.errors = {}),
    }
  );
};
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

      <p class="mt-1 text-sm text-gray-600">
        Update your account's profile information and email address.
      </p>
    </header>

    <form @submit.prevent="save" class="mt-6 space-y-6">
      <InputGroup
        label="Name"
        v-model="form.name"
        :error="form.errors.name"
        :autofocus="true"
      />

      <InputGroup
        label="Email"
        v-model="form.email"
        :error="form.errors.email"
      />
      <InputGroup label="Profile Image">
        <InputFile
          class="w-full"
          :error="form.errors.profile_image"
          :placeholder="fileName"
          @update:file="(e) => handleFileSelected(e)"
        />
      </InputGroup>

      <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="mt-2 text-sm text-gray-800">
          Your email address is unverified.
          <Link
            :href="route('verification.send')"
            method="post"
            as="button"
            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            Click here to re-send the verification email.
          </Link>
        </p>

        <div
          v-show="status === 'verification-link-sent'"
          class="mt-2 text-sm font-medium text-green-600"
        >
          A new verification link has been sent to your email address.
        </div>
      </div>

      <div class="flex items-center gap-4">
        <Button variant="primary" :disabled="disabled" type="submit">
          Save
        </Button>

        <Transition
          enter-active-class="transition ease-in-out"
          enter-from-class="opacity-0"
          leave-active-class="transition ease-in-out"
          leave-to-class="opacity-0"
        >
          <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
            Saved.
          </p>
        </Transition>
      </div>
    </form>
  </section>
</template>
