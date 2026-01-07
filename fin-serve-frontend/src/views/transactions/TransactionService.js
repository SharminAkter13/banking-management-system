import api from '@/services/api'

// Get all transactions
export const getTransactions = () => {
  return api.get('/transactions')
}

// Get single transaction
export const getTransaction = (id) => {
  return api.get(`/transactions/${id}`)
}

// Create transaction
export const createTransaction = (data) => {
  return api.post('/transactions', data)
}

// Update transaction (status / notes)
export const updateTransaction = (id, data) => {
  return api.put(`/transactions/${id}`, data)
}

// Delete transaction
export const deleteTransaction = (id) => {
  return api.delete(`/transactions/${id}`)
}
