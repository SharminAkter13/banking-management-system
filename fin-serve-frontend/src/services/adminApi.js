import api from "./api";

/* ===========================
   ACCOUNTS API
=========================== */

export const getAccounts = () => api.get("/accounts");

export const getAccount = (id) => api.get(`/accounts/${id}`);

export const createAccount = (data) => api.post("/accounts", data);

export const updateAccount = (id, data) => api.put(`/accounts/${id}`, data);

export const deleteAccount = (id) => api.delete(`/accounts/${id}`);

/* ===========================
   ACCOUNT TYPES API
=========================== */

export const getAccountTypes = () => api.get("/account-types");

export const createAccountType = (data) => api.post("/account-types", data);

export const updateAccountType = (id, data) => api.put(`/account-types/${id}`, data);

export const deleteAccountType = (id) => api.delete(`/account-types/${id}`);

/* ===========================
   ACCOUNT STATUSES API
=========================== */

export const getAccountStatuses = () => api.get("/account-statuses");

export const createAccountStatus = (data) => api.post("/account-statuses", data);

export const updateAccountStatus = (id, data) => api.put(`/account-statuses/${id}`, data);

export const deleteAccountStatus = (id) => api.delete(`/account-statuses/${id}`);
