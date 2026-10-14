<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Departments</h1>
      <button
        @click="showModal = true"
        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Add Department
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="department in departments"
        :key="department.id"
        class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition"
      >
        <div class="flex items-start justify-between">
          <div>
            <h3 class="text-xl font-bold text-gray-800">{{ department.name }}</h3>
            <p class="text-gray-600 mt-2">{{ department.description || 'No description' }}</p>
          </div>
          <div class="flex space-x-2">
            <button @click="editDepartment(department)" class="text-blue-600 hover:text-blue-800">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
            </button>
            <button @click="deleteDepartment(department)" class="text-red-600 hover:text-red-800">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
            </button>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t">
          <p class="text-sm text-gray-500">
            {{ department.employees?.length || 0 }} employees
          </p>
        </div>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
          <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <h2 class="text-2xl font-bold text-white">{{ editingDepartment ? 'Edit Department' : 'Add Department' }}</h2>
          </div>
          <form @submit.prevent="saveDepartment" class="p-6 space-y-5">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Department Name</label>
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="Enter department name"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
              <textarea
                v-model="form.description"
                rows="4"
                placeholder="Enter department description"
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
                Save Department
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
import { getDepartments, createDepartment, updateDepartment, deleteDepartment as deleteDepartmentApi } from '../api/departments'

const departments = ref([])
const showModal = ref(false)
const editingDepartment = ref(null)

const form = ref({
  name: '',
  description: ''
})

const loadDepartments = async () => {
  try {
    const response = await getDepartments()
    departments.value = response.data
  } catch (err) {
    console.error('Failed to load departments')
  }
}

const closeModal = () => {
  showModal.value = false
  editingDepartment.value = null
  form.value = { name: '', description: '' }
}

const editDepartment = (department) => {
  editingDepartment.value = department
  form.value = { ...department }
  showModal.value = true
}

const saveDepartment = async () => {
  try {
    if (editingDepartment.value) {
      await updateDepartment(editingDepartment.value.id, form.value)
    } else {
      await createDepartment(form.value)
    }
    closeModal()
    loadDepartments()
  } catch (err) {
    console.error('Failed to save department')
  }
}

const deleteDepartment = async (department) => {
  if (confirm(`Delete ${department.name}?`)) {
    try {
      await deleteDepartmentApi(department.id)
      loadDepartments()
    } catch (err) {
      console.error('Failed to delete department')
    }
  }
}

onMounted(() => {
  loadDepartments()
})
</script>
