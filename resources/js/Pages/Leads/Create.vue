<template>
  <div>

    <Head title="Create Lead" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/leads">Leads</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2"
            label="First name" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Last name" />
          <select-input v-model="form.organization_id" :error="form.errors.organization_id"
            class="pb-8 pr-6 w-full lg:w-1/2" label="Organization">
            <option :value="null" />
            <option v-for="organization in organizations" :key="organization.id" :value="organization.id">
              {{ organization.name }}
            </option>
          </select-input>

          <select-input v-model="form.contact_id" :error="form.errors.contact_id" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Contacts">
            <option :value="null" />
            <option v-for="contact in contacts" :key="contact.id" :value="contact.id">
              {{ contact.first_name }} {{ contact.last_name }}
            </option>
          </select-input>

          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Phone" />
          <text-input v-model="form.description" :error="form.errors.description" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Description" />

          <select-input v-model="form.status" :error="form.errors.status" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Status">
            <option :value="null" />
            <option v-for="status in statuses" :key="key" :value="key">
              {{ status }}
            </option>
          </select-input>

        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create Contact</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    organizations: Array,
    contacts: Array,
    statuses: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: '',
        last_name: '',
        organization_id: null,
        contact_id: null,
        email: '',
        phone: '',
        description: '',
        status: 'new',
      }),
    }
  },
  computed: {
    contacts() {
      return this.contacts.filter(contact => contact.organization_id === this.form.organization_id);
    }
  },
  methods: {
    store() {
      this.form.post('/leads')
    },
  },
}
</script>
