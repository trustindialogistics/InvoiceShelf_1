<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-100 px-4 py-10">
    <NotificationRoot />

    <div
      class="
        flex
        w-full
        max-w-md
        flex-col
        rounded-lg
        bg-white
        px-6
        py-10
        shadow
        text-gray-900
        sm:px-10
      "
    >
      <div class="w-full">
        <div class="flex items-center justify-center w-full mb-8">
          <MainLogo
            v-if="!loginPageLogo"
            class="block h-auto max-w-full w-48"
          />

          <img
            v-else
            :src="loginPageLogo"
            class="block h-auto max-w-full w-48"
            alt="Company Logo"
          />
        </div>

        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup>
import NotificationRoot from '@/scripts/components/notifications/NotificationRoot.vue'
import MainLogo from '@/scripts/components/icons/MainLogo.vue'
import { computed } from 'vue'

const loginPageLogo = computed(() => {
  if (window.login_page_logo) {
    return window.login_page_logo
  }

  return false
})
</script>

<style scoped>
</style>
