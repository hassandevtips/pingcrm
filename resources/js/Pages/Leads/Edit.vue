<template>
  <div>

    <Head :title="`${form.first_name} ${form.last_name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/leads">Leads</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.first_name }} {{ form.last_name }}
    </h1>
    <trashed-message v-if="lead.deleted_at" class="mb-6" @restore="restore"> This lead has been deleted.
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2"
            label="First name" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Last name" />
          <select-input v-model="form.organization_id" :error="form.errors.organization_id"
            class="pb-8 pr-6 w-full lg:w-1/2" label="Organization">
            <option :value="null" />
            <option v-for="organization in organizations" :key="organization.id" :value="organization.id">{{
              organization.name }}</option>
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
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!lead.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button"
            @click="destroy">Delete Lead</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update
            Lead</loading-button>
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
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    lead: Object,
    organizations: Array,
    contacts: Array,
    statuses: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: this.lead.first_name,
        last_name: this.lead.last_name,
        organization_id: this.lead.organization_id,
        contact_id: this.lead.contact_id,
        email: this.lead.email,
        phone: this.lead.phone,
        description: this.lead.description,
        status: this.lead.status,
      }),
    }
  },
  computed: {
    contacts() {
      return this.contacts.filter(contact => contact.organization_id === this.form.organization_id);
    }
  },
  methods: {
    update() {
      this.form.put(`/leads/${this.lead.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this lead?')) {
        this.$inertia.delete(`/leads/${this.lead.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this lead?')) {
        this.$inertia.put(`/leads/${this.lead.id}/restore`)
      }
    },
  },
}
</script>
