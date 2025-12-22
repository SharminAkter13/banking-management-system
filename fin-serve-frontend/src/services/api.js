import axios from 'axios';

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api', // change if needed
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

// Attach token automatically
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  console.log("Current Token:", token); // Add this
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;
