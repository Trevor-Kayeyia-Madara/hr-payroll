import api from './index';

export const getAttendances = (params) => api.get('/attendances', { params });
export const getAttendance = (id) => api.get(`/attendances/${id}`);
export const createAttendance = (data) => api.post('/attendances', data);
export const updateAttendance = (id, data) => api.put(`/attendances/${id}`, data);
export const deleteAttendance = (id) => api.delete(`/attendances/${id}`);
export const clockIn = (data) => api.post('/attendances/clock-in', data);
export const clockOut = (data) => api.post('/attendances/clock-out', data);
