<template>
  <BasePage>
    <BasePageHeader :title="title">
      <BaseBreadcrumb>
        <BaseBreadcrumbItem :title="$t('general.home')" to="/admin/dashboard" />
        <BaseBreadcrumbItem title="Transport Invoices" to="/admin/transport-invoices" />
        <BaseBreadcrumbItem :title="title" to="#" active />
      </BaseBreadcrumb>

      <template #actions>
        <router-link :to="`/admin/transport-invoices/${route.params.id}/edit`">
          <BaseButton variant="primary-outline">
            <template #left="slotProps">
              <BaseIcon name="PencilIcon" :class="slotProps.class" />
            </template>
            Edit
          </BaseButton>
        </router-link>

        <a v-if="invoice?.unique_hash" :href="`/transport-invoices/pdf/${invoice.unique_hash}`" target="_blank">
          <BaseButton class="ml-3" variant="primary">
            {{ $t('general.view_pdf') }}
          </BaseButton>
        </a>
      </template>
    </BasePageHeader>

    <div v-if="invoice" class="mt-6 space-y-4">
      <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 md:col-span-6">
          <div class="text-sm text-gray-500">Party</div>
          <div class="text-base font-medium text-gray-900">{{ invoice.customer?.name }}</div>
        </div>
        <div class="col-span-12 md:col-span-3">
          <div class="text-sm text-gray-500">LR No.</div>
          <div class="text-base font-medium text-gray-900">{{ invoice.lr_number }}</div>
        </div>
        <div class="col-span-12 md:col-span-3">
          <div class="text-sm text-gray-500">Branch Code</div>
          <div class="text-base font-medium text-gray-900">{{ invoice.branch_code }}</div>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="p-2 text-left text-xs font-semibold text-gray-700">#</th>
              <th class="p-2 text-left text-xs font-semibold text-gray-700">Consignment No</th>
              <th class="p-2 text-left text-xs font-semibold text-gray-700">Old Bill Date</th>
              <th class="p-2 text-left text-xs font-semibold text-gray-700">Invoice No</th>
              <th class="p-2 text-left text-xs font-semibold text-gray-700">Destination</th>
              <th class="p-2 text-left text-xs font-semibold text-gray-700">Vehicle No</th>
              <th class="p-2 text-left text-xs font-semibold text-gray-700">Pkg</th>
              <th class="p-2 text-left text-xs font-semibold text-gray-700">Charged Wt</th>
              <th class="p-2 text-left text-xs font-semibold text-gray-700">Rate</th>
              <th class="p-2 text-left text-xs font-semibold text-gray-700">Other</th>
              <th class="p-2 text-left text-xs font-semibold text-gray-700">DD</th>
              <th class="p-2 text-left text-xs font-semibold text-gray-700">Amount</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(r, idx) in invoice.rows" :key="idx" class="border-t border-gray-200">
              <td class="p-2 text-sm text-gray-700">{{ idx + 1 }}</td>
              <td class="p-2 text-sm text-gray-700">{{ r.consignment_no }}</td>
              <td class="p-2 text-sm text-gray-700">{{ r.old_bill_date }}</td>
              <td class="p-2 text-sm text-gray-700">{{ r.invoice_no }}</td>
              <td class="p-2 text-sm text-gray-700">{{ r.destination }}</td>
              <td class="p-2 text-sm text-gray-700">{{ r.vehicle_no }}</td>
              <td class="p-2 text-sm text-gray-700">{{ r.pkg }}</td>
              <td class="p-2 text-sm text-gray-700">{{ r.charged_weight }}</td>
              <td class="p-2 text-sm text-gray-700">{{ r.rate }}</td>
              <td class="p-2 text-sm text-gray-700">{{ r.other_charge }}</td>
              <td class="p-2 text-sm text-gray-700">{{ r.dd_charge }}</td>
              <td class="p-2 text-sm text-gray-700">{{ r.amount }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </BasePage>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useTransportInvoiceStore } from '@/scripts/admin/stores/transport-invoice'

const route = useRoute()
const store = useTransportInvoiceStore()
const invoice = ref(null)

const title = computed(() => invoice.value?.lr_number || 'Transport Invoice')

async function load() {
  const res = await store.fetchTransportInvoice(route.params.id)
  invoice.value = res.data.data
}

onMounted(load)
</script>

