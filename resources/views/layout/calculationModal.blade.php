<div id="calculationModal"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-opacity duration-500">

  <div id="calculationModalContent"
       class="relative w-11/12 max-w-5xl rounded-3xl shadow-2xl
              bg-white/80 backdrop-blur-xl border border-white/30
              transform scale-90 opacity-0 transition-all duration-500 ease-out overflow-hidden">

    <!-- Gradient Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">
      <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold tracking-wide">
          Overall Evaluation Result
        </h2>

        <!-- Score Circle -->
        <div class="relative">
          <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center border-4 border-white/30">
            <span id="calcScore"
                  class="text-xl font-bold text-white"></span>%
          </div>
        </div>
      </div>
    </div>

    <!-- Body -->
    <div class="p-8">

      <!-- Info Cards -->
      <div class="grid md:grid-cols-3 gap-6 mb-8">

        <div class="bg-white shadow-md rounded-xl p-4 border">
          <p class="text-xs text-gray-500 uppercase tracking-wide">Supplier</p>
          <p id="calcSupplier" class="text-lg font-semibold text-gray-800"></p>
        </div>

        <div class="bg-white shadow-md rounded-xl p-4 border">
          <p class="text-xs text-gray-500 uppercase tracking-wide">Purchase Orders</p>
          <p id="calcPO" class="text-lg font-semibold text-gray-800 break-words"></p>
        </div>

        <div class="bg-white shadow-md rounded-xl p-4 border">
          <p class="text-xs text-gray-500 uppercase tracking-wide">Department</p>
          <p id="calcDept" class="text-lg font-semibold text-gray-800"></p>
        </div>

      </div>

      <!-- Selected Count -->
      <div class="mb-6 text-center">
        <p class="text-gray-600 text-lg">
          Selected Evaluations:
          <span id="calcCount" class="font-bold text-indigo-600"></span>
        </p>
      </div>

      <!-- Progress Bar -->
      <div class="w-full bg-gray-200 rounded-full h-4 mb-8 overflow-hidden">
        <div id="scoreProgress"
             class="h-4 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-700"
             style="width:0%">
        </div>
      </div>

      <!-- Individual Evaluation Scores -->
      <div>
        <h3 class="text-lg font-semibold mb-4 text-gray-700">
          Individual Evaluation Scores
        </h3>

        <div id="evaluationScoreList"
             class="space-y-3 max-h-64 overflow-y-auto pr-2">
          <!-- JS will insert each evaluation here -->
        </div>
      </div>

      <!-- Footer -->
      <div class="mt-10 text-center">
        <button id="closeCalcModal"
                class="px-8 py-3 bg-gradient-to-r from-gray-700 to-gray-900
                       text-white rounded-xl shadow-lg hover:scale-105
                       hover:shadow-xl transition-all duration-300">
          Close
        </button>
      </div>

    </div>

  </div>
</div>
