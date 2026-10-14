import api from './index';

export const getPayslipSettings = () => api.get('/payslip-settings');
export const updatePayslipSettings = (data) => api.put('/payslip-settings', data);
export const resetPayslipSettings = () => api.post('/payslip-settings/reset');
