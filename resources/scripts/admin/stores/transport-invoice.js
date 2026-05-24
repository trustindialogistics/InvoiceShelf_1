import http from '@/scripts/http'
import { defineStore } from 'pinia'
import { handleError } from '@/scripts/helpers/error-handling'

export const useTransportInvoiceStore = (useWindow = false) => {
  const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore

  return defineStoreFunc('transportInvoice', {
    state: () => ({
      transportInvoices: [],
      transportInvoiceTotalCount: 0,
      isFetching: false,
      current: null,
    }),

    actions: {
      fetchTransportInvoices(params) {
        return new Promise((resolve, reject) => {
          http
            .get('/api/v1/transport-invoices', { params })
            .then((response) => {
              this.transportInvoices = response.data.data
              this.transportInvoiceTotalCount = response.data.meta?.total || 0
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchTransportInvoice(id) {
        return new Promise((resolve, reject) => {
          http
            .get(`/api/v1/transport-invoices/${id}`)
            .then((response) => {
              this.current = response.data.data
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      createTransportInvoice(payload) {
        return new Promise((resolve, reject) => {
          http
            .post('/api/v1/transport-invoices', payload)
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updateTransportInvoice(id, payload) {
        return new Promise((resolve, reject) => {
          http
            .put(`/api/v1/transport-invoices/${id}`, payload)
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      deleteTransportInvoice(id) {
        return new Promise((resolve, reject) => {
          http
            .delete(`/api/v1/transport-invoices/${id}`)
            .then((response) => resolve(response))
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },
    },
  })
}

