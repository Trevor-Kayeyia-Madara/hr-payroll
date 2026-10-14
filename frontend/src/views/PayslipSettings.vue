<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Payslip Settings</h1>
      <button
        @click="resetSettings"
        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
      >
        Reset to Default
      </button>
    </div>

    <div class="space-y-8">
      <!-- Company Information -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Company Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
            <input
              v-model="settings.company_name"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <input
              v-model="settings.company_description"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
            <input
              v-model="settings.company_address"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input
              v-model="settings.company_email"
              type="email"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
            <input
              v-model="settings.company_phone"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tax ID</label>
            <input
              v-model="settings.company_tax_id"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Logo URL</label>
            <input
              v-model="settings.company_logo_url"
              type="text"
              placeholder="https://example.com/logo.png"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
        </div>
      </div>

      <!-- Display Options -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Display Options</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <label class="flex items-center space-x-2 cursor-pointer">
            <input
              v-model="settings.show_ytd"
              type="checkbox"
              class="rounded text-purple-600 focus:ring-purple-500"
            />
            <span class="text-sm text-gray-700">Show YTD Totals</span>
          </label>
          <label class="flex items-center space-x-2 cursor-pointer">
            <input
              v-model="settings.show_leave_balances"
              type="checkbox"
              class="rounded text-purple-600 focus:ring-purple-500"
            />
            <span class="text-sm text-gray-700">Show Leave Balances</span>
          </label>
          <label class="flex items-center space-x-2 cursor-pointer">
            <input
              v-model="settings.show_tax_id"
              type="checkbox"
              class="rounded text-purple-600 focus:ring-purple-500"
            />
            <span class="text-sm text-gray-700">Show Tax ID</span>
          </label>
          <label class="flex items-center space-x-2 cursor-pointer">
            <input
              v-model="settings.show_payment_date"
              type="checkbox"
              class="rounded text-purple-600 focus:ring-purple-500"
            />
            <span class="text-sm text-gray-700">Show Payment Date</span>
          </label>
          <label class="flex items-center space-x-2 cursor-pointer">
            <input
              v-model="settings.show_department"
              type="checkbox"
              class="rounded text-purple-600 focus:ring-purple-500"
            />
            <span class="text-sm text-gray-700">Show Department</span>
          </label>
          <label class="flex items-center space-x-2 cursor-pointer">
            <input
              v-model="settings.show_position"
              type="checkbox"
              class="rounded text-purple-600 focus:ring-purple-500"
            />
            <span class="text-sm text-gray-700">Show Position</span>
          </label>
          <label class="flex items-center space-x-2 cursor-pointer">
            <input
              v-model="settings.show_tax_rule"
              type="checkbox"
              class="rounded text-purple-600 focus:ring-purple-500"
            />
            <span class="text-sm text-gray-700">Show Tax Rule</span>
          </label>
        </div>
      </div>

      <!-- Section Titles -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Section Titles</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Employee Section Title</label>
            <input
              v-model="settings.employee_section_title"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Earnings Section Title</label>
            <input
              v-model="settings.earnings_section_title"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Deductions Section Title</label>
            <input
              v-model="settings.deductions_section_title"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">YTD Section Title</label>
            <input
              v-model="settings.ytd_section_title"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Leave Section Title</label>
            <input
              v-model="settings.leave_section_title"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
        </div>
      </div>

      <!-- Labels -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Field Labels</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Basic Salary Label</label>
            <input
              v-model="settings.basic_salary_label"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Overtime Label</label>
            <input
              v-model="settings.overtime_label"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Allowances Label</label>
            <input
              v-model="settings.allowances_label"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Gross Pay Label</label>
            <input
              v-model="settings.gross_pay_label"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">PAYE Tax Label</label>
            <input
              v-model="settings.paye_tax_label"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">NSSF Label</label>
            <input
              v-model="settings.nssf_label"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">SHIF Label</label>
            <input
              v-model="settings.shif_label"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Housing Levy Label</label>
            <input
              v-model="settings.housing_levy_label"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Other Deductions Label</label>
            <input
              v-model="settings.other_deductions_label"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Total Deductions Label</label>
            <input
              v-model="settings.total_deductions_label"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Net Pay Label</label>
            <input
              v-model="settings.net_pay_label"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            />
          </div>
        </div>
      </div>

      <!-- Colors -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Colors</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
            <div class="flex items-center space-x-3">
              <input
                v-model="settings.primary_color"
                type="color"
                class="w-16 h-10 rounded cursor-pointer"
              />
              <input
                v-model="settings.primary_color"
                type="text"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
            <div class="flex items-center space-x-3">
              <input
                v-model="settings.secondary_color"
                type="color"
                class="w-16 h-10 rounded cursor-pointer"
              />
              <input
                v-model="settings.secondary_color"
                type="text"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Accent Color</label>
            <div class="flex items-center space-x-3">
              <input
                v-model="settings.accent_color"
                type="color"
                class="w-16 h-10 rounded cursor-pointer"
              />
              <input
                v-model="settings.accent_color"
                type="text"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Footer Information</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Footer Text</label>
            <textarea
              v-model="settings.footer_text"
              rows="2"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            ></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Footer Contact Information</label>
            <textarea
              v-model="settings.footer_contact"
              rows="2"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            ></textarea>
          </div>
        </div>
      </div>

      <!-- Layout -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Layout Options</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Paper Size</label>
            <select
              v-model="settings.paper_size"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            >
              <option value="A4">A4</option>
              <option value="Letter">Letter</option>
              <option value="Legal">Legal</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Margin</label>
            <select
              v-model="settings.margin"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
            >
              <option value="10mm">10mm</option>
              <option value="15mm">15mm</option>
              <option value="20mm">20mm</option>
              <option value="25mm">25mm</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Save Button -->
      <div class="flex justify-end">
        <button
          @click="saveSettings"
          :disabled="saving"
          class="px-8 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-semibold disabled:opacity-50"
        >
          {{ saving ? 'Saving...' : 'Save Settings' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getPayslipSettings, updatePayslipSettings, resetPayslipSettings } from '../api/payslipSettings'

const settings = ref({})
const saving = ref(false)

const loadSettings = async () => {
  try {
    const response = await getPayslipSettings()
    settings.value = response.data
  } catch (err) {
    console.error('Failed to load payslip settings')
  }
}

const saveSettings = async () => {
  saving.value = true
  try {
    await updatePayslipSettings(settings.value)
    alert('Settings saved successfully!')
  } catch (err) {
    console.error('Failed to save settings')
    alert('Failed to save settings')
  } finally {
    saving.value = false
  }
}

const resetSettings = async () => {
  if (confirm('Reset all settings to default?')) {
    try {
      await resetPayslipSettings()
      await loadSettings()
      alert('Settings reset to default')
    } catch (err) {
      console.error('Failed to reset settings')
      alert('Failed to reset settings')
    }
  }
}

onMounted(() => {
  loadSettings()
})
</script>
