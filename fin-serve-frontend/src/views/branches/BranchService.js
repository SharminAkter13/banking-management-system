import api from '@/services/api'  // Import your Axios instance

// Branch CRUD Operations

// Get all branches
export const getBranches = () => {
  return api.get('/branches');  // Fetches all branches
}

// Get a single branch by ID
export const getBranch = (id) => {
  return api.get(`/branches/${id}`);  // Fetches a branch by its ID
}

// Create a new branch
export const createBranch = (data) => {
  return api.post('/branches', data);  // Creates a new branch with the provided data
}

// Update an existing branch
export const updateBranch = (id, data) => {
  return api.put(`/branches/${id}`, data);  // Updates the branch with the provided ID and data
}

// Delete a branch
export const deleteBranch = (id) => {
  return api.delete(`/branches/${id}`);  // Deletes the branch by its ID
}
