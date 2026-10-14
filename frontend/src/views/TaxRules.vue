<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Tax Rules</h1>
      <button
        @click="showModal = true"
        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Add Tax Rule
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div
        v-for="taxRule in taxRules"
        :key="taxRule.id"
        class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition"
      >
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="text-xl font-bold text-gray-800">{{ taxRule.name }}</h3>
            <p class="text-sm text-gray-500">{{ taxRule.country_code }} - {{ taxRule.currency }}</p>
          </div>
          <span
            :class="[
              'px-2 py-1 text-xs font-semibold rounded-full',
              taxRule.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
            ]"
          >
            {{ taxRule.is_active ? 'Active' : 'Inactive' }}
          </span>
        </div>

        <div class="space-y-3 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-600">PAYE Bands:</span>
            <span class="font-medium">{{ taxRule.paye_bands?.length || 0 }} bands</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Personal Relief:</span>
            <span class="font-medium">{{ taxRule.personal_relief }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">NSSF Rate:</span>
            <span class="font-medium">{{ (taxRule.nssf_rate * 100).toFixed(2) }}%</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">NSSF Max:</span>
            <span class="font-medium">{{ taxRule.nssf_max }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">SHIF Rate:</span>
            <span class="font-medium">{{ (taxRule.shif_rate * 100).toFixed(2) }}%</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">SHIF Min:</span>
            <span class="font-medium">{{ taxRule.shif_min }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Housing Levy:</span>
            <span class="font-medium">{{ (taxRule.housing_levy_rate * 100).toFixed(2) }}%</span>
          </div>
        </div>

        <div class="mt-4 pt-4 border-t flex justify-end space-x-2">
          <button @click="editTaxRule(taxRule)" class="text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
          </button>
          <button @click="deleteTaxRule(taxRule)" class="text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden transform transition-all my-8">
          <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <h2 class="text-2xl font-bold text-white">{{ editingTaxRule ? 'Edit Tax Rule' : 'Add Tax Rule' }}</h2>
          </div>
          <form @submit.prevent="saveTaxRule" class="p-6 space-y-5">
            <div class="grid grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Rule Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  placeholder="e.g., Kenya PAYE"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Country Code</label>
                <input
                  v-model="form.country_code"
                  type="text"
                  required
                  maxlength="2"
                  placeholder="KE"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400 uppercase"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Currency</label>
                <input
                  v-model="form.currency"
                  type="text"
                  required
                  maxlength="3"
                  placeholder="KES"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400 uppercase"
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">PAYE Bands (JSON Format)</label>
              <textarea
                v-model="payeBandsJson"
                rows="4"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm transition-all hover:border-blue-400 resize-none"
                placeholder='[{"limit": 24000, "rate": 0.10}, {"limit": 8333, "rate": 0.25}, {"limit": null, "rate": 0.35}]'
              ></textarea>
              <p class="text-xs text-gray-500 mt-1">Format: [{"limit": amount, "rate": 0.10}, ...] - Use null for final band limit</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Personal Relief</label>
                <input
                  v-model="form.personal_relief"
                  type="number"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">NSSF Rate (%)</label>
                <input
                  v-model="form.nssf_rate"
                  type="number"
                  step="0.01"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">NSSF Tier 1 Limit</label>
                <input
                  v-model="form.nssf_tier1_limit"
                  type="number"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">NSSF Tier 2 Limit</label>
                <input
                  v-model="form.nssf_tier2_limit"
                  type="number"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">NSSF Max</label>
                <input
                  v-model="form.nssf_max"
                  type="number"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">SHIF Rate (%)</label>
                <input
                  v-model="form.shif_rate"
                  type="number"
                  step="0.0001"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">SHIF Min</label>
                <input
                  v-model="form.shif_min"
                  type="number"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Housing Levy Rate (%)</label>
                <input
                  v-model="form.housing_levy_rate"
                  type="number"
                  step="0.001"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Insurance Relief Rate (%)</label>
                <input
                  v-model="form.insurance_relief_rate"
                  type="number"
                  step="0.01"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Insurance Relief Cap</label>
                <input
                  v-model="form.insurance_relief_cap"
                  type="number"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pension Cap</label>
                <input
                  v-model="form.pension_cap"
                  type="number"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mortgage Cap</label>
                <input
                  v-model="form.mortgage_cap"
                  type="number"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-blue-400"
                />
              </div>
            </div>

            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
              <input
                v-model="form.is_active"
                type="checkbox"
                id="is_active"
                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
              />
              <label for="is_active" class="ml-3 text-sm font-semibold text-gray-700">Active Rule</label>
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
                Save Tax Rule
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
import { ref, onMounted, computed } from 'vue'
import { getTaxRules, createTaxRule, updateTaxRule, deleteTaxRule as deleteTaxRuleApi } from '../api/taxRules'

const taxRules = ref([])
const showModal = ref(false)
const editingTaxRule = ref(null)
const payeBandsJson = ref('')

const form = ref({
  name: '',
  country_code: '',
  currency: '',
  paye_bands: [],
  personal_relief: 0,
  insurance_relief_rate: 0,
  insurance_relief_cap: 0,
  pension_cap: 0,
  mortgage_cap: 0,
  nssf_rate: 0,
  nssf_tier1_limit: 0,
  nssf_tier2_limit: 0,
  nssf_max: 0,
  shif_rate: 0,
  shif_min: 0,
  housing_levy_rate: 0,
  is_active: true
})

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
  editingTaxRule.value = null
  payeBandsJson.value = ''
  form.value = {
    name: '',
    country_code: '',
    currency: '',
    paye_bands: [],
    personal_relief: 0,
    insurance_relief_rate: 0,
    insurance_relief_cap: 0,
    pension_cap: 0,
    mortgage_cap: 0,
    nssf_rate: 0,
    nssf_tier1_limit: 0,
    nssf_tier2_limit: 0,
    nssf_max: 0,
    shif_rate: 0,
    shif_min: 0,
    housing_levy_rate: 0,
    is_active: true
  }
}

const editTaxRule = (taxRule) => {
  editingTaxRule.value = taxRule
  form.value = { ...taxRule }
  payeBandsJson.value = JSON.stringify(taxRule.paye_bands, null, 2)
  showModal.value = true
}

const saveTaxRule = async () => {
  try {
    form.value.paye_bands = JSON.parse(payeBandsJson.value)
    if (editingTaxRule.value) {
      await updateTaxRule(editingTaxRule.value.id, form.value)
    } else {
      await createTaxRule(form.value)
    }
    closeModal()
    loadTaxRules()
  } catch (err) {
    alert('Invalid JSON format for PAYE bands')
  }
}

const deleteTaxRule = async (taxRule) => {
  if (confirm(`Delete ${taxRule.name}?`)) {
    try {
      await deleteTaxRuleApi(taxRule.id)
      loadTaxRules()
    } catch (err) {
      console.error('Failed to delete tax rule')
    }
  }
}

onMounted(() => {
  loadTaxRules()
})
</script>
