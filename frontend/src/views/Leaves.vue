<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Leave Management</h1>
      <button
        @click="showModal = true"
        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Request Leave
      </button>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="leave in leaves" :key="leave.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 text-gray-900">{{ leave.employee?.name }}</td>
            <td class="px-6 py-4 text-gray-600">{{ leave.type }}</td>
            <td class="px-6 py-4 text-gray-600">{{ leave.start_date }} to {{ leave.end_date }}</td>
            <td class="px-6 py-4 text-gray-600">{{ leave.days }}</td>
            <td class="px-6 py-4">
              <span
                :class="[
                  'px-2 py-1 text-xs font-semibold rounded-full',
                  leave.status === 'approved' ? 'bg-green-100 text-green-800' :
                  leave.status === 'rejected' ? 'bg-red-100 text-red-800' :
                  'bg-yellow-100 text-yellow-800'
                ]"
              >
                {{ leave.status }}
              </span>
            </td>
            <td class="px-6 py-4">
              <div class="flex space-x-2">
                <button
                  v-if="leave.status === 'pending'"
                  @click="approveLeave(leave)"
                  class="text-green-600 hover:text-green-800"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </button>
                <button
                  v-if="leave.status === 'pending'"
                  @click="rejectLeave(leave)"
                  class="text-red-600 hover:text-red-800"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Request Leave Modal -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
          <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <h2 class="text-2xl font-bold text-white">Request Leave</h2>
          </div>
          <form @submit.prevent="requestLeave" class="p-6 space-y-5">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Employee</label>
              <select
                v-model="form.employee_id"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
              >
                <option value="">Select Employee</option>
                <option v-for="emp in employees" :key="emp.id" :value="emp.id">{{ emp.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Leave Type</label>
              <select
                v-model="form.type"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
              >
                <option value="annual">Annual Leave</option>
                <option value="sick">Sick Leave</option>
                <option value="maternity">Maternity Leave</option>
                <option value="paternity">Paternity Leave</option>
                <option value="compassionate">Compassionate Leave</option>
              </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
                <input
                  v-model="form.start_date"
                  type="date"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                <input
                  v-model="form.end_date"
                  type="date"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Reason</label>
              <textarea
                v-model="form.reason"
                rows="4"
                placeholder="Provide reason for leave request"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400 resize-none"
              ></textarea>
            </div>
            <div class="flex justify-end space-x-3 pt-4 border-t">
              <button
                type="button"
                @click="closeModal"
                class="px-6 py-3 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all font-semibold"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-6 py-3 text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all font-semibold shadow-lg hover:shadow-xl"
              >
                Submit Request
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
  transform: scale(0.95);
}

.modal-enter-to,
.modal-leave-from {
  opacity: 1;
  transform: scale(1);
}
</style>

<script setup>
import { ref, onMounted } from 'vue'
import { getLeaves, createLeave, approveLeave as approveLeaveApi, rejectLeave as rejectLeaveApi } from '../api/leaves'
import { getEmployees } from '../api/employees'

const leaves = ref([])
const employees = ref([])
const showModal = ref(false)

const form = ref({
  employee_id: '',
  type: 'annual',
  start_date: '',
  end_date: '',
  reason: ''
})

const loadLeaves = async () => {
  try {
    const response = await getLeaves()
    leaves.value = response.data
  } catch (err) {
    console.error('Failed to load leaves')
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

const closeModal = () => {
  showModal.value = false
  form.value = {
    employee_id: '',
    type: 'annual',
    start_date: '',
    end_date: '',
    reason: ''
  }
}

const requestLeave = async () => {
  try {
    await createLeave(form.value)
    closeModal()
    loadLeaves()
  } catch (err) {
    console.error('Failed to request leave')
  }
}

const approveLeave = async (leave) => {
  try {
    await approveLeaveApi(leave.id, { approved_by: 1 })
    loadLeaves()
  } catch (err) {
    console.error('Failed to approve leave')
  }
}

const rejectLeave = async (leave) => {
  const reason = prompt('Rejection reason:')
  if (reason) {
    try {
      await rejectLeaveApi(leave.id, { approved_by: 1, rejection_reason: reason })
      loadLeaves()
    } catch (err) {
      console.error('Failed to reject leave')
    }
  }
}

onMounted(() => {
  loadLeaves()
  loadEmployees()
})
</script>
