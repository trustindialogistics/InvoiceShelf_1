<template>
  <div class="grid grid-cols-12 gap-8 mt-6 mb-8">
    <BaseCustomerSelectPopup
      v-model="invoiceStore.newInvoice.customer"
      :valid="v.customer_id"
      :content-loading="isLoading"
      type="invoice"
      :label="isLrReceiptTemplate ? 'Consignee' : ''"
      :class="isLrReceiptTemplate ? 'col-span-12 lg:col-span-4 pr-0' : 'col-span-12 lg:col-span-5 pr-0'"
    />

    <div
      v-if="isLrReceiptTemplate"
      class="col-span-12 lg:col-span-4 pr-0"
    >
      <BaseContentPlaceholders v-if="isLoading">
        <BaseContentPlaceholdersBox
          :rounded="true"
          class="w-full"
          style="min-height: 170px"
        />
      </BaseContentPlaceholders>
      <div v-else class="max-h-[173px]">
        <div
          v-if="selectedConsignor"
          class="flex flex-col p-4 bg-white border border-gray-200 border-solid min-h-[170px] rounded-md"
        >
          <div class="flex relative justify-between mb-2">
            <BaseText
              :text="selectedConsignor.name || selectedConsignor.display_name"
              class="flex-1 text-base font-medium text-left text-gray-900"
            />
            <a
              class="relative my-0 ml-6 text-sm flex items-center font-medium cursor-pointer text-primary-500"
              @click="resetConsignor"
            >
              <BaseIcon name="XCircleIcon" class="text-gray-500 h-4 w-4 mr-1" />
              {{ $t('general.deselect') }}
            </a>
          </div>
          <div class="grid grid-cols-2 gap-8 mt-2">
            <div class="flex flex-col">
              <label class="mb-1 text-sm font-medium text-left text-gray-400 uppercase whitespace-nowrap">
                Phone
              </label>
              <label class="relative w-11/12 text-sm truncate">
                {{ selectedConsignor.phone }}
              </label>
            </div>
            <div class="flex flex-col">
              <label class="mb-1 text-sm font-medium text-left text-gray-400 uppercase whitespace-nowrap">
                GST No
              </label>
              <label class="relative w-11/12 text-sm truncate">
                {{ selectedConsignor.tax_id }}
              </label>
            </div>
          </div>
        </div>

        <Popover v-else v-slot="{ open }" class="relative flex flex-col rounded-md">
          <PopoverButton
            :class="{
              'focus:ring-2 focus:ring-primary-400': !open,
            }"
            class="w-full outline-hidden rounded-md"
            @click="ensureConsignorsLoaded"
          >
            <div class="relative flex justify-center px-0 p-0 py-16 bg-white border border-gray-200 border-solid rounded-md min-h-[170px]">
              <BaseIcon
                name="UserIcon"
                class="flex justify-center !w-10 !h-10 p-2 mr-5 text-sm text-white bg-gray-200 rounded-full font-base"
              />
              <div class="mt-1">
                <label class="text-lg font-medium text-gray-900">Consignor</label>
              </div>
            </div>
          </PopoverButton>

          <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-1 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-1 opacity-0"
          >
            <div v-if="open" class="absolute min-w-full z-10">
              <PopoverPanel
                v-slot="{ close }"
                focus
                static
                class="overflow-hidden rounded-md shadow-lg ring-1 ring-black/5 bg-white"
              >
                <div class="relative">
                  <BaseInput
                    v-model="consignorSearch"
                    container-class="m-4"
                    :placeholder="$t('general.search')"
                    type="text"
                    icon="search"
                    @update:modelValue="debounceSearchConsignors"
                  />

                  <ul class="max-h-80 flex flex-col overflow-auto list border-t border-gray-200">
                    <li
                      v-for="customer in customerStore.customers"
                      :key="customer.id"
                      class="flex px-6 py-2 border-b border-gray-200 border-solid cursor-pointer hover:cursor-pointer hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
                      @click="selectConsignor(customer, close)"
                    >
                      <div class="flex items-center justify-center h-10 w-10 mr-4 rounded-full bg-gray-100 uppercase text-primary-500">
                        {{ (customer.name || customer.display_name || 'C').charAt(0) }}
                      </div>
                      <div class="flex-1 flex flex-col text-left">
                        <span class="text-sm font-medium text-gray-900">
                          {{ customer.name || customer.display_name }}
                        </span>
                        <span class="text-xs text-gray-500">
                          {{ customer.phone || customer.tax_id }}
                        </span>
                      </div>
                    </li>
                  </ul>

                  <button
                    type="button"
                    class="flex items-center justify-center w-full px-6 py-3 bg-gray-100 cursor-pointer"
                    @click="openCustomerModal(close)"
                  >
                    <BaseIcon name="PlusIcon" class="h-5 text-primary-400" />
                    <label class="m-0 ml-3 text-sm leading-none cursor-pointer font-base text-primary-400">
                      {{ $t('customers.add_new_customer') }}
                    </label>
                  </button>
                </div>
              </PopoverPanel>
            </div>
          </transition>
        </Popover>
      </div>
    </div>

    <BaseInputGrid :class="isLrReceiptTemplate ? 'col-span-12 lg:col-span-4' : 'col-span-12 lg:col-span-7'">
      <BaseInputGroup
        :label="isLrReceiptTemplate ? 'Date' : $t('invoices.invoice_date')"
        :content-loading="isLoading"
        required
        :error="v.invoice_date.$error && v.invoice_date.$errors[0].$message"
      >
        <BaseDatePicker
          v-model="invoiceStore.newInvoice.invoice_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
          :enableTime="enableTime"
          :time24hr="time24h"
        />
      </BaseInputGroup>

      <BaseInputGroup
        v-if="!isLrReceiptTemplate"
        :label="$t('invoices.due_date')"
        :content-loading="isLoading"
      >
        <BaseDatePicker
          v-model="invoiceStore.newInvoice.due_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="isLrReceiptTemplate ? 'Docket No.' : $t('invoices.invoice_number')"
        :content-loading="isLoading"
        :error="v.invoice_number.$error && v.invoice_number.$errors[0].$message"
        required
      >
        <BaseInput
          v-model="invoiceStore.newInvoice.invoice_number"
          :content-loading="isLoading"
          @input="v.invoice_number.$touch()"
        />
      </BaseInputGroup>

      <ExchangeRateConverter
        :store="invoiceStore"
        store-prop="newInvoice"
        :v="v"
        :is-loading="isLoading"
        :is-edit="isEdit"
        :customer-currency="invoiceStore.newInvoice.currency_id"
      />
    </BaseInputGrid>
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { useDebounceFn } from '@vueuse/core'
import ExchangeRateConverter from '@/scripts/admin/components/estimate-invoice-common/ExchangeRateConverter.vue'
import { useInvoiceStore } from '@/scripts/admin/stores/invoice'
import { useCompanyStore } from '@/scripts/admin/stores/company'
import { useCustomerStore } from '@/scripts/admin/stores/customer'
import { useModalStore } from '@/scripts/stores/modal'
import { useGlobalStore } from '@/scripts/admin/stores/global'

const props = defineProps({
  v: {
    type: Object,
    default: null,
  },
  isLoading: {
    type: Boolean,
    default: false,
  },
  isEdit: {
    type: Boolean,
    default: false,
  },
})

const invoiceStore = useInvoiceStore()
const companyStore = useCompanyStore()
const customerStore = useCustomerStore()
const modalStore = useModalStore()
const globalStore = useGlobalStore()
const selectedConsignor = ref(null)
const consignorSearch = ref('')

const enableTime = computed(() => {
  return (
    companyStore.selectedCompanySettings.invoice_use_time === 'YES'
  );
})
const time24h = computed(() => {
  return (
    companyStore.selectedCompanySettings.carbon_time_format.indexOf('H') > -1
  );
})

const isLrReceiptTemplate = computed(() => {
  return invoiceStore.newInvoice.template_name === 'lr_receipt'
})

const debounceSearchConsignors = useDebounceFn(() => {
  fetchConsignors(consignorSearch.value)
}, 500)

function getInvoiceField(label) {
  return invoiceStore.newInvoice.customFields?.find((_field) => _field.label === label)
}

function setInvoiceField(label, value) {
  const field = getInvoiceField(label)

  if (field) {
    field.value = value || ''
  }
}

function syncConsigneeFields(customer) {
  setInvoiceField('Consignee', customer?.name || customer?.display_name)
  setInvoiceField('Consignee Phone No', customer?.phone)
  setInvoiceField('Consignee GST No', customer?.tax_id)
}

function syncConsignorFields(customer) {
  setInvoiceField('Consignor', customer?.name || customer?.display_name)
  setInvoiceField('Consignor Phone No', customer?.phone)
  setInvoiceField('Consignor GST No', customer?.tax_id)
}

function selectConsignor(customer, close) {
  selectedConsignor.value = customer
  syncConsignorFields(customer)
  close()
  consignorSearch.value = ''
}

function resetConsignor() {
  selectedConsignor.value = null
  syncConsignorFields(null)
}

function fetchConsignors(search = '') {
  customerStore.fetchCustomers({
    display_name: search,
    page: 1,
  })
}

function ensureConsignorsLoaded() {
  if (!customerStore.customers.length) {
    fetchConsignors()
  }
}

function openCustomerModal(close) {
  close?.()
  globalStore.fetchCurrencies()
  globalStore.fetchCountries()

  modalStore.openModal({
    title: 'Add Customer',
    componentName: 'CustomerModal',
    variant: 'md',
  })
}

watch(
  () => invoiceStore.newInvoice.customer,
  (customer) => {
    if (isLrReceiptTemplate.value) {
      syncConsigneeFields(customer)
    }
  },
  { deep: true }
)

watch(
  () => invoiceStore.newInvoice.customFields,
  () => {
    if (!isLrReceiptTemplate.value) {
      return
    }

    syncConsigneeFields(invoiceStore.newInvoice.customer)
    syncConsignorFields(selectedConsignor.value)
  },
  { deep: false }
)

watch(
  isLrReceiptTemplate,
  (isLr) => {
    if (!isLr) {
      selectedConsignor.value = null
    }
  },
  { immediate: true }
)

</script>
