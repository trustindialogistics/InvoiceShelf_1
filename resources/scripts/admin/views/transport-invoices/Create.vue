<template>
  <BasePage class="relative">
    <form @submit.prevent="submit">
      <BasePageHeader :title="pageTitle">
        <BaseBreadcrumb>
          <BaseBreadcrumbItem :title="$t('general.home')" to="/admin/dashboard" />
          <BaseBreadcrumbItem title="Transport Invoices" to="/admin/transport-invoices" />
          <BaseBreadcrumbItem :title="pageTitle" to="#" active />
        </BaseBreadcrumb>

        <template #actions>
          <a v-if="isEdit && current?.unique_hash" :href="pdfUrl" target="_blank">
            <BaseButton class="mr-3" variant="primary-outline" type="button">
              {{ $t('general.view_pdf') }}
            </BaseButton>
          </a>

          <BaseButton :loading="isSaving" :disabled="isSaving" variant="primary" type="submit">
            <template #left="slotProps">
              <BaseIcon v-if="!isSaving" name="ArrowDownOnSquareIcon" :class="slotProps.class" />
            </template>
            Save
          </BaseButton>
        </template>
      </BasePageHeader>

      <div class="mt-6 space-y-6">
        <div class="grid grid-cols-12 gap-4">
          <div class="col-span-12 md:col-span-6">
            <BaseInputGroup label="Customer (Party)">
              <BaseCustomerSelectInput
                v-model="form.customer_id"
                :placeholder="$t('customers.type_or_click')"
                value-prop="id"
                label="name"
              />
            </BaseInputGroup>
          </div>

          <div class="col-span-12 md:col-span-3">
            <BaseInputGroup label="Bill No. (LR No.)">
              <BaseInput v-model="form.lr_number" />
            </BaseInputGroup>
          </div>

          <div class="col-span-12 md:col-span-3">
            <BaseInputGroup label="Branch Code">
              <BaseInput v-model="form.branch_code" />
            </BaseInputGroup>
          </div>

          <div class="col-span-12 md:col-span-3">
            <BaseInputGroup label="Bill Date">
              <BaseDatePicker v-model="form.invoice_date" :calendar-button="true" calendar-button-icon="calendar" />
            </BaseInputGroup>
          </div>

          <div class="col-span-12 md:col-span-3">
            <BaseInputGroup label="Payment Due Date">
              <BaseDatePicker v-model="form.due_date" :calendar-button="true" calendar-button-icon="calendar" />
            </BaseInputGroup>
          </div>
        </div>

        <div class="flex items-center justify-between">
          <h2 class="text-base font-medium text-gray-900">Transport Rows</h2>
          <BaseButton variant="primary-outline" type="button" @click="addRow">
            <template #left="slotProps">
              <BaseIcon name="PlusIcon" :class="slotProps.class" />
            </template>
            Add Row
          </BaseButton>
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
                <th class="p-2" />
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, idx) in form.rows" :key="idx" class="border-t border-gray-200">
                <td class="p-2 text-sm text-gray-700">{{ idx + 1 }}</td>
                <td class="p-2"><BaseInput v-model="row.consignment_no" /></td>
                <td class="p-2">
                  <BaseDatePicker v-model="row.old_bill_date" :calendar-button="true" calendar-button-icon="calendar" />
                </td>
                <td class="p-2"><BaseInput v-model="row.invoice_no" /></td>
                <td class="p-2"><BaseInput v-model="row.destination" /></td>
                <td class="p-2"><BaseInput v-model="row.vehicle_no" /></td>
                <td class="p-2"><BaseInput v-model="row.pkg" type="number" /></td>
                <td class="p-2"><BaseInput v-model="row.charged_weight" type="number" /></td>
                <td class="p-2"><BaseInput v-model="row.rate" type="number" /></td>
                <td class="p-2"><BaseInput v-model="row.other_charge" type="number" /></td>
                <td class="p-2"><BaseInput v-model="row.dd_charge" type="number" /></td>
                <td class="p-2"><BaseInput v-model="row.amount" type="number" /></td>
                <td class="p-2">
                  <BaseButton variant="danger-outline" type="button" @click="removeRow(idx)">
                    <BaseIcon name="TrashIcon" />
                  </BaseButton>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="flex justify-end text-sm font-medium text-gray-900">
          Grand Total: {{ grandTotal }}
        </div>
      </div>
    </form>
  </BasePage>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useTransportInvoiceStore } from '@/scripts/admin/stores/transport-invoice'

const route = useRoute()
const router = useRouter()
const store = useTransportInvoiceStore()

const isSaving = ref(false)
const current = ref(null)

const isEdit = computed(() => !!route.params.id)
const pageTitle = computed(() => (isEdit.value ? 'Edit Transport Invoice' : 'New Transport Invoice'))

const form = reactive({
  customer_id: null,
  lr_number: '',
  branch_code: '',
  invoice_date: '',
  due_date: '',
  rows: [],
})

const pdfUrl = computed(() => {
  return current.value?.unique_hash ? `/transport-invoices/pdf/${current.value.unique_hash}` : '#'
})

const grandTotal = computed(() => {
  const sum = form.rows.reduce((acc, r) => acc + Number(r.amount || 0), 0)
  return sum.toFixed(2)
})

function addRow() {
  form.rows.push({
    consignment_no: '',
    old_bill_date: '',
    invoice_no: '',
    destination: '',
    vehicle_no: '',
    pkg: null,
    charged_weight: null,
    rate: null,
    other_charge: null,
    dd_charge: null,
    amount: null,
  })
}

function removeRow(idx) {
  form.rows.splice(idx, 1)
  if (form.rows.length === 0) {
    addRow()
  }
}

async function load() {
  if (!isEdit.value) {
    addRow()
    return
  }

  const res = await store.fetchTransportInvoice(route.params.id)
  current.value = res.data.data

  form.customer_id = current.value.customer_id
  form.lr_number = current.value.lr_number
  form.branch_code = current.value.branch_code
  form.invoice_date = current.value.invoice_date
  form.due_date = current.value.due_date
  form.rows = (current.value.rows || []).map((r) => ({
    consignment_no: r.consignment_no,
    old_bill_date: r.old_bill_date,
    invoice_no: r.invoice_no,
    destination: r.destination,
    vehicle_no: r.vehicle_no,
    pkg: r.pkg,
    charged_weight: r.charged_weight,
    rate: r.rate,
    other_charge: r.other_charge,
    dd_charge: r.dd_charge,
    amount: r.amount,
  }))

  if (!form.rows.length) {
    addRow()
  }
}

async function submit() {
  isSaving.value = true
  try {
    if (isEdit.value) {
      const res = await store.updateTransportInvoice(route.params.id, form)
      current.value = res.data.data
    } else {
      const res = await store.createTransportInvoice(form)
      current.value = res.data.data
      router.push(`/admin/transport-invoices/${current.value.id}/view`)
    }
  } finally {
    isSaving.value = false
  }
}

onMounted(load)
</script>

