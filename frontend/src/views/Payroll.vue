<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Payroll</h1>
      <button
        @click="generatePayroll"
        class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Generate Payroll
      </button>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
      <div class="p-4 border-b flex space-x-4">
        <input
          v-model="selectedPeriod"
          type="month"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
        />
        <button
          @click="loadPayrolls"
          class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
        >
          Filter
        </button>
      </div>
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gross Pay</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deductions</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Pay</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="payroll in payrolls" :key="payroll.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 text-gray-900">{{ payroll.employee?.name }}</td>
            <td class="px-6 py-4 text-gray-600">{{ payroll.period }}</td>
            <td class="px-6 py-4 text-gray-600">{{ getCurrencySymbol(payroll.employee?.tax_rule?.currency || 'KES') }}{{ Number(payroll.gross_pay || 0).toFixed(2) }}</td>
            <td class="px-6 py-4 text-gray-600">{{ getCurrencySymbol(payroll.employee?.tax_rule?.currency || 'KES') }}{{ (Number(payroll.deductions || 0) + Number(payroll.paye_tax || 0) + Number(payroll.nssf || 0) + Number(payroll.shif || 0) + Number(payroll.housing_levy || 0)).toFixed(2) }}</td>
            <td class="px-6 py-4 font-semibold text-gray-900">{{ getCurrencySymbol(payroll.employee?.tax_rule?.currency || 'KES') }}{{ Number(payroll.net_pay || 0).toFixed(2) }}</td>
            <td class="px-6 py-4">
              <span
                :class="[
                  'px-2 py-1 text-xs font-semibold rounded-full',
                  payroll.status === 'processed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                ]"
              >
                {{ payroll.status }}
              </span>
            </td>
            <td class="px-6 py-4">
              <button
                @click="viewPayslip(payroll)"
                class="text-blue-600 hover:text-blue-800 font-medium text-sm"
              >
                View Payslip
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Payslip Modal -->
    <Transition name="modal">
      <div v-if="showPayslipModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-7xl max-h-[95vh] overflow-hidden transform transition-all flex flex-col lg:flex-row">
          <!-- Sidebar -->
          <div class="w-full lg:w-80 bg-gray-50 border-r overflow-y-auto">
            <div class="p-4 border-b bg-white sticky top-0 z-10">
              <h3 class="text-lg font-bold text-gray-800">Customize Payslip</h3>
              <p class="text-sm text-gray-500">Adjust layout and content</p>
            </div>

            <div class="p-4 space-y-6">
              <!-- Company Info -->
              <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Company Information</h4>
                <div class="space-y-3">
                  <div>
                    <label class="block text-xs text-gray-600 mb-1">Company Logo URL</label>
                    <input
                      v-model="payslipConfig.company.logoUrl"
                      type="text"
                      placeholder="https://example.com/logo.png"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500"
                    />
                    <p class="text-xs text-gray-400 mt-1">Leave empty to use default logo placeholder</p>
                  </div>
                  <div>
                    <label class="block text-xs text-gray-600 mb-1">Company Name</label>
                    <input
                      v-model="payslipConfig.company.name"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500"
                    />
                  </div>
                  <div>
                    <label class="block text-xs text-gray-600 mb-1">Description</label>
                    <input
                      v-model="payslipConfig.company.description"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500"
                    />
                  </div>
                  <div>
                    <label class="block text-xs text-gray-600 mb-1">Address</label>
                    <input
                      v-model="payslipConfig.company.address"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500"
                    />
                  </div>
                  <div>
                    <label class="block text-xs text-gray-600 mb-1">Email</label>
                    <input
                      v-model="payslipConfig.company.email"
                      type="email"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500"
                    />
                  </div>
                  <div>
                    <label class="block text-xs text-gray-600 mb-1">Phone</label>
                    <input
                      v-model="payslipConfig.company.phone"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500"
                    />
                  </div>
                  <div>
                    <label class="block text-xs text-gray-600 mb-1">Tax ID</label>
                    <input
                      v-model="payslipConfig.company.taxId"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500"
                    />
                  </div>
                </div>
              </div>

              <!-- Display Options -->
              <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Display Options</h4>
                <div class="space-y-2">
                  <label class="flex items-center space-x-2 cursor-pointer">
                    <input
                      v-model="payslipConfig.payslip.showYTD"
                      type="checkbox"
                      class="rounded text-purple-600 focus:ring-purple-500"
                    />
                    <span class="text-sm text-gray-700">Show YTD Totals</span>
                  </label>
                  <label class="flex items-center space-x-2 cursor-pointer">
                    <input
                      v-model="payslipConfig.payslip.showLeaveBalances"
                      type="checkbox"
                      class="rounded text-purple-600 focus:ring-purple-500"
                    />
                    <span class="text-sm text-gray-700">Show Leave Balances</span>
                  </label>
                  <label class="flex items-center space-x-2 cursor-pointer">
                    <input
                      v-model="payslipConfig.payslip.showTaxId"
                      type="checkbox"
                      class="rounded text-purple-600 focus:ring-purple-500"
                    />
                    <span class="text-sm text-gray-700">Show Tax ID</span>
                  </label>
                  <label class="flex items-center space-x-2 cursor-pointer">
                    <input
                      v-model="payslipConfig.payslip.showPaymentDate"
                      type="checkbox"
                      class="rounded text-purple-600 focus:ring-purple-500"
                    />
                    <span class="text-sm text-gray-700">Show Payment Date</span>
                  </label>
                </div>
              </div>

              <!-- Colors -->
              <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Colors</h4>
                <div class="space-y-3">
                  <div>
                    <label class="block text-xs text-gray-600 mb-1">Primary Color</label>
                    <input
                      v-model="payslipConfig.colors.primary"
                      type="color"
                      class="w-full h-10 rounded cursor-pointer"
                    />
                  </div>
                  <div>
                    <label class="block text-xs text-gray-600 mb-1">Secondary Color</label>
                    <input
                      v-model="payslipConfig.colors.secondary"
                      type="color"
                      class="w-full h-10 rounded cursor-pointer"
                    />
                  </div>
                  <div>
                    <label class="block text-xs text-gray-600 mb-1">Accent Color</label>
                    <input
                      v-model="payslipConfig.colors.accent"
                      type="color"
                      class="w-full h-10 rounded cursor-pointer"
                    />
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="space-y-2 pt-4 border-t">
                <button
                  @click="savePayslipConfig"
                  class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-semibold"
                >
                  Save Configuration
                </button>
                <button
                  @click="resetPayslipConfig"
                  class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm"
                >
                  Reset to Default
                </button>
              </div>
            </div>
          </div>
        </div>

          <!-- Main Content -->
          <div class="flex-1 flex flex-col">
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4 flex justify-between items-center">
              <h2 class="text-xl font-bold text-white">Payslip Preview</h2>
              <div class="flex space-x-2">
                <button
                  @click="toggleSidebar"
                  class="lg:hidden text-white hover:text-gray-200 transition"
                >
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                  </svg>
                </button>
                <button
                  @click="showPayslipModal = false"
                  class="text-white hover:text-gray-200 transition"
                >
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
              </div>
            </div>
            <div class="p-4 lg:p-8 overflow-y-auto flex-1" id="payslip-content">
            <!-- Payslip Header -->
            <div class="flex justify-between items-start mb-6 border-b-2 border-gray-200 pb-4">
              <div class="flex items-center space-x-4">
                <div v-if="payslipConfig.company.logoUrl" class="w-16 h-16 rounded-lg overflow-hidden">
                  <img :src="payslipConfig.company.logoUrl" alt="Company Logo" class="w-full h-full object-cover" />
                </div>
                <div v-else class="w-16 h-16 rounded-lg flex items-center justify-center" :style="{ background: `linear-gradient(135deg, ${payslipConfig.colors.secondary} 0%, ${payslipConfig.colors.accent} 100%)` }">
                  <span class="text-white font-bold text-xl">LOGO</span>
                </div>
                <div>
                  <h3 class="text-xl font-bold" :style="{ color: payslipConfig.colors.primary }">{{ payslipConfig.company.name }}</h3>
                  <p class="text-gray-600 text-sm">{{ payslipConfig.company.description }}</p>
                  <p class="text-sm text-gray-500">{{ payslipConfig.company.address }}</p>
                  <p class="text-xs text-gray-400">Email: {{ payslipConfig.company.email }} | Phone: {{ payslipConfig.company.phone }}</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Payslip Period</p>
                <p class="text-lg font-bold" :style="{ color: payslipConfig.colors.primary }">{{ selectedPayroll?.period }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide mt-2">Issue Date</p>
                <p class="text-sm text-gray-700">{{ new Date().toLocaleDateString() }}</p>
              </div>
            </div>

            <!-- Employee Info -->
            <div class="mb-6">
              <h4 class="text-sm font-bold mb-3 border-b pb-2" :style="{ color: payslipConfig.colors.primary }">Employee Information</h4>
              <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                <div>
                  <p class="text-xs text-gray-500 uppercase tracking-wide">Employee Name</p>
                  <p class="font-semibold text-gray-800 text-sm">{{ selectedPayroll?.employee?.name }}</p>
                </div>
                <div>
                  <p class="text-xs text-gray-500 uppercase tracking-wide">Employee Number</p>
                  <p class="font-semibold text-gray-800 text-sm">{{ selectedPayroll?.employee?.employee_number }}</p>
                </div>
                <div>
                  <p class="text-xs text-gray-500 uppercase tracking-wide">Department</p>
                  <p class="font-semibold text-gray-800 text-sm">{{ selectedPayroll?.employee?.department?.name }}</p>
                </div>
                <div>
                  <p class="text-xs text-gray-500 uppercase tracking-wide">Position</p>
                  <p class="font-semibold text-gray-800 text-sm">{{ selectedPayroll?.employee?.position }}</p>
                </div>
                <div v-if="payslipConfig.payslip.showTaxId">
                  <p class="text-xs text-gray-500 uppercase tracking-wide">Tax ID / PIN</p>
                  <p class="font-semibold text-gray-800 text-sm">A00{{ selectedPayroll?.employee?.id || '000000000' }}</p>
                </div>
                <div>
                  <p class="text-xs text-gray-500 uppercase tracking-wide">Tax Rule</p>
                  <p class="font-semibold text-gray-800 text-sm">{{ selectedPayroll?.employee?.tax_rule?.name || 'Standard' }}</p>
                </div>
              </div>
            </div>

            <!-- Earnings Section -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-gray-800 mb-3 border-b pb-2">Earnings (Gross Pay)</h4>
              <table class="w-full">
                <thead class="bg-green-50">
                  <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Description</th>
                    <th class="px-3 py-2 text-right text-xs font-semibold text-gray-700">Amount</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr>
                    <td class="px-3 py-2 text-gray-700 text-sm">Basic Salary</td>
                    <td class="px-3 py-2 text-right text-gray-800 text-sm font-semibold">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ Number(selectedPayroll?.basic_salary || 0).toFixed(2) }}</td>
                  </tr>
                  <tr v-if="Number(selectedPayroll?.overtime_pay || 0) > 0">
                    <td class="px-3 py-2 text-gray-700 text-sm">Overtime Pay</td>
                    <td class="px-3 py-2 text-right text-gray-800 text-sm font-semibold">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ Number(selectedPayroll?.overtime_pay || 0).toFixed(2) }}</td>
                  </tr>
                  <tr v-if="Number(selectedPayroll?.allowances || 0) > 0">
                    <td class="px-3 py-2 text-gray-700 text-sm">Allowances</td>
                    <td class="px-3 py-2 text-right text-gray-800 text-sm font-semibold">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ Number(selectedPayroll?.allowances || 0).toFixed(2) }}</td>
                  </tr>
                  <tr class="bg-green-100 font-semibold">
                    <td class="px-3 py-2 text-gray-800 text-sm">Gross Pay</td>
                    <td class="px-3 py-2 text-right text-gray-800 text-sm font-bold">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ Number(selectedPayroll?.gross_pay || 0).toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Deductions Section -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-gray-800 mb-3 border-b pb-2">Deductions</h4>
              <table class="w-full">
                <thead class="bg-red-50">
                  <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Description</th>
                    <th class="px-3 py-2 text-right text-xs font-semibold text-gray-700">Amount</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-if="Number(selectedPayroll?.paye_tax || 0) > 0">
                    <td class="px-3 py-2 text-gray-700 text-sm">PAYE Tax (Income Tax)</td>
                    <td class="px-3 py-2 text-right text-gray-800 text-sm font-semibold">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ Number(selectedPayroll?.paye_tax || 0).toFixed(2) }}</td>
                  </tr>
                  <tr v-if="Number(selectedPayroll?.nssf || 0) > 0">
                    <td class="px-3 py-2 text-gray-700 text-sm">NSSF Contribution (Social Security)</td>
                    <td class="px-3 py-2 text-right text-gray-800 text-sm font-semibold">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ Number(selectedPayroll?.nssf || 0).toFixed(2) }}</td>
                  </tr>
                  <tr v-if="Number(selectedPayroll?.shif || 0) > 0">
                    <td class="px-3 py-2 text-gray-700 text-sm">SHIF Contribution (Health Insurance)</td>
                    <td class="px-3 py-2 text-right text-gray-800 text-sm font-semibold">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ Number(selectedPayroll?.shif || 0).toFixed(2) }}</td>
                  </tr>
                  <tr v-if="Number(selectedPayroll?.housing_levy || 0) > 0">
                    <td class="px-3 py-2 text-gray-700 text-sm">Housing Levy</td>
                    <td class="px-3 py-2 text-right text-gray-800 text-sm font-semibold">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ Number(selectedPayroll?.housing_levy || 0).toFixed(2) }}</td>
                  </tr>
                  <tr v-if="Number(selectedPayroll?.deductions || 0) > 0">
                    <td class="px-3 py-2 text-gray-700 text-sm">Other Deductions (Loans/Advances)</td>
                    <td class="px-3 py-2 text-right text-gray-800 text-sm font-semibold">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ Number(selectedPayroll?.deductions || 0).toFixed(2) }}</td>
                  </tr>
                  <tr class="bg-red-100 font-semibold">
                    <td class="px-3 py-2 text-gray-800 text-sm">Total Deductions</td>
                    <td class="px-3 py-2 text-right text-gray-800 text-sm font-bold">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ (Number(selectedPayroll?.deductions || 0) + Number(selectedPayroll?.paye_tax || 0) + Number(selectedPayroll?.nssf || 0) + Number(selectedPayroll?.shif || 0) + Number(selectedPayroll?.housing_levy || 0)).toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Year-to-Date Totals -->
            <div v-if="payslipConfig.payslip.showYTD" class="mb-6">
              <h4 class="text-sm font-bold mb-3 border-b pb-2" :style="{ color: payslipConfig.colors.primary }">Year-to-Date (YTD) Totals</h4>
              <div class="grid grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg">
                <div class="text-center">
                  <p class="text-xs text-gray-500 uppercase tracking-wide">YTD Gross Pay</p>
                  <p class="font-bold text-gray-800 text-sm mt-1">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ (Number(selectedPayroll?.gross_pay || 0)).toFixed(2) }}</p>
                </div>
                <div class="text-center">
                  <p class="text-xs text-gray-500 uppercase tracking-wide">YTD Tax Paid</p>
                  <p class="font-bold text-gray-800 text-sm mt-1">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ (Number(selectedPayroll?.paye_tax || 0)).toFixed(2) }}</p>
                </div>
                <div class="text-center">
                  <p class="text-xs text-gray-500 uppercase tracking-wide">YTD Net Pay</p>
                  <p class="font-bold text-gray-800 text-sm mt-1">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ (Number(selectedPayroll?.net_pay || 0)).toFixed(2) }}</p>
                </div>
              </div>
            </div>

            <!-- Leave Balances -->
            <div v-if="payslipConfig.payslip.showLeaveBalances" class="mb-6">
              <h4 class="text-sm font-bold mb-3 border-b pb-2" :style="{ color: payslipConfig.colors.primary }">Leave Balances</h4>
              <div class="grid grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg">
                <div class="text-center">
                  <p class="text-xs text-gray-500 uppercase tracking-wide">Annual Leave</p>
                  <p class="font-bold text-gray-800 text-sm mt-1">21 Days</p>
                </div>
                <div class="text-center">
                  <p class="text-xs text-gray-500 uppercase tracking-wide">Sick Leave</p>
                  <p class="font-bold text-gray-800 text-sm mt-1">14 Days</p>
                </div>
                <div class="text-center">
                  <p class="text-xs text-gray-500 uppercase tracking-wide">Casual Leave</p>
                  <p class="font-bold text-gray-800 text-sm mt-1">7 Days</p>
                </div>
              </div>
            </div>

            <!-- Net Pay Summary -->
            <div class="rounded-lg p-5 text-white mb-6" :style="{ background: `linear-gradient(135deg, ${payslipConfig.colors.secondary} 0%, ${payslipConfig.colors.accent} 100%)` }">
              <div class="flex justify-between items-center">
                <div>
                  <p class="text-xs uppercase tracking-wide opacity-90">Net Pay (Take-Home)</p>
                  <p class="text-2xl font-bold mt-1">{{ getCurrencySymbol(selectedPayroll?.employee?.tax_rule?.currency || 'KES') }}{{ Number(selectedPayroll?.net_pay || 0).toFixed(2) }}</p>
                </div>
                <div class="text-right">
                  <p class="text-xs uppercase tracking-wide opacity-90">Payment Status</p>
                  <p class="text-lg font-semibold capitalize mt-1">{{ selectedPayroll?.status }}</p>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="border-t pt-4 text-center text-xs text-gray-500">
              <p class="font-semibold">This is a computer-generated payslip. No signature required.</p>
              <p class="mt-1">For any queries regarding this payslip, please contact the HR Department.</p>
              <p class="mt-1">{{ payslipConfig.company.name }} | Generated on {{ new Date().toLocaleString() }}</p>
            </div>
          </div>
          <div class="bg-gray-50 px-4 lg:px-6 py-4 flex justify-end space-x-3 border-t">
            <button
              @click="printPayslip"
              class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center text-sm font-semibold shadow-lg"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
              </svg>
              Print / Download Payslip
            </button>
            <button
              @click="showPayslipModal = false"
              class="px-4 lg:px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getPayrolls, generatePeriodPayroll } from '../api/payrolls'
import { getPayslipSettings, updatePayslipSettings } from '../api/payslipSettings'

const payrolls = ref([])
const selectedPeriod = ref(new Date().toISOString().slice(0, 7))
const showPayslipModal = ref(false)
const selectedPayroll = ref(null)
const showSidebar = ref(true)

const payslipConfig = ref({
  company: {
    name: 'SecureGuard HR',
    description: 'Security Company Management System',
    address: 'Nairobi, Kenya',
    email: 'hr@secureguard.co.ke',
    phone: '+254 700 000 000',
    taxId: 'PVT-001234567',
    logoUrl: ''
  },
  payslip: {
    showYTD: true,
    showLeaveBalances: true,
    showTaxId: true,
    showPaymentDate: true
  },
  colors: {
    primary: '#1e40af',
    secondary: '#7c3aed',
    accent: '#2563eb'
  }
})

const defaultConfig = JSON.parse(JSON.stringify(payslipConfig.value))

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

const loadPayrolls = async () => {
  try {
    const response = await getPayrolls({ period: selectedPeriod.value })
    payrolls.value = response.data
  } catch (err) {
    console.error('Failed to load payrolls')
  }
}

const generatePayroll = async () => {
  if (!confirm(`Generate payroll for ${selectedPeriod.value}?`)) return

  try {
    await generatePeriodPayroll({ period: selectedPeriod.value })
    loadPayrolls()
  } catch (err) {
    console.error('Failed to generate payroll')
  }
}

const viewPayslip = async (payroll) => {
  selectedPayroll.value = payroll
  showPayslipModal.value = true
  // Load payslip settings from backend
  await loadPayslipSettings()
}

const toggleSidebar = () => {
  showSidebar.value = !showSidebar.value
}

const loadPayslipSettings = async () => {
  try {
    const response = await getPayslipSettings()
    const backendSettings = response.data
    
    // Map backend settings to frontend config format
    payslipConfig.value = {
      company: {
        name: backendSettings.company_name,
        description: backendSettings.company_description,
        address: backendSettings.company_address,
        email: backendSettings.company_email,
        phone: backendSettings.company_phone,
        taxId: backendSettings.company_tax_id
      },
      payslip: {
        showYTD: backendSettings.show_ytd,
        showLeaveBalances: backendSettings.show_leave_balances,
        showTaxId: backendSettings.show_tax_id,
        showPaymentDate: backendSettings.show_payment_date
      },
      colors: {
        primary: backendSettings.primary_color,
        secondary: backendSettings.secondary_color,
        accent: backendSettings.accent_color
      }
    }
  } catch (err) {
    console.error('Failed to load payslip settings')
  }
}

const savePayslipConfig = async () => {
  try {
    // Map frontend config to backend format
    const backendSettings = {
      company_name: payslipConfig.value.company.name,
      company_description: payslipConfig.value.company.description,
      company_address: payslipConfig.value.company.address,
      company_email: payslipConfig.value.company.email,
      company_phone: payslipConfig.value.company.phone,
      company_tax_id: payslipConfig.value.company.taxId,
      show_ytd: payslipConfig.value.payslip.showYTD,
      show_leave_balances: payslipConfig.value.payslip.showLeaveBalances,
      show_tax_id: payslipConfig.value.payslip.showTaxId,
      show_payment_date: payslipConfig.value.payslip.showPaymentDate,
      primary_color: payslipConfig.value.colors.primary,
      secondary_color: payslipConfig.value.colors.secondary,
      accent_color: payslipConfig.value.colors.accent
    }
    
    await updatePayslipSettings(backendSettings)
    alert('Payslip configuration saved!')
  } catch (err) {
    console.error('Failed to save payslip settings')
    alert('Failed to save configuration')
  }
}

const resetPayslipConfig = async () => {
  if (confirm('Reset to default configuration?')) {
    try {
      const response = await fetch('http://localhost:8000/api/payslip-settings/reset', {
        method: 'POST'
      })
      if (response.ok) {
        await loadPayslipSettings()
        alert('Settings reset to default')
      }
    } catch (err) {
      console.error('Failed to reset settings')
      alert('Failed to reset settings')
    }
  }
}

const printPayslip = () => {
  const currency = getCurrencySymbol(selectedPayroll.value?.employee?.tax_rule?.currency || 'KES')
  const employee = selectedPayroll.value?.employee
  const payroll = selectedPayroll.value
  const config = payslipConfig.value

  const ytdGross = Number(payroll?.gross_pay || 0)
  const ytdTax = Number(payroll?.paye_tax || 0)
  const ytdNet = Number(payroll?.net_pay || 0)

  const leaveBalances = {
    annual: 21,
    sick: 14,
    casual: 7
  }

  const htmlContent = `
    <!DOCTYPE html>
    <html>
      <head>
        <title>Payslip - ${employee?.name}</title>
        <style>
          @page {
            size: A4;
            margin: 15mm;
          }
          body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
          }
          .payslip-container {
            max-width: 650px;
            margin: 0 auto;
            background: white;
          }
          .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid ${config.colors.primary};
            padding-bottom: 15px;
            margin-bottom: 20px;
          }
          .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
          }
          .logo-placeholder {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, ${config.colors.secondary} 0%, ${config.colors.accent} 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
          }
          .logo-image {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            overflow: hidden;
          }
          .logo-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
          }
          .company-info h1 {
            margin: 0;
            font-size: 22px;
            color: ${config.colors.primary};
            font-weight: bold;
          }
          .company-info p {
            margin: 3px 0;
            color: #666;
            font-size: 11px;
          }
          .payslip-info {
            text-align: right;
          }
          .payslip-info .label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
          }
          .payslip-info .value {
            font-size: 18px;
            font-weight: bold;
            color: ${config.colors.primary};
            margin-top: 3px;
          }
          .section-title {
            font-size: 14px;
            font-weight: bold;
            color: ${config.colors.primary};
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 6px;
            margin-bottom: 12px;
            margin-top: 20px;
          }
          .employee-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
          }
          .info-item .label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
          }
          .info-item .value {
            font-size: 12px;
            font-weight: 600;
            color: #1f2937;
            margin-top: 2px;
          }
          table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
          }
          th {
            background: #f3f4f6;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            color: #374151;
            border-bottom: 2px solid #d1d5db;
          }
          th:last-child {
            text-align: right;
          }
          td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
          }
          td:last-child {
            text-align: right;
            font-weight: 600;
          }
          tr.total-row {
            background: #f3f4f6;
            font-weight: bold;
          }
          tr.total-row td {
            font-size: 12px;
            font-weight: bold;
            color: #1f2937;
          }
          .earnings th {
            background: #dcfce7;
            border-bottom: 2px solid #86efac;
          }
          .deductions th {
            background: #fee2e2;
            border-bottom: 2px solid #fca5a5;
          }
          .net-pay-section {
            background: linear-gradient(135deg, ${config.colors.secondary} 0%, ${config.colors.accent} 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 25px 0;
          }
          .net-pay-section .label {
            font-size: 12px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
          }
          .net-pay-section .amount {
            font-size: 32px;
            font-weight: bold;
            margin-top: 4px;
          }
          .status-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
          }
          .ytd-section, .leave-section {
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
          }
          .ytd-grid, .leave-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
          }
          .ytd-item, .leave-item {
            text-align: center;
          }
          .ytd-item .label, .leave-item .label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
          }
          .ytd-item .value, .leave-item .value {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-top: 3px;
          }
          .footer {
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 25px;
            text-align: center;
            color: #666;
            font-size: 10px;
          }
          .footer p {
            margin: 4px 0;
          }
          @media print {
            body {
              print-color-adjust: exact;
              -webkit-print-color-adjust: exact;
            }
            .payslip-container {
              max-width: 100%;
            }
          }
        </style>
      </head>
      <body>
        <div class="payslip-container">
          <div class="header">
            <div class="logo-section">
              ${config.company.logoUrl ? `
              <div class="logo-image">
                <img src="${config.company.logoUrl}" alt="Company Logo" style="width: 70px; height: 70px; object-fit: contain;" />
              </div>
              ` : `
              <div class="logo-placeholder">LOGO</div>
              `}
              <div class="company-info">
                <h1>${config.company.name}</h1>
                <p>${config.company.description}</p>
                <p>${config.company.address}</p>
                <p>Email: ${config.company.email} | Phone: ${config.company.phone}</p>
                ${config.payslip.showTaxId ? `<p>Tax ID: ${config.company.taxId}</p>` : ''}
              </div>
            </div>
            <div class="payslip-info">
              <div class="label">Payslip Period</div>
              <div class="value">${payroll?.period}</div>
              <div class="label" style="margin-top: 12px;">Issue Date</div>
              <div class="value" style="font-size: 13px;">${new Date().toLocaleDateString()}</div>
              ${config.payslip.showPaymentDate ? `
              <div class="label" style="margin-top: 12px;">Payment Date</div>
              <div class="value" style="font-size: 13px;">${new Date().toLocaleDateString()}</div>
              ` : ''}
            </div>
          </div>

          <div class="section-title">Employee Information</div>
          <div class="employee-grid">
            <div class="info-item">
              <div class="label">Employee Name</div>
              <div class="value">${employee?.name}</div>
            </div>
            <div class="info-item">
              <div class="label">Employee Number</div>
              <div class="value">${employee?.employee_number}</div>
            </div>
            <div class="info-item">
              <div class="label">Department</div>
              <div class="value">${employee?.department?.name}</div>
            </div>
            <div class="info-item">
              <div class="label">Position</div>
              <div class="value">${employee?.position}</div>
            </div>
            ${config.payslip.showTaxId ? `
            <div class="info-item">
              <div class="label">Tax ID / PIN</div>
              <div class="value">A00${employee?.id || '000000000'}</div>
            </div>
            ` : ''}
            <div class="info-item">
              <div class="label">Tax Rule</div>
              <div class="value">${employee?.tax_rule?.name || 'Standard'}</div>
            </div>
          </div>

          <div class="section-title">Earnings (Gross Pay)</div>
          <table class="earnings">
            <thead>
              <tr>
                <th>Description</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Basic Salary</td>
                <td>${currency}${Number(payroll?.basic_salary || 0).toFixed(2)}</td>
              </tr>
              ${Number(payroll?.overtime_pay || 0) > 0 ? `
              <tr>
                <td>Overtime Pay</td>
                <td>${currency}${Number(payroll?.overtime_pay || 0).toFixed(2)}</td>
              </tr>
              ` : ''}
              ${Number(payroll?.allowances || 0) > 0 ? `
              <tr>
                <td>Allowances</td>
                <td>${currency}${Number(payroll?.allowances || 0).toFixed(2)}</td>
              </tr>
              ` : ''}
              <tr class="total-row">
                <td>Gross Pay</td>
                <td>${currency}${Number(payroll?.gross_pay || 0).toFixed(2)}</td>
              </tr>
            </tbody>
          </table>

          <div class="section-title">Deductions</div>
          <table class="deductions">
            <thead>
              <tr>
                <th>Description</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              ${Number(payroll?.paye_tax || 0) > 0 ? `
              <tr>
                <td>PAYE Tax (Income Tax)</td>
                <td>${currency}${Number(payroll?.paye_tax || 0).toFixed(2)}</td>
              </tr>
              ` : ''}
              ${Number(payroll?.nssf || 0) > 0 ? `
              <tr>
                <td>NSSF Contribution (Social Security)</td>
                <td>${currency}${Number(payroll?.nssf || 0).toFixed(2)}</td>
              </tr>
              ` : ''}
              ${Number(payroll?.shif || 0) > 0 ? `
              <tr>
                <td>SHIF Contribution (Health Insurance)</td>
                <td>${currency}${Number(payroll?.shif || 0).toFixed(2)}</td>
              </tr>
              ` : ''}
              ${Number(payroll?.housing_levy || 0) > 0 ? `
              <tr>
                <td>Housing Levy</td>
                <td>${currency}${Number(payroll?.housing_levy || 0).toFixed(2)}</td>
              </tr>
              ` : ''}
              ${Number(payroll?.deductions || 0) > 0 ? `
              <tr>
                <td>Other Deductions (Loans/Advances)</td>
                <td>${currency}${Number(payroll?.deductions || 0).toFixed(2)}</td>
              </tr>
              ` : ''}
              <tr class="total-row">
                <td>Total Deductions</td>
                <td>${currency}${(Number(payroll?.deductions || 0) + Number(payroll?.paye_tax || 0) + Number(payroll?.nssf || 0) + Number(payroll?.shif || 0) + Number(payroll?.housing_levy || 0)).toFixed(2)}</td>
              </tr>
            </tbody>
          </table>

          ${config.payslip.showYTD ? `
          <div class="section-title">Year-to-Date (YTD) Totals</div>
          <div class="ytd-section">
            <div class="ytd-grid">
              <div class="ytd-item">
                <div class="label">YTD Gross Pay</div>
                <div class="value">${currency}${ytdGross.toFixed(2)}</div>
              </div>
              <div class="ytd-item">
                <div class="label">YTD Tax Paid</div>
                <div class="value">${currency}${ytdTax.toFixed(2)}</div>
              </div>
              <div class="ytd-item">
                <div class="label">YTD Net Pay</div>
                <div class="value">${currency}${ytdNet.toFixed(2)}</div>
              </div>
            </div>
          </div>
          ` : ''}

          ${config.payslip.showLeaveBalances ? `
          <div class="section-title">Leave Balances</div>
          <div class="leave-section">
            <div class="leave-grid">
              <div class="leave-item">
                <div class="label">Annual Leave</div>
                <div class="value">${leaveBalances.annual} Days</div>
              </div>
              <div class="leave-item">
                <div class="label">Sick Leave</div>
                <div class="value">${leaveBalances.sick} Days</div>
              </div>
              <div class="leave-item">
                <div class="label">Casual Leave</div>
                <div class="value">${leaveBalances.casual} Days</div>
              </div>
            </div>
          </div>
          ` : ''}

          <div class="net-pay-section">
            <div>
              <div class="label">Net Pay (Take-Home)</div>
              <div class="amount">${currency}${Number(payroll?.net_pay || 0).toFixed(2)}</div>
            </div>
            <div class="status-badge">${payroll?.status}</div>
          </div>

          <div class="footer">
            <p><strong>This is a computer-generated payslip. No signature required.</strong></p>
            <p>For any queries regarding this payslip, please contact the HR Department.</p>
            <p>${config.company.name} | Generated on ${new Date().toLocaleString()}</p>
          </div>
        </div>
      </body>
    </html>
  `

  // Create a blob and download as HTML file
  const blob = new Blob([htmlContent], { type: 'text/html' })
  const url = URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = `payslip_${employee?.name}_${payroll?.period}.html`
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  URL.revokeObjectURL(url)

  // Also open in new window for printing
  const printWindow = window.open('', '_blank')
  if (printWindow) {
    printWindow.document.write(htmlContent)
    printWindow.document.close()
    printWindow.onload = function() {
      printWindow.print()
    }
  } else {
    alert('Please allow popups to print the payslip. The payslip has been downloaded as an HTML file.')
  }
}

onMounted(() => {
  loadPayrolls()
  loadPayslipSettings()
})
</script>

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
