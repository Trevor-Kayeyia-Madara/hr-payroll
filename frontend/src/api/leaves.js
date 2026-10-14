import api from './index';

export const getLeaves = (params) => api.get('/leaves', { params });
export const getLeave = (id) => api.get(`/leaves/${id}`);
export const createLeave = (data) => api.post('/leaves', data);
export const updateLeave = (id, data) => api.put(`/leaves/${id}`, data);
export const deleteLeave = (id) => api.delete(`/leaves/${id}`);
export const approveLeave = (id, data) => api.post(`/leaves/${id}/approve`, data);
export const rejectLeave = (id, data) => api.post(`/leaves/${id}/reject`, data);
