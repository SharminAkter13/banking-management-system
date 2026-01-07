import api from '@/services/api'

// Fetch all interest rates
export const getInterestRates = () => api.get('/interest-rates')

// Fetch single rate
export const getInterestRate = (id) => api.get(`/interest-rates/${id}`)

// Create new rate
export const createInterestRate = (data) => api.post('/interest-rates', data)

// Update rate
export const updateInterestRate = (id, data) => api.put(`/interest-rates/${id}`, data)

// Delete rate
export const deleteInterestRate = (id) => api.delete(`/interest-rates/${id}`)
