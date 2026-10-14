import api from './index';

export const getTaxRules = () => api.get('/tax-rules');
export const getTaxRule = (id) => api.get(`/tax-rules/${id}`);
export const createTaxRule = (data) => api.post('/tax-rules', data);
export const updateTaxRule = (id, data) => api.put(`/tax-rules/${id}`, data);
export const deleteTaxRule = (id) => api.delete(`/tax-rules/${id}`);
