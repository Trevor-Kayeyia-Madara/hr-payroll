import api from './index';

export const getPayrolls = (params) => api.get('/payrolls', { params });
export const getPayroll = (id) => api.get(`/payrolls/${id}`);
export const createPayroll = (data) => api.post('/payrolls', data);
export const updatePayroll = (id, data) => api.put(`/payrolls/${id}`, data);
export const deletePayroll = (id) => api.delete(`/payrolls/${id}`);
export const generatePeriodPayroll = (data) => api.post('/payrolls/generate-period', data);
