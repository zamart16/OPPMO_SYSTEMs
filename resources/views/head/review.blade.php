@include('layout.header')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
{{-- <style>
    /* Force desktop layout on mobile if ?desktop=1 */
body.desktop-mode {
    width: 1024px;
    margin: 0 auto;
}
</style> --}}

@include('layout.style')

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-white z-50 flex items-center justify-center overflow-hidden">
  <!-- Full screen smoke layer -->
  <div class="global-smoke"></div>

  <!-- Center logo -->
  <img src="/logo.png" alt="Logo" class="relative z-10 w-24 h-24 logo-animate" />
</div>

<body class="overflow-hidden">
<!-- New Evaluation Modal -->
<div id="updateEvaluationModal" class="fixed inset-0 bg-black bg-opacity-50">
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-xl shadow-2xl
            w-full max-w-5xl
            h-[90vh]
            overflow-y-auto
            border border-gray-100">
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 rounded-t-xl">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-xl font-bold text-white">SUPPLIER'S EVALUATION FORM</h3>
            <p class="text-blue-100 text-sm mt-1">Performance Assessment & Rating System</p>
          </div>
          <button id="closeUpdateEvaluationModalBtn" class="text-white hover:text-gray-200 transition-colors">
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ri-close-line text-xl"></i>
            </div>
          </button>
        </div>
      </div>
      <div class="p-8">
        <div class="mb-8">
        </div>
        <div class="mb-8">
          <div class="bg-blue-50 rounded-xl p-6 border-l-4 border-primary">
            <h4 class="text-base font-bold text-primary mb-3 flex items-center">
              <div class="w-5 h-5 flex items-center justify-center mr-2">
                <i class="ri-information-line"></i>
              </div>
              INSTRUCTIONS
            </h4>
            <div class="space-y-2 text-sm text-gray-700">
              <p class="flex items-start">
                <span class="font-bold text-primary mr-2 mt-0.5">1.</span>
                <span>Check the box which corresponds to the supplier's performance based on the Purchase Order/Contract listed above.</span>
              </p>
              <p class="flex items-start">
                <span class="font-bold text-primary mr-2 mt-0.5">2.</span>
                <span>In the Remarks / Specific Comments Column, please provide the details especially incidents/description of the delivery in case it fell beyond what was expected. You may use additional sheet, if necessary.</span>
              </p>
              <p class="flex items-start">
                <span class="font-bold text-primary mr-2 mt-0.5">3.</span>
                <span>When multiple POs are added, each evaluation will be calculated separately and combined for the overall rating.</span>
              </p>
            </div>
          </div>
        </div>
        <div id="UpdateevaluationFormsContainer">
          <div class="evaluation-form-item mb-8" data-form-id="1">
            <div class="bg-white border-2 border-primary rounded-xl shadow-lg">
              <div class="bg-gradient-to-r from-primary to-blue-600 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                  <h4 class="text-lg font-bold text-white flex items-center">
                    <div class="w-5 h-5 flex items-center justify-center mr-2">
                      <i class="ri-file-text-line"></i>
                    </div>
                    EVALUATION FORM
                  </h4>
                  <div class="flex items-center space-x-2">
                    <button class="collapse-toggle text-white hover:text-gray-200 transition-colors">
                      <div class="w-5 h-5 flex items-center justify-center">
                        <i class="ri-subtract-line"></i>
                      </div>
                    </button>
                    <button class="remove-po-btn text-white hover:text-red-200 transition-colors hidden">
                      <div class="w-5 h-5 flex items-center justify-center">
                        <i class="ri-close-line"></i>
                      </div>
                    </button>
                  </div>
                </div>
              </div>
              <div class="form-content p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6">
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">NAME OF SUPPLIER:</label>
                    <input id="update_supplier_name" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Purchase Order / Contract No.:</label>
                    <input id="update_po_no" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800" >
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Date of Evaluation:</label>
                    <input id="update_date_evaluation" type="date" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800" >
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Covered Period:</label>
                    <input id="update_covered_period" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800" >
                  </div>
                </div>
                <div class="mb-6">
                  <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">
                    Evaluated by (Office Name):
                  </label>


                      <!-- Non-admin: readonly input pre-filled with their department -->
                      <input id="update_office_name" type="text"
                             class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base
                                    focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">

                  </div>

                <div class="w-full overflow-x-auto rounded-xl border border-gray-300 shadow-sm mb-8">
                  <table class="min-w-[800px] w-full text-sm table-auto border-collapse">
                    <thead>
                      <tr class="bg-gradient-to-r from-gray-800 to-gray-700 border-b border-gray-400">
                        <th class="border-r border-gray-500 p-4 text-left font-bold text-white uppercase tracking-wide">EVALUATION CRITERIA</th>
                        <th class="p-4 text-left font-bold text-white uppercase tracking-wide">REMARKS / SPECIFIC COMMENTS</th>
                      </tr>
                    </thead>

                        <tbody>
                          <tr class="border-b border-gray-400">
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">A. PRICE (20%)</div>
                                <div class="space-y-2 text-sm md:text-xs">
                                  <label class="flex items-start">
                                    <input id="update_price_1_option_4" type="radio" name="price_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Highly Reasonable <span class="bg-yellow-200 px-1 rounded">(20%)</span></strong><br>• Bid amount is reasonable based on the brand/services delivered.<br>• Pricing is consistent with current market rates (brand or market scooping / historical data)<br>• No competitive</span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="update_price_1_option_3" type="radio" name="price_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - Reasonable <span class="bg-yellow-200 px-1 rounded">(15%)</span></strong><br>• Bid amount generally aligns with brand/services delivered.<br>• Minor discrepancies in pricing but still within acceptable market range.<br>• No significant cost or overpricing based on brand/services delivered.</span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="update_price_1_option_2" type="radio" name="price_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - Moderately Reasonable <span class="bg-yellow-200 px-1 rounded">(10%)</span></strong><br>• Some mismatch between bid amount and brand/services delivered.<br>• The bid amount is notably higher than the prevailing market range based on the brand/services delivered.</span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="update_price_1_option_1" type="radio" name="price_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - Not Reasonable <span class="bg-yellow-200 px-1 rounded">(5%)</span></strong><br>• The bid amount is higher than the prevailing market price against the brand/services delivered.</span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="update_form_remarks_price_1" name="form_remarks_price_1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                          <tr class="border-b border-gray-400">
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">B. QUALITY / SERVICE LEVEL (30%)</div>
                                <div class="space-y-2 text-sm md:text-xs">
                                  <label class="flex items-start">
                                    <input id="update_quality_1_option_4" type="radio" name="quality_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Goods delivered according to specifications, and acceptable quality <span class="bg-yellow-200 px-1 rounded">(30%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="update_quality_1_option_3" type="radio" name="quality_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - Goods delivered in accordance with specifications, with minor damages, defects, or workmanship issues, which were immediately corrected without affecting functionality or project timeline. <span class="bg-yellow-200 px-1 rounded">(22.5%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="update_quality_1_option_2" type="radio" name="quality_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - Goods delivered in accordance with specifications and of fair to low quality <span class="bg-yellow-200 px-1 rounded">(15%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="update_quality_1_option_1" type="radio" name="quality_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - Goods delivered with recurring or significant damages, defects, or workmanship issues, affecting functionality and functionality <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></strong></span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="update_form_remarks_quality_1" name="form_remarks_quality_1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                          <tr class="border-b border-gray-400">
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">C. CUSTOMER CARE / AFTER SALES SERVICE (25%)</div>
                                <div class="space-y-2 text-sm md:text-xs">
                                  <label class="flex items-start">
                                    <input id="update_customercare_1_option_4" type="radio" name="customercare_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Accessible and easy to contact, responsive to inquiries / complaints, adaptable to certain needs of the end-user</strong> and has competent staff to handle end-user's concerns. <strong><span class="bg-yellow-200 px-1 rounded">(25%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="update_customercare_1_option_3" type="radio" name="customercare_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - If one (1) of the details given in item #4 is lacking <span class="bg-yellow-200 px-1 rounded">(18.75%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="update_customercare_1_option_2" type="radio" name="customercare_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - If any two (2) of the details given in item #4 is lacking <span class="bg-yellow-200 px-1 rounded">(12.5%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="update_customercare_1_option_1" type="radio" name="customercare_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - If any three (3) of the details given in item #4 is lacking <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></strong></span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="update_form_remarks_customercare_1" name="form_remarks_customercare_1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                          <tr>
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">D. DELIVERY FULFILLMENT (25%)</div>
                                <div class="space-y-2 text-sm md:text-xs">
                                  <label class="flex items-start">
                                    <input id="update_delivery_1_option_4" type="radio" name="delivery_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Goods / Services delivered on Time <span class="bg-yellow-200 px-1 rounded">(25%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="update_delivery_1_option_3" type="radio" name="delivery_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - Goods / Services delivered, One (1) to Five (5) days after the expiration of the delivery period <span class="bg-yellow-200 px-1 rounded">(18.75%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="update_delivery_1_option_2" type="radio" name="delivery_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - Goods / Services delivered, Six (6) to Ten (10) days after the expiration of the delivery period <span class="bg-yellow-200 px-1 rounded">(12.5%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="update_delivery_1_option_1" type="radio" name="delivery_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - Goods / Services delivered, eleven (11) or more days after the expiration of the delivery period <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></strong></span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="update_form_remarks_delivery_1" name="form_remarks_delivery_1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                        </tbody>


                  </table>
                </div>
                <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl p-4 text-white mb-6">
                    <div class="text-center">
                      <h4 class="text-lg font-bold mb-4">OVERALL RATING CALCULATION</h4>
                      <div class="bg-white bg-opacity-20 rounded-lg p-4 mb-4">
                        <div class="text-3xl font-bold">
                          <span id="update_currentRating">0</span>%
                        </div>
                        <div class="text-sm opacity-90 mt-1">Overall Average Score</div>
                      </div>
                      <div class="flex flex-col sm:flex-row
            items-center justify-center
            space-y-3 sm:space-y-0 sm:space-x-4">
                        <div class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                          <div class="text-xs opacity-90">Passing Rate</div>
                          <div class="font-bold">60%</div>
                        </div>
                        <div id="update_ratingStatus" class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                          <div class="text-xs opacity-90">Status</div>
                          <div class="font-bold" id="update_statusText">Pending</div>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
<!-- Digital Authorization Section -->
<div class="bg-gray-50 rounded-xl p-4 md:p-6
            border-2 border-gray-200
            grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

  <!-- LEFT PANEL: END USER (Read-only / Already Filled) -->
  <div class="bg-white rounded-xl p-4 border border-gray-200">
    <h4 class="text-lg font-bold text-gray-800 mb-6 pb-3 border-b border-gray-300">
      End User
    </h4>

    <div>
      <div class="text-sm text-gray-700 mb-2">
        <strong>Prepared by:</strong> {{ $evaluation->digitalApprovals->where('role','Prepared By')->first()->full_name ?? '-' }}<br>
        <strong>Designation:</strong> {{ $evaluation->digitalApprovals->where('role','Prepared By')->first()->designation ?? '-' }}
      </div>
      <div class="text-xs text-gray-500 mb-3">
        Already submitted by End User
      </div>

      <img src="{{ $evaluation->digitalApprovals->where('role','Prepared By')->first()->image ?? '' }}"
           alt="End User Signature"
           class="w-24 h-24 rounded-lg object-cover border border-gray-300">
    </div>
  </div>

  <!-- RIGHT PANEL: HEAD (Fillable / Camera Capture) -->
  <div class="bg-white rounded-xl p-4 border border-gray-200">
    <h4 class="text-lg font-bold text-gray-800 mb-6 pb-3 border-b border-gray-300">
      Head Authorization
    </h4>

    <div id="headPreparedBySection">
      <input id="head_full_name" type="text" placeholder="Enter full name" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 text-sm mb-3 focus:outline-none focus:border-primary">
      <input id="head_designation" type="text" placeholder="Enter designation" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 text-sm mb-4 focus:outline-none focus:border-primary">
      <button id="head_captureEvaluatorBtn" class="bg-gradient-to-r from-primary to-blue-600 text-white px-4 py-3 rounded-lg hover:from-blue-600 hover:to-blue-700">
        Capture Face for Authorization
      </button>
    </div>

    <div id="head_evaluatorCaptured" class="hidden mt-4">
      <div class="text-sm text-gray-700 mb-2">
        <strong>Prepared by:</strong> <span id="head_evaluatorName"></span><br>
        <strong>Designation:</strong> <span id="head_evaluatorDesignation"></span>
      </div>

      <div class="text-xs text-gray-500 mb-3">
        Already submitted by Office Head
      </div>

      <img id="head_evaluatorImage"
           src=""
           alt="Evaluator"
           class="w-24 h-24 rounded-lg object-cover border border-gray-300">
    </div>

    <div class="flex justify-end space-x-4 mt-8">
      <button id="submitUpdateEvaluationBtn" class="w-full sm:w-auto
               bg-primary text-white
               px-6 py-3 rounded-lg
               hover:bg-blue-600">Submit Evaluation</button>
    </div>
  </div>
</div>
<div class="w-full text-center my-6 px-4">
  <p class="text-gray-700 text-sm md:text-base">
    This Supplier Evaluation is authenticated and authorized through computer-generated facial recognition technology, which serves as an official signature in lieu of a handwritten signature.
  </p>
</div>
<br>
<br>

<br>
<br>
<br>
<br>

      </div>
    </div>
  </div>
</div>

<!-- Camera Modal for Head Capture -->
<div id="cameraModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[9999]">
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
      <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">Face Capture Authorization</h3>
        <button id="closeCameraModal" class="text-gray-400 hover:text-gray-600">
          <div class="w-6 h-6 flex items-center justify-center">
            <i class="ri-close-line"></i>
          </div>
        </button>
      </div>
      <div class="p-6">
        <div id="cameraPreview" class="mb-4">
          <video id="cameraVideo" class="w-full h-64 rounded-lg object-cover hidden" autoplay playsinline></video>
          <canvas id="captureCanvas" class="hidden"></canvas>
          <div id="cameraPlaceholder" class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
            <div class="text-center">
              <div class="w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <i class="ri-camera-line text-4xl text-gray-400"></i>
              </div>
              <p class="text-gray-500">Camera preview will appear here</p>
            </div>
          </div>
        </div>
        <div id="capturedImagePreview" class="hidden mb-4">
          <img id="capturedImage" src="" alt="Captured" class="w-full h-64 object-cover rounded-lg">
          <div class="mt-3 text-center">
            <button id="retakeBtn" class="text-primary hover:text-blue-700 text-sm flex items-center justify-center mx-auto">
              <i class="ri-refresh-line mr-1"></i> Retake
            </button>
          </div>
        </div>
        <div class="flex justify-center space-x-4">
          <button id="captureBtn" class="bg-primary text-white px-6 py-2 !rounded-button hover:bg-blue-600 whitespace-nowrap flex items-center">
            <i class="ri-camera-line mr-2"></i> Capture
          </button>
          <button id="confirmCaptureBtn" class="bg-green-600 text-white px-6 py-2 !rounded-button hover:bg-green-700 whitespace-nowrap hidden flex items-center">
            <i class="ri-check-line mr-2"></i> Confirm
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const evaluatorCaptured = document.getElementById('head_evaluatorCaptured');
    const evaluatorName = document.getElementById('head_evaluatorName');
    const evaluatorDesignation = document.getElementById('head_evaluatorDesignation');
    const evaluatorImage = document.getElementById('head_evaluatorImage');
    const preparedBySection = document.getElementById('headPreparedBySection');
    const submitBtn = document.getElementById('submitUpdateEvaluationBtn'); // <-- Submit button

    // Get Head evaluator data from Blade (backend)
    const headEvaluatorData = @json($evaluation->digitalApprovals->where('role','Head')->first());

    if (headEvaluatorData) {
        // Fill captured section with saved data
        evaluatorName.textContent = headEvaluatorData.full_name ?? '';
        evaluatorDesignation.textContent = headEvaluatorData.designation ?? '';
        evaluatorImage.src = headEvaluatorData.image ?? '';

        // Show captured info and hide inputs
        evaluatorCaptured.classList.remove('hidden');
        preparedBySection.classList.add('hidden');

        // Hide submit button
        if (submitBtn) submitBtn.classList.add('hidden');
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Camera elements
    const captureEvaluatorBtn = document.getElementById('head_captureEvaluatorBtn');
    const cameraModal = document.getElementById('cameraModal');
    const closeCameraModal = document.getElementById('closeCameraModal');
    const captureBtn = document.getElementById('captureBtn');
    const confirmCaptureBtn = document.getElementById('confirmCaptureBtn');
    const retakeBtn = document.getElementById('retakeBtn');

    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('captureCanvas');
    const capturedImage = document.getElementById('capturedImage');

    const cameraPlaceholder = document.getElementById('cameraPlaceholder');
    const capturedPreview = document.getElementById('capturedImagePreview');

    const evaluatorNameInput = document.getElementById('head_full_name');
    const evaluatorDesignationInput = document.getElementById('head_designation');

    const evaluatorCaptured = document.getElementById('head_evaluatorCaptured');
    const evaluatorName = document.getElementById('head_evaluatorName');
    const evaluatorDesignation = document.getElementById('head_evaluatorDesignation');
    const evaluatorImage = document.getElementById('head_evaluatorImage');
    const preparedBySection = document.getElementById('headPreparedBySection');

    const submitBtn = document.getElementById('submitUpdateEvaluationBtn');
    let stream = null;

    // --------------------------
    // CAMERA LOGIC
    // --------------------------
    captureEvaluatorBtn.addEventListener('click', async function() {
        if (!evaluatorNameInput.value || !evaluatorDesignationInput.value) {
            alert("Please enter full name and designation first.");
            return;
        }

        cameraModal.classList.remove('hidden');

        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: "user" },
                audio: false
            });
            video.srcObject = stream;
            video.onloadedmetadata = () => {
                video.play();
                video.classList.remove('hidden');
                cameraPlaceholder.classList.add('hidden');
            };
        } catch (error) {
            alert("Camera access denied or not available. Please allow camera access.");
            console.error(error);
        }
    });

    captureBtn.addEventListener('click', function() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        capturedImage.src = canvas.toDataURL("image/png");

        video.classList.add('hidden');
        capturedPreview.classList.remove('hidden');
        captureBtn.classList.add('hidden');
        confirmCaptureBtn.classList.remove('hidden');
    });

    retakeBtn.addEventListener('click', function() {
        capturedPreview.classList.add('hidden');
        video.classList.remove('hidden');
        captureBtn.classList.remove('hidden');
        confirmCaptureBtn.classList.add('hidden');
    });

    confirmCaptureBtn.addEventListener('click', function() {
        evaluatorName.textContent = evaluatorNameInput.value;
        evaluatorDesignation.textContent = evaluatorDesignationInput.value;
        evaluatorImage.src = capturedImage.src;

        preparedBySection.classList.add('hidden');
        evaluatorCaptured.classList.remove('hidden');

        stopCamera();
        cameraModal.classList.add('hidden');
    });

    closeCameraModal.addEventListener('click', function() {
        stopCamera();
        cameraModal.classList.add('hidden');
    });

    cameraModal.addEventListener('click', function(e) {
        if (e.target === cameraModal) {
            stopCamera();
            cameraModal.classList.add('hidden');
        }
    });

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        video.srcObject = null;
        video.classList.add('hidden');
        capturedPreview.classList.add('hidden');
        captureBtn.classList.remove('hidden');
        confirmCaptureBtn.classList.add('hidden');
        cameraPlaceholder.classList.remove('hidden');
    }

    // --------------------------
    // SUBMIT EVALUATION
    // --------------------------
    submitBtn.addEventListener('click', async function() {
        const token = window.location.pathname.split('/').pop();

        // Collect criteria scores
        const criteriaKeys = ['price_1','quality_1','customercare_1','delivery_1'];
        let criteriaData = {};
        criteriaKeys.forEach(key => {
            const selected = document.querySelector(`input[name="${key}"]:checked`);
            const remarks = document.getElementById(`update_form_remarks_${key}`)?.value ?? '';
            if (selected) {
                criteriaData[key] = { value: parseFloat(selected.value), remarks };
            }
        });

        // Prepare Head evaluator data
        const headEvaluator = {
            full_name: evaluatorNameInput.value,
            designation: evaluatorDesignationInput.value,
            image: capturedImage.src
        };

        // Confirm submission
        if (!confirm("Are you sure you want to submit this evaluation?")) return;

        try {
            const res = await fetch(`/evaluation/update/${token}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    criteria: criteriaData,
                    evaluator: headEvaluator,
                    role: 'Head' // this tells backend to insert/update Head approval
                })
            });

            const data = await res.json();

            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert("Failed to submit evaluation: " + data.message);
            }
        } catch (err) {
            console.error(err);
            alert("An unexpected error occurred while submitting evaluation.");
        }
    });
});
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const token = window.location.pathname.split('/').pop();
    const multipliers = {
        price_1: 5,
        quality_1: 7.5,
        customercare_1: 6.25,
        delivery_1: 6.25
    };

    const previousValues = {};
    let warningConfirmed = false;

    // Show / hide loading overlay
    const loadingEl = document.getElementById('loadingModal');
    function showLoading() { loadingEl.classList.remove('hidden'); }
    function hideLoading() { loadingEl.classList.add('hidden'); }

    function calculateOverallRating() {
        let total = 0;
        Object.keys(multipliers).forEach(name => {
            const selected = document.querySelector(`input[name="${name}"]:checked`);
            if (selected) total += parseFloat(selected.value) * multipliers[name];
        });
        total = total.toFixed(2);

        document.getElementById('update_currentRating').innerText = total;

        const statusText = document.getElementById('update_statusText');
        const statusBox = document.getElementById('update_ratingStatus');

        if (total >= 60) {
            statusText.innerText = "PASSED";
            statusBox.classList.remove('bg-red-500');
            statusBox.classList.add('bg-green-500');
        } else {
            statusText.innerText = "FAILED";
            statusBox.classList.remove('bg-green-500');
            statusBox.classList.add('bg-red-500');
        }
    }

    // Fetch evaluation data
    showLoading(); // <-- Show spinner before fetch
    fetch(`/evaluation/review/${token}`)
        .then(res => res.json())
        .then(data => {
            // Populate main fields
            document.getElementById('update_supplier_name').value = data.supplier_name ?? '-';
            document.getElementById('update_po_no').value = data.po_no ?? '-';
            document.getElementById('update_date_evaluation').value = data.date_evaluation ?? '';
            document.getElementById('update_covered_period').value = data.covered_period ?? '-';
            document.getElementById('update_office_name').value = data.office_name ?? '-';

            // Populate criteria and remarks
            Object.keys(data.criteria).forEach(name => {
                const score = data.criteria[name];

                const radio = document.querySelector(`input[name="${name}"][value="${score.value}"]`);
                if (radio) radio.checked = true;

                previousValues[name] = score.value;

                const textarea = document.getElementById(`update_form_remarks_${name}`);
                if (textarea) textarea.value = score.remarks ?? '';
            });

            calculateOverallRating();
        })
        .catch(err => console.error('Error fetching evaluation data:', err))
        .finally(() => hideLoading()); // <-- Hide spinner after fetch

    // Handle radio changes with conditional SweetAlert
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function (event) {
            const name = event.target.name;
            const newValue = event.target.value;

            if (previousValues[name] == newValue) return;

            if (warningConfirmed) {
                previousValues[name] = newValue;
                calculateOverallRating();
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Re-rate Confirmation',
                text: 'You are about to re-rate the evaluation. Are you sure?',
                showCancelButton: true,
                confirmButtonText: 'Yes, I confirm',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    warningConfirmed = true;
                    previousValues[name] = newValue;
                    calculateOverallRating();
                } else {
                    const prevRadio = document.querySelector(`input[name="${name}"][value="${previousValues[name]}"]`);
                    if (prevRadio) prevRadio.checked = true;
                }
            });
        });
    });

});
</script>



<script>
document.getElementById('submitUpdateEvaluationBtn').addEventListener('click', async function() {
    const token = window.location.pathname.split('/').pop();

    // Collect main fields
    const payload = {
        supplier_name: document.getElementById('update_supplier_name')?.value || '',
        po_no: document.getElementById('update_po_no')?.value || '',
        date_evaluation: document.getElementById('update_date_evaluation')?.value || '',
        covered_period: document.getElementById('update_covered_period')?.value || '',
        office_name: document.getElementById('update_office_name')?.value || '',
        criteria: {},
        evaluator: {}
    };

    // Collect criteria scores and remarks
    ['price_1','quality_1','customercare_1','delivery_1'].forEach(name => {
        const selected = document.querySelector(`input[name="${name}"]:checked`);
        const remarks = document.getElementById(`update_form_remarks_${name}`)?.value || '';
        if (selected) payload.criteria[name] = {
            value: parseFloat(selected.value),
            remarks: remarks
        };
    });

    // Collect evaluator info (Head)
    const evaluatorName = document.getElementById('head_full_name')?.value || '';
    const evaluatorDesignation = document.getElementById('head_designation')?.value || '';
    const evaluatorImage = document.getElementById('head_evaluatorImage')?.src || '';

    if (evaluatorName && evaluatorDesignation && evaluatorImage) {
        payload.evaluator = {
            full_name: evaluatorName,
            designation: evaluatorDesignation,
            image: evaluatorImage
        };
    }

    // Confirm submission
    if (!confirm("Are you sure you want to submit this evaluation?")) return;

    try {
        const res = await fetch(`/evaluation/update/${token}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        });

        const data = await res.json();

        if (data.success) {
            alert(data.message);
            // Optionally redirect or reload
            window.location.reload();
        } else {
            alert('Failed to submit evaluation: ' + data.message);
        }
    } catch (err) {
        console.error(err);
        alert('Error submitting evaluation.');
    }
});
</script>

</body>
