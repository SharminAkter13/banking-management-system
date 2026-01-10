// LoanService.js
import api from '@/services/api'


// API functions only
export const getLoans = () => api.get('/loans?include=approval.steps.role');
export const getLoan = (id) => api.get(`/loans/${id}?include=approval.steps.role`);
export const createLoan = (data) => api.post('/loans', data);
export const updateLoan = (id, data) => api.put(`/loans/${id}`, data);
export const deleteLoan = (id) => api.delete(`/loans/${id}`);
export const getBranches = () => api.get(`/branches`);
export const getLoanTypes = () => api.get(`/loan-types`);
export const getCustomers = () => api.get(`/customers`);