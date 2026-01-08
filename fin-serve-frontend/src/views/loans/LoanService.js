import api from '@/services/api'
import { onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
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
export const getBranches = () => api.get(`/branches`)
export const getLoanTypes = () => api.get(`/loan-types`)


const auth = useAuthStore()

const form = ref({
  customer_id: '',  // will be set dynamically
  branch_id: '',
  loan_type_id: '',
  principal_amount: 0,
  interest_rate: 0,
  duration_months: 1,
  emi_amount: 0,
  total_payable: 0,
  remaining_balance: 0,
  status: 'Pending'
})

onMounted(async () => {
  // If user is not loaded but token exists, fetch user
  if (!auth.user && auth.token) {
    await auth.fetchUser()  // you need to create this action in the store
  }

  if (auth.user) {
    form.value.customer_id = auth.user.id
    console.log('Customer ID set:', form.value.customer_id)
  }
})
