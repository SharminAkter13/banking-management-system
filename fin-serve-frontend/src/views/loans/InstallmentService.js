import api from "@/services/api";

export const getInstallments = () => {
  return api.get("/installments");
};

export const payInstallment = (data) => {
  return api.post("/installments/pay", data);
};
