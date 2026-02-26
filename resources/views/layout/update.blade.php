<!-- New Evaluation Modal -->
<div id="updateEvaluationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-5xl w-full max-h-screen overflow-y-auto border border-gray-100">
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
                <div class="grid grid-cols-2 gap-6 mb-6">
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">NAME OF SUPPLIER:</label>
                    <input id="update_supplier_name" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Purchase Order / Contract No.:</label>
                    <input id="update_po_no" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Date of Evaluation:</label>
                    <input id="update_date_evaluation" type="date" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Covered Period:</label>
                    <input id="update_covered_period" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                </div>
<div class="mb-6">
  <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">
    Evaluated by (Office Name):
  </label>

  @if(auth()->user()->isAdmin())
    <!-- Admin: Dropdown with all departments -->
    <select id="new_office_name" name="office_name"
            class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base
                   focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
      <option value="">Select Office</option>
      @foreach($departments as $department)
        <option value="{{ $department }}">{{ $department }}</option>
      @endforeach
    </select>
  @else
    <!-- Non-admin: readonly input pre-filled with their department -->
    <input id="new_office_name" type="text"
           class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base
                  focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800"
           value="{{ auth()->user()->department ?? '' }}"
           readonly>
  @endif
</div>

                <div class="border-2 border-gray-300 rounded-xl mb-8 overflow-hidden shadow-sm">
                  <table class="w-full text-sm">
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
                                <div class="space-y-1 text-xs">
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
                                <div class="space-y-1 text-xs">
                                  <label class="flex items-start">
                                    <input id="new_quality_1_option_4" type="radio" name="quality_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
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
                                <div class="space-y-1 text-xs">
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
                                <div class="space-y-1 text-xs">
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
                      <div class="flex items-center justify-center space-x-4">
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
        <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-200">
          <h4 class="text-lg font-bold text-gray-800 mb-6 pb-3 border-b border-gray-300">
            Digital Authorization
          </h4>

          <div>
            <h5 class="font-semibold text-gray-800 mb-4 flex items-center">
              <div class="w-6 h-6 flex items-center justify-center mr-2 bg-primary text-white rounded-full">
                <i class="ri-user-line text-sm"></i>
              </div>
              Prepared by:
            </h5>

            <!-- Input section for Add Modal -->
            <div id="update_preparedBySection">
              <input id="update_full_name" type="text" placeholder="Enter full name" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 text-sm mb-3 focus:outline-none focus:border-primary">
              <input id="update_designation" type="text" placeholder="Enter designation" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 text-sm mb-4 focus:outline-none focus:border-primary">
              <button id="update_captureEvaluatorBtn" class="bg-gradient-to-r from-primary to-blue-600 text-white px-4 py-3 rounded-lg hover:from-blue-600 hover:to-blue-700">Capture Face for Authorization</button>
            </div>

            <!-- Captured section for Add Modal -->
            <div id="update_evaluatorCaptured" class="hidden">
              <div class="text-sm text-gray-700 mb-2">
                <strong>Prepared by:</strong> <span id="update_evaluatorName"></span><br>
                <strong>Designation:</strong> <span id="update_evaluatorDesignation"></span>
              </div>

              <div class="text-xs text-gray-500 mb-3">
                Authorized using Human Computer Authentication.
              </div>

              <img id="update_evaluatorImage"
                   src=""
                   alt="Evaluator"
                   class="w-24 h-24 rounded-lg object-cover border border-gray-300">
            </div>
          </div>

          <div class="flex justify-end space-x-4 mt-8">
            <button id="cancelUpdateEvaluationModalBtn" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-50">Cancel</button>
            <button id="submitUpdateEvaluationBtn" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-600">Submit Evaluation</button>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Camera Modal for Face Capture -->
<div id="update_cameraModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[9999]">
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
      <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-900">Face Capture Authorization</h3>
          <button id="update_closeCameraModal" class="text-gray-400 hover:text-gray-600">
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ri-close-line"></i>
            </div>
          </button>
        </div>
      </div>
      <div class="p-6">
        <div id="update_cameraPreview" class="mb-4">
          <video id="update_cameraVideo" class="w-full h-64 rounded-lg object-cover hidden"></video>
          <canvas id="update_captureCanvas" class="hidden"></canvas>
          <div id="update_cameraPlaceholder" class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
            <div class="text-center">
              <div class="w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <i class="ri-camera-line text-4xl text-gray-400"></i>
              </div>
              <p class="text-gray-500">Camera preview will appear here</p>
            </div>
          </div>
        </div>
        <div id="update_capturedImagePreview" class="hidden mb-4">
          <img id="update_capturedImage" src="" alt="Captured" class="w-full h-64 object-cover rounded-lg">
          <div class="mt-3 text-center">
            <button id="update_retakeBtn" class="text-primary hover:text-blue-700 text-sm">
              <div class="w-4 h-4 flex items-center justify-center mr-1 inline-block">
                <i class="ri-refresh-line"></i>
              </div>
              Retake
            </button>
          </div>
        </div>
        <div class="flex justify-center space-x-4">
          <button id="update_captureBtn" class="bg-primary text-white px-6 py-2 !rounded-button hover:bg-blue-600 whitespace-nowrap">
            <div class="w-4 h-4 flex items-center justify-center mr-2 inline-block">
              <i class="ri-camera-line"></i>
            </div>
            Capture
          </button>
          <button id="update_confirmCaptureBtn" class="bg-green-600 text-white px-6 py-2 !rounded-button hover:bg-green-700 whitespace-nowrap hidden">
            <div class="w-4 h-4 flex items-center justify-center mr-2 inline-block">
              <i class="ri-check-line"></i>
            </div>
            Confirm
          </button>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {

  const updateCaptureEvaluatorBtn = document.getElementById('update_captureEvaluatorBtn');
  const updateCameraModal = document.getElementById('update_cameraModal');
  const updateCloseCameraModal = document.getElementById('update_closeCameraModal');
  const updateCaptureBtn = document.getElementById('update_captureBtn');
  const updateConfirmCaptureBtn = document.getElementById('update_confirmCaptureBtn');
  const updateRetakeBtn = document.getElementById('update_retakeBtn');

  const updateVideo = document.getElementById('update_cameraVideo');
  const updateCanvas = document.getElementById('update_captureCanvas');
  const updateCapturedImage = document.getElementById('update_capturedImage');

  const updateCameraPlaceholder = document.getElementById('update_cameraPlaceholder');
  const updateCapturedPreview = document.getElementById('update_capturedImagePreview');

  const updateEvaluatorNameInput = document.getElementById('update_full_name');
  const updateEvaluatorDesignationInput = document.getElementById('update_designation');

  const updateEvaluatorCaptured = document.getElementById('update_evaluatorCaptured');
  const updateEvaluatorName = document.getElementById('update_evaluatorName');
  const updateEvaluatorDesignation = document.getElementById('update_evaluatorDesignation');
  const updateEvaluatorImage = document.getElementById('update_evaluatorImage');
  const updatePreparedBySection = document.getElementById('update_preparedBySection');

  let stream = null;

  // OPEN CAMERA
  updateCaptureEvaluatorBtn.addEventListener('click', async function() {
    if (!updateEvaluatorNameInput.value || !updateEvaluatorDesignationInput.value) {
      alert("Please enter full name and designation first.");
      return;
    }
    updateCameraModal.classList.remove('hidden');
    try {
      stream = await navigator.mediaDevices.getUserMedia({ video: true });
      updateVideo.srcObject = stream;
      updateVideo.onloadedmetadata = () => {
        updateVideo.play();
        updateVideo.classList.remove('hidden');
        updateCameraPlaceholder.classList.add('hidden');
      };
    } catch (error) {
      alert("Camera access denied or not available.");
      console.error(error);
    }
  });

  // CAPTURE IMAGE
  updateCaptureBtn.addEventListener('click', function() {
    updateCanvas.width = updateVideo.videoWidth;
    updateCanvas.height = updateVideo.videoHeight;
    const ctx = updateCanvas.getContext('2d');
    ctx.drawImage(updateVideo, 0, 0, updateCanvas.width, updateCanvas.height);
    updateCapturedImage.src = updateCanvas.toDataURL("image/png");
    updateVideo.classList.add('hidden');
    updateCapturedPreview.classList.remove('hidden');
    updateCaptureBtn.classList.add('hidden');
    updateConfirmCaptureBtn.classList.remove('hidden');
  });

  // RETAKE IMAGE
  updateRetakeBtn.addEventListener('click', function() {
    updateCapturedPreview.classList.add('hidden');
    updateVideo.classList.remove('hidden');
    updateCaptureBtn.classList.remove('hidden');
    updateConfirmCaptureBtn.classList.add('hidden');
  });

  // CONFIRM CAPTURE
  updateConfirmCaptureBtn.addEventListener('click', function() {
    updateEvaluatorName.textContent = updateEvaluatorNameInput.value;
    updateEvaluatorDesignation.textContent = updateEvaluatorDesignationInput.value;
    updateEvaluatorImage.src = updateCapturedImage.src;
    updatePreparedBySection.classList.add('hidden');
    updateEvaluatorCaptured.classList.remove('hidden');
    stopCamera();
    updateCameraModal.classList.add('hidden');
  });

  // CLOSE MODAL
  updateCloseCameraModal.addEventListener('click', function() {
    stopCamera();
    updateCameraModal.classList.add('hidden');
  });

  updateCameraModal.addEventListener('click', function(e) {
    if (e.target === updateCameraModal) {
      stopCamera();
      updateCameraModal.classList.add('hidden');
    }
  });

  function stopCamera() {
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
      stream = null;
    }
    updateVideo.srcObject = null;
    updateVideo.classList.add('hidden');
    updateCapturedPreview.classList.add('hidden');
    updateCaptureBtn.classList.remove('hidden');
    updateConfirmCaptureBtn.classList.add('hidden');
    updateCameraPlaceholder.classList.remove('hidden');
  }

});
</script>






<script>
// Global variable to track current evaluation ID
let currentEditEvaluationId = null;

// ===============================
// FETCH & POPULATE UPDATE FORM
// ===============================
async function updateEvaluation(id) {
    try {
        const response = await fetch(`/showupdateevaluations/${id}`);
        if (!response.ok) throw new Error('Failed to fetch evaluation data');
        const data = await response.json();

        const evaluation = data.evaluation;
        const evaluator = data.evaluator;

        currentEditEvaluationId = id;

        // Basic fields
        ['update_supplier_name','update_po_no','update_date_evaluation','update_covered_period','update_office_name'].forEach(fieldId => {
            const el = document.getElementById(fieldId);
            if(el) el.value = evaluation[fieldId.replace('update_','')] ?? '';
        });

        // Reset and populate criteria radios & remarks
        document.querySelectorAll('#updateEvaluationModal input[type="radio"]').forEach(r=>r.checked=false);
        document.querySelectorAll('#updateEvaluationModal textarea').forEach(t=>t.value='');

        evaluation.criteria_scores.forEach(score => {
            const radio = document.querySelector(`#updateEvaluationModal input[name="${getCriteriaName(score.criteria_id)}_1"][value="${score.number_rating}"]`);
            if(radio) radio.checked = true;

            const remarksField = document.getElementById(`update_form_remarks_${getCriteriaName(score.criteria_id)}_1`);
            if(remarksField) remarksField.value = score.remarks ?? '';
        });

        // Initial calculation
        calculateUpdateRating();

        // Evaluator captured (for reference only)
        const capturedSection = document.getElementById('update_evaluatorCaptured');
        if(evaluator){
            capturedSection.classList.remove('hidden');
            document.getElementById('update_full_name').value = evaluator.full_name ?? '';
            document.getElementById('update_designation').value = evaluator.designation ?? '';
            document.getElementById('update_evaluatorImage').src = evaluator.image ?? '';
        } else {
            capturedSection.classList.add('hidden');
        }

        // Attach auto-calc listeners
        attachUpdateRatingListeners();

        // Show modal
        document.getElementById('updateEvaluationModal').classList.remove('hidden');

    } catch(error){
        console.error('Error loading evaluation for update:', error);
        Swal.fire('Oops!', 'Failed to load evaluation data.', 'error');
    }
}

// ===============================
// AUTO-CALCULATE SCORE & STATUS
// ===============================
function calculateUpdateRating() {
    const criteriaIds = [1,2,3,4];
    let scores = {};

    criteriaIds.forEach(id => {
        const radios = document.querySelectorAll(`#updateEvaluationModal input[name="${getCriteriaName(id)}_1"]`);
        let value = 0;
        radios.forEach(r => { if(r.checked) value = parseFloat(r.value); });
        scores[id] = value;
    });

    let total = (5*scores[1]) + (7.5*scores[2]) + (6.25*scores[3]) + (6.25*scores[4]);
    total = parseFloat(total.toFixed(2));

    const ratingEl = document.getElementById('update_currentRating');
    if(ratingEl) ratingEl.innerText = total;

    let statusText = 'Pending';
    if(Object.values(scores).every(v => v!==0)) statusText = total>=60 ? 'Approved':'Fail';
    const statusEl = document.getElementById('update_statusText');
    if(statusEl) statusEl.innerText = statusText;
}

// ===============================
// ATTACH LISTENERS FOR AUTO-CALC
// ===============================
function attachUpdateRatingListeners() {
    const radios = document.querySelectorAll('#updateEvaluationModal input[type="radio"]');
    radios.forEach(r => r.addEventListener('change', calculateUpdateRating));
    const textareas = document.querySelectorAll('#updateEvaluationModal textarea');
    textareas.forEach(t => t.addEventListener('input', calculateUpdateRating));
}

// ===============================
// SUBMIT END-USER UPDATE WITH CONFIRMATION & COPY LINK
// ===============================
async function submitUpdateEvaluation(id){
    try{
        const confirmResult = await Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update this evaluation?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'No, cancel'
        });
        if(!confirmResult.isConfirmed) return;

        const evaluatorImageEl = document.getElementById('update_evaluatorImage');
        const evaluatorImage = evaluatorImageEl && evaluatorImageEl.src ? evaluatorImageEl.src : null;

        const evaluationData = {
            supplier_name: safeValue('update_supplier_name'),
            po_no: safeValue('update_po_no'),
            date_evaluation: safeValue('update_date_evaluation', false),
            covered_period: safeValue('update_covered_period'),
            office_name: safeValue('update_office_name'),
            criteria_scores: [],
            evaluator: {
                full_name: safeValue('update_full_name'),
                designation: safeValue('update_designation'),
                ...(evaluatorImage ? { image: evaluatorImage } : {})
            }
        };

        [1,2,3,4].forEach(criteriaId=>{
            const namePrefix = getCriteriaName(criteriaId)+'_1';
            let selectedValue = null;
            document.querySelectorAll(`#updateEvaluationModal input[name="${namePrefix}"]`).forEach(r=>{
                if(r.checked) selectedValue = parseFloat(r.value);
            });
            const remarksField = document.getElementById(`update_form_remarks_${getCriteriaName(criteriaId)}_1`);
            evaluationData.criteria_scores.push({
                criteria_id: criteriaId,
                number_rating: selectedValue ?? 0,
                remarks: remarksField ? remarksField.value.trim() : ''
            });
        });

        Swal.fire({
            title: 'Updating Evaluation...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const response = await fetch(`/updateevaluations/${id}`,{
            method:'PUT',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(evaluationData)
        });

        const result = await response.json();
        if(!response.ok) throw new Error(result.message || 'Failed to update evaluation');

        document.getElementById('updateEvaluationModal').classList.add('hidden');
        if(typeof fetchEvaluations === 'function') fetchEvaluations();

        // ------------------------------
        // Build full absolute URL with https://yourdomain.com
        // ------------------------------
        const origin = window.location.origin; // e.g. https://yourdomain.com
        const reviewUrl = `${origin}/evaluation/head-review/${result.review_token}`;

        Swal.fire({
            icon: 'success',
            title: 'Updated!',
            html: `
                ${result.message || 'Evaluation successfully updated!'}<br><br>
                <strong>Head Review Link:</strong><br>
                <input type="text" id="copyEvalLink" class="swal2-input" value="${reviewUrl}" readonly>
                <button id="copyLinkBtn" class="swal2-confirm swal2-styled" style="margin-top:5px;">Copy Link</button>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            didOpen: () => {
                const copyBtn = document.getElementById('copyLinkBtn');
                copyBtn.addEventListener('click', () => {
                    const linkInput = document.getElementById('copyEvalLink');
                    linkInput.select();
                    linkInput.setSelectionRange(0, 99999);
                    document.execCommand('copy');
                    Swal.fire('Copied!', 'Head Review link copied to clipboard.', 'success');
                });
            }
        });

    }catch(error){
        console.error('Error updating evaluation:', error);
        Swal.fire('Error!', error.message || 'An error occurred while updating the evaluation.', 'error');
    }
}

// ===============================
// HELPER
// ===============================
function getCriteriaName(id){
    switch(id){
        case 1: return 'price';
        case 2: return 'quality';
        case 3: return 'customercare';
        case 4: return 'delivery';
        default: return '';
    }
}
// ===============================
// SAFE INPUT GETTER
// ===============================
function safeValue(id, trim = true) {
    const el = document.getElementById(id);
    if (!el) return '';
    return trim ? el.value.trim() : el.value;
}
// ===============================
// BUTTON EVENTS
// ===============================
const submitBtn = document.getElementById('submitUpdateEvaluationBtn');
if (submitBtn) {
    submitBtn.addEventListener('click', () => {
        if (currentEditEvaluationId) {
            submitUpdateEvaluation(currentEditEvaluationId);
        }
    });
}
document.getElementById('cancelUpdateEvaluationModalBtn').addEventListener('click', ()=>{
    document.getElementById('updateEvaluationModal').classList.add('hidden');
});
document.getElementById('closeUpdateEvaluationModalBtn').addEventListener('click', ()=>{
    document.getElementById('updateEvaluationModal').classList.add('hidden');
});
</script>
