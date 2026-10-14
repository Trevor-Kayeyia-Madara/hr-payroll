<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Employees</h1>
      <button
        @click="showModal = true"
        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Add Employee
      </button>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
      <div class="p-4 border-b">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search employees..."
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
      </div>

      <div v-if="loading" class="p-8 text-center text-gray-500">
        Loading...
      </div>

      <div v-else-if="error" class="p-8 text-center text-red-500">
        {{ error }}
      </div>

      <table v-else class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="employee in filteredEmployees" :key="employee.id" class="hover:bg-gray-50">
            <td class="px-6 py-4">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                  <span class="text-blue-600 font-semibold">{{ employee.name.charAt(0) }}</span>
                </div>
                <div>
                  <div class="font-medium text-gray-900">{{ employee.name }}</div>
                  <div class="text-sm text-gray-500">{{ employee.email }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 text-gray-600">{{ employee.department?.name || '-' }}</td>
            <td class="px-6 py-4 text-gray-600">{{ employee.position || '-' }}</td>
            <td class="px-6 py-4">
              <span
                :class="[
                  'px-2 py-1 text-xs font-semibold rounded-full',
                  employee.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                ]"
              >
                {{ employee.status }}
              </span>
            </td>
            <td class="px-6 py-4">
              <div class="flex space-x-2">
                <button @click="editEmployee(employee)" class="text-blue-600 hover:text-blue-800">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                </button>
                <button @click="deleteEmployee(employee)" class="text-red-600 hover:text-red-800">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add/Edit Modal -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
          <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <h2 class="text-2xl font-bold text-white">{{ editingEmployee ? 'Edit Employee' : 'Add Employee' }}</h2>
          </div>
          <form @submit.prevent="saveEmployee" class="p-6 space-y-5">
            <div class="grid grid-cols-2 gap-4">
              <div class="col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  placeholder="Enter full name"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
              <div class="col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  placeholder="email@example.com"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                <input
                  v-model="form.phone"
                  type="text"
                  placeholder="+254..."
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select
                  v-model="form.status"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                >
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Department</label>
                <select
                  v-model="form.department_id"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                >
                  <option value="">Select Department</option>
                  <option v-for="dept in departments" :key="dept.id" :value="dept.id">{{ dept.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tax Rule (Currency)</label>
                <select
                  v-model="form.tax_rule_id"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                >
                  <option value="">Select Tax Rule</option>
                  <option v-for="rule in taxRules" :key="rule.id" :value="rule.id">{{ rule.name }} ({{ rule.currency }})</option>
                </select>
              </div>
              <div class="col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Position</label>
                <input
                  v-model="form.position"
                  type="text"
                  placeholder="Job title"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
              <div class="col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Salary ({{ getCurrencySymbol(form.tax_rule_id ? getTaxRuleCurrency(form.tax_rule_id) : 'KES') }})</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">{{ getCurrencySymbol(form.tax_rule_id ? getTaxRuleCurrency(form.tax_rule_id) : 'KES') }}</span>
                  <input
                    v-model="form.salary"
                    type="number"
                    required
                    placeholder="0.00"
                    class="w-full pl-16 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                  />
                </div>
              </div>
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
                Save Employee
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
import { ref, computed, onMounted } from 'vue'
import { getEmployees, createEmployee, updateEmployee, deleteEmployee as deleteEmployeeApi } from '../api/employees'
import { getDepartments } from '../api/departments'
import { getTaxRules } from '../api/taxRules'

const employees = ref([])
const departments = ref([])
const taxRules = ref([])
const loading = ref(true)
const error = ref('')
const searchQuery = ref('')
const showModal = ref(false)
const editingEmployee = ref(null)

const getCurrencySymbol = (currency) => {
  const symbols = {
    'USD': '$',
    'KES': 'KSh',
    'EUR': '€',
    'GBP': '£',
    'JPY': '¥',
    'CAD': 'C$',
    'AUD': 'A$',
    'ZAR': 'R',
    'NGN': '₦',
    'GHS': 'GH₵',
  }
  return symbols[currency] || currency || ''
}

const getTaxRuleCurrency = (taxRuleId) => {
  const taxRule = taxRules.value.find(tr => tr.id === taxRuleId)
  return taxRule ? taxRule.currency : 'KES'
}

const form = ref({
  name: '',
  email: '',
  phone: '',
  department_id: '',
  position: '',
  salary: '',
  status: 'active',
  tax_rule_id: ''
})

const filteredEmployees = computed(() => {
  if (!searchQuery.value) return employees.value
  const query = searchQuery.value.toLowerCase()
  return employees.value.filter(emp =>
    emp.name.toLowerCase().includes(query) ||
    emp.email.toLowerCase().includes(query)
  )
})

const loadEmployees = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await getEmployees()
    employees.value = response.data
  } catch (err) {
    error.value = 'Failed to load employees'
  } finally {
    loading.value = false
  }
}

const loadDepartments = async () => {
  try {
    const response = await getDepartments()
    departments.value = response.data
  } catch (err) {
    console.error('Failed to load departments')
  }
}

const loadTaxRules = async () => {
  try {
    const response = await getTaxRules()
    taxRules.value = response.data
  } catch (err) {
    console.error('Failed to load tax rules')
  }
}

const closeModal = () => {
  showModal.value = false
  editingEmployee.value = null
  form.value = {
    name: '',
    email: '',
    phone: '',
    department_id: '',
    position: '',
    salary: '',
    status: 'active',
    tax_rule_id: ''
  }
}

const editEmployee = (employee) => {
  editingEmployee.value = employee
  form.value = { ...employee }
  showModal.value = true
}

const saveEmployee = async () => {
  try {
    if (editingEmployee.value) {
      await updateEmployee(editingEmployee.value.id, form.value)
    } else {
      await createEmployee(form.value)
    }
    closeModal()
    loadEmployees()
  } catch (err) {
    error.value = 'Failed to save employee'
  }
}

const deleteEmployee = async (employee) => {
  if (confirm(`Delete ${employee.name}?`)) {
    try {
      await deleteEmployeeApi(employee.id)
      loadEmployees()
    } catch (err) {
      error.value = 'Failed to delete employee'
    }
  }
}

onMounted(() => {
  loadEmployees()
  loadDepartments()
  loadTaxRules()
})
</script>
