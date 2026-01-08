import api from '@/services/api'

// Fetch all loans with approvals and steps
export const getLoans = () => api.get('/loans?include=approval.steps.role');

// Fetch a single loan by ID
export const getLoan = (id) => api.get(`/loans/${id}?include=approval.steps.role`);

// Create a new loan
export const createLoan = (data) => api.post('/loans', data);

// Update an existing loan
export const updateLoan = (id, data) => api.put(`/loans/${id}`, data);

// Delete a loan
export const deleteLoan = (id) => api.delete(`/loans/${id}`);
