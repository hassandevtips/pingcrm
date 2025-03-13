<template>
  <div>

    <Head title="Dashboard" />
    <h1 class="mb-8 text-3xl font-bold">Dashboard</h1>
    <p class="mb-8 leading-normal">
      Hey there! Welcome to Ping CRM, a demo app designed to help illustrate how
      <a class="text-indigo-500 hover:text-orange-600 underline" href="https://inertiajs.com">Inertia.js</a> works.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-semibold text-gray-700">Organizations</h2>
        <p class="mt-2 text-2xl font-bold">{{ organizationsCount }}</p>
      </div>

      <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-semibold text-gray-700">Leads</h2>
        <p class="mt-2 text-2xl font-bold">{{ leadsCount }}</p>
      </div>

      <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-semibold text-gray-700">Contacts</h2>
        <p class="mt-2 text-2xl font-bold">{{ contactsCount }}</p>
      </div>

    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  components: {
    Head,
  },
  layout: Layout,
  props: {
    organizationsCount: Number,
    leadsCount: Number,
    contactsCount: Number,
  },
}
</script>
