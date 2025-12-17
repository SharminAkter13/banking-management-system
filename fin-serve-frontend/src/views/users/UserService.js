import api from '@/services/api'

// Users CRUD
export const getUsers = () => api.get('/users') // returns array of users with role_id
export const getUser = (id) => api.get(`/users/${id}`)
export const createUser = (data) => api.post('/users', data)
export const updateUser = (id, data) => api.put(`/users/${id}`, data)
export const deleteUser = (id) => api.delete(`/users/${id}`)

// Roles
export const getRoles = () => api.get('/roles') // returns array [{id, name, slug}]
