<!-- New Evaluation Modal -->
<div id="viewEvaluationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-5xl w-full max-h-screen overflow-y-auto border border-gray-100">
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 rounded-t-xl">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-xl font-bold text-white">SUPPLIER'S EVALUATION FORM</h3>
            <p class="text-blue-100 text-sm mt-1">Performance Assessment & Rating System</p>
          </div>
          <button id="closeViewEvaluationModalBtn" class="text-white hover:text-gray-200 transition-colors">
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
        <div id="viewevaluationFormsContainer">
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
                    <input id="view_supplier_name" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Purchase Order / Contract No.:</label>
                    <input id="view_po_no" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Date of Evaluation:</label>
                    <input id="view_date_evaluation" type="date" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Covered Period:</label>
                    <input id="view_covered_period" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                </div>
                <div class="mb-6">
                  <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">
                    Evaluated by (Office Name):
                  </label>


                      <!-- Non-admin: readonly input pre-filled with their department -->
                      <input id="view_office_name" type="text"
                             class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base
                                    focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800" value="">

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
                                    <input id="view_price_1_option_4" type="radio" name="price_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Highly Reasonable <span class="bg-yellow-200 px-1 rounded">(20%)</span></strong><br>• Bid amount is reasonable based on the brand/services delivered.<br>• Pricing is consistent with current market rates (brand or market scooping / historical data)<br>• No competitive</span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_price_1_option_3" type="radio" name="price_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - Reasonable <span class="bg-yellow-200 px-1 rounded">(15%)</span></strong><br>• Bid amount generally aligns with brand/services delivered.<br>• Minor discrepancies in pricing but still within acceptable market range.<br>• No significant cost or overpricing based on brand/services delivered.</span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_price_1_option_2" type="radio" name="price_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - Moderately Reasonable <span class="bg-yellow-200 px-1 rounded">(10%)</span></strong><br>• Some mismatch between bid amount and brand/services delivered.<br>• The bid amount is notably higher than the prevailing market range based on the brand/services delivered.</span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_price_1_option_1" type="radio" name="price_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - Not Reasonable <span class="bg-yellow-200 px-1 rounded">(5%)</span></strong><br>• The bid amount is higher than the prevailing market price against the brand/services delivered.</span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="view_form_remarks_price_1" name="form_remarks_price_1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                          <tr class="border-b border-gray-400">
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">B. QUALITY / SERVICE LEVEL (30%)</div>
                                <div class="space-y-1 text-xs">
                                  <label class="flex items-start">
                                    <input id="view_quality_1_option_4" type="radio" name="quality_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Goods delivered according to specifications, and acceptable quality <span class="bg-yellow-200 px-1 rounded">(30%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_quality_1_option_3" type="radio" name="quality_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - Goods delivered in accordance with specifications, with minor damages, defects, or workmanship issues, which were immediately corrected without affecting functionality or project timeline. <span class="bg-yellow-200 px-1 rounded">(22.5%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_quality_1_option_2" type="radio" name="quality_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - Goods delivered in accordance with specifications and of fair to low quality <span class="bg-yellow-200 px-1 rounded">(15%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_quality_1_option_1" type="radio" name="quality_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - Goods delivered with recurring or significant damages, defects, or workmanship issues, affecting functionality and functionality <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></strong></span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="view_form_remarks_quality_1" name="form_remarks_quality_1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                          <tr class="border-b border-gray-400">
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">C. CUSTOMER CARE / AFTER SALES SERVICE (25%)</div>
                                <div class="space-y-1 text-xs">
                                  <label class="flex items-start">
                                    <input id="view_customercare_1_option_4" type="radio" name="customercare_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Accessible and easy to contact, responsive to inquiries / complaints, adaptable to certain needs of the end-user</strong> and has competent staff to handle end-user's concerns. <strong><span class="bg-yellow-200 px-1 rounded">(25%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_customercare_1_option_3" type="radio" name="customercare_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - If one (1) of the details given in item #4 is lacking <span class="bg-yellow-200 px-1 rounded">(18.75%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_customercare_1_option_2" type="radio" name="customercare_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - If any two (2) of the details given in item #4 is lacking <span class="bg-yellow-200 px-1 rounded">(12.5%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_customercare_1_option_1" type="radio" name="customercare_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - If any three (3) of the details given in item #4 is lacking <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></strong></span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="view_form_remarks_customercare_1" name="form_remarks_customercare_1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                          <tr>
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">D. DELIVERY FULFILLMENT (25%)</div>
                                <div class="space-y-1 text-xs">
                                  <label class="flex items-start">
                                    <input id="view_delivery_1_option_4" type="radio" name="delivery_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Goods / Services delivered on Time <span class="bg-yellow-200 px-1 rounded">(25%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_delivery_1_option_3" type="radio" name="delivery_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - Goods / Services delivered, One (1) to Five (5) days after the expiration of the delivery period <span class="bg-yellow-200 px-1 rounded">(18.75%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_delivery_1_option_2" type="radio" name="delivery_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - Goods / Services delivered, Six (6) to Ten (10) days after the expiration of the delivery period <span class="bg-yellow-200 px-1 rounded">(12.5%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_delivery_1_option_1" type="radio" name="delivery_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - Goods / Services delivered, eleven (11) or more days after the expiration of the delivery period <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></strong></span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="view_form_remarks_delivery_1" name="form_remarks_delivery_1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
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
                          <span id="view_currentRating">0</span>%
                        </div>
                        <div class="text-sm opacity-90 mt-1">Overall Average Score</div>
                      </div>
                      <div class="flex items-center justify-center space-x-4">
                        <div class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                          <div class="text-xs opacity-90">Passing Rate</div>
                          <div class="font-bold">60%</div>
                        </div>
                        <div id="view_ratingStatus" class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                          <div class="text-xs opacity-90">Status</div>
                          <div class="font-bold" id="view_statusText">Pending</div>
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

    <div id="view_preparedBySection">
      <div class="text-sm text-gray-700 mb-2">
        <strong>Prepared by:</strong> <span id="view_preparedByName">-</span><br>
        <strong>Designation:</strong> <span id="view_preparedByDesignation">-</span>
      </div>
      <div class="text-xs text-gray-500 mb-3">
        Already submitted by End User
      </div>

      <img id="view_preparedByImage"
           src=""
           alt="End User Signature"
           class="w-24 h-24 rounded-lg object-cover border border-gray-300">
    </div>
  </div>

  <!-- RIGHT PANEL: HEAD (Fillable / Camera Capture) -->
  <div class="bg-white rounded-xl p-4 border border-gray-200">
    <h4 class="text-lg font-bold text-gray-800 mb-6 pb-3 border-b border-gray-300">
      Head Authorization
    </h4>

    <div id="view_headSection" class="mt-4">
      <div class="text-sm text-gray-700 mb-2">
        <strong>Prepared by:</strong> <span id="view_headName">-</span><br>
        <strong>Designation:</strong> <span id="view_headDesignation">-</span>
      </div>

      <div class="text-xs text-gray-500 mb-3">
        Already submitted by Office Head
      </div>

      <img id="view_headImage"
           src=""
           alt="Evaluator"
           class="w-24 h-24 rounded-lg object-cover border border-gray-300">
    </div>

    <div class="flex justify-end space-x-4 mt-8">
      <button id="cancelViewEvaluationModalBtn" class="w-full sm:w-auto
               bg-primary text-white
               px-6 py-3 rounded-lg
               hover:bg-blue-600">Close</button>
    </div>
  </div>
</div>
<div class="w-full text-center my-6 px-4">
  <p class="text-gray-700 text-sm md:text-base">
    This Supplier Evaluation is authenticated and authorized through computer-generated facial recognition technology, which serves as an official signature in lieu of a handwritten signature.
  </p>
</div>

      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('viewEvaluationModal');

    document.getElementById('closeViewEvaluationModalBtn')
        .addEventListener('click', function () {
            modal.classList.add('hidden');
        });

    document.getElementById('cancelViewEvaluationModalBtn')
        .addEventListener('click', function () {
            modal.classList.add('hidden');
        });

});
</script>

<script>

// Helper to match criteria ID to input name prefix
function getCriteriaName(id) {
    switch (id) {
        case 1: return 'price';
        case 2: return 'quality';
        case 3: return 'customercare';
        case 4: return 'delivery';
        default: return '';
    }
}

async function viewEvaluation(id) {
    try {
        const response = await fetch(`/evaluations/${id}`);
        const data = await response.json();

        const evaluation = data.evaluation;

        // ===============================
        // BASIC INFORMATION
        // ===============================
        const basicFields = [
            'view_supplier_name',
            'view_po_no',
            'view_date_evaluation',
            'view_covered_period',
            'view_office_name'
        ];

        basicFields.forEach(fid => {
            const el = document.getElementById(fid);
            if (el) {
                el.value = evaluation[fid.replace('view_', '')] ?? '';
                el.disabled = true;
            }
        });

        // ===============================
        // RESET RADIO BUTTONS & TEXTAREAS
        // ===============================
        const radios = document.querySelectorAll('#viewEvaluationModal input[type="radio"]');
        radios.forEach(r => { r.checked = false; r.disabled = true; });

        const textareas = document.querySelectorAll('#viewEvaluationModal textarea');
        textareas.forEach(t => { t.value = ''; t.readOnly = true; });

        // ===============================
        // POPULATE CRITERIA SCORES
        // ===============================
        let scores = {1:0, 2:0, 3:0, 4:0};

        if (evaluation.criteria_scores) {
            evaluation.criteria_scores.forEach(score => {
                scores[score.criteria_id] = score.number_rating ?? 0;

                // radio buttons
                const radio = document.querySelector(
                    `#viewEvaluationModal input[name="${getCriteriaName(score.criteria_id)}_1"][value="${score.number_rating}"]`
                );
                if (radio) radio.checked = true;

                // remarks
                const remarksField = document.getElementById(
                    `view_form_remarks_${getCriteriaName(score.criteria_id)}_1`
                );
                if (remarksField) remarksField.value = score.remarks ?? '';
            });
        }

        // ===============================
        // CALCULATE OVERALL RATING
        // ===============================
        const total = parseFloat(
            ((5*scores[1]) + (7.5*scores[2]) + (6.25*scores[3]) + (6.25*scores[4])).toFixed(2)
        );

        const ratingEl = document.getElementById('view_currentRating');
        if (ratingEl) ratingEl.innerText = total;

        const statusText = total >= 60 ? 'Approved' : 'Fail!';
        const statusEl = document.getElementById('view_statusText');
        if (statusEl) statusEl.innerText = statusText;

        // ===============================
        // DIGITAL APPROVALS
        // ===============================
        if (evaluation.digital_approvals && evaluation.digital_approvals.length > 0) {

            const preparedBy = evaluation.digital_approvals.find(a => a.role === 'Prepared By');
            const head = evaluation.digital_approvals.find(a => a.role === 'Head');

            // End User
            if (preparedBy) {
                const nameEl = document.getElementById('view_preparedByName');
                if (nameEl) nameEl.innerText = preparedBy.full_name ?? '-';

                const desigEl = document.getElementById('view_preparedByDesignation');
                if (desigEl) desigEl.innerText = preparedBy.designation ?? '-';

                const imgEl = document.getElementById('view_preparedByImage');
                if (imgEl) imgEl.src = preparedBy.image ?? '';

                const sectionEl = document.getElementById('view_preparedBySection');
                if (sectionEl) sectionEl.classList.remove('hidden');
            }

            // Head
            if (head) {
                const nameEl = document.getElementById('view_headName');
                if (nameEl) nameEl.innerText = head.full_name ?? '-';

                const desigEl = document.getElementById('view_headDesignation');
                if (desigEl) desigEl.innerText = head.designation ?? '-';

                const imgEl = document.getElementById('view_headImage');
                if (imgEl) imgEl.src = head.image ?? '';

                const sectionEl = document.getElementById('view_headSection');
                if (sectionEl) sectionEl.classList.remove('hidden');
            }

        } else {
            document.getElementById('view_preparedBySection')?.classList.add('hidden');
            document.getElementById('view_headSection')?.classList.add('hidden');
        }

        // ===============================
        // DISABLE SELECTS
        // ===============================
        const selects = document.querySelectorAll('#viewEvaluationModal select');
        selects.forEach(s => s.disabled = true);


        // ===============================
        // SHOW MODAL
        // ===============================
        const modal = document.getElementById('viewEvaluationModal');
        if (modal) modal.classList.remove('hidden');

    } catch (error) {
        console.error('Error loading evaluation:', error);
        alert('Failed to load evaluation.');
    }
}
</script>
