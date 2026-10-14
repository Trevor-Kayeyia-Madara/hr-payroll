<template>
  <div class="p-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Attendance</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Clock In</h3>
        <form @submit.prevent="handleClockIn" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
            <select
              v-model="clockInForm.employee_id"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">Select Employee</option>
              <option v-for="emp in employees" :key="emp.id" :value="emp.id">{{ emp.name }}</option>
            </select>
          </div>
          <button
            type="submit"
            class="w-full px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition"
          >
            Clock In
          </button>
        </form>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Clock Out</h3>
        <form @submit.prevent="handleClockOut" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
            <select
              v-model="clockOutForm.employee_id"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">Select Employee</option>
              <option v-for="emp in employees" :key="emp.id" :value="emp.id">{{ emp.name }}</option>
            </select>
          </div>
          <button
            type="submit"
            class="w-full px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition"
          >
            Clock Out
          </button>
        </form>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
      <div class="p-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Today's Attendance</h3>
      </div>
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="attendance in attendances" :key="attendance.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 text-gray-900">{{ attendance.employee?.name }}</td>
            <td class="px-6 py-4 text-gray-600">{{ attendance.date }}</td>
            <td class="px-6 py-4 text-gray-600">{{ attendance.check_in || '-' }}</td>
            <td class="px-6 py-4 text-gray-600">{{ attendance.check_out || '-' }}</td>
            <td class="px-6 py-4 text-gray-600">{{ attendance.hours_worked || '-' }}</td>
            <td class="px-6 py-4">
              <span
                :class="[
                  'px-2 py-1 text-xs font-semibold rounded-full',
                  attendance.status === 'present' ? 'bg-green-100 text-green-800' :
                  attendance.status === 'late' ? 'bg-yellow-100 text-yellow-800' :
                  'bg-red-100 text-red-800'
                ]"
              >
                {{ attendance.status }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getAttendances, clockIn, clockOut } from '../api/attendances'
import { getEmployees } from '../api/employees'

const attendances = ref([])
const employees = ref([])
const clockInForm = ref({ employee_id: '' })
const clockOutForm = ref({ employee_id: '' })

const loadAttendances = async () => {
  try {
    const today = new Date().toISOString().split('T')[0]
    const response = await getAttendances({ date_from: today, date_to: today })
    attendances.value = response.data
  } catch (err) {
    console.error('Failed to load attendances')
  }
}

const loadEmployees = async () => {
  try {
    const response = await getEmployees()
    employees.value = response.data
  } catch (err) {
    console.error('Failed to load employees')
  }
}

const handleClockIn = async () => {
  try {
    await clockIn(clockInForm.value)
    clockInForm.value.employee_id = ''
    loadAttendances()
  } catch (err) {
    console.error('Failed to clock in')
  }
}

const handleClockOut = async () => {
  try {
    await clockOut(clockOutForm.value)
    clockOutForm.value.employee_id = ''
    loadAttendances()
  } catch (err) {
    console.error('Failed to clock out')
  }
}

onMounted(() => {
  loadAttendances()
  loadEmployees()
})
</script>
