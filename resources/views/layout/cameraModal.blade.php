<div id="update_cameraModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
  <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative z-[9999]">
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-gray-900">Face Capture Authorization</h3>
      <button id="update_closeCameraModal" class="text-gray-400 hover:text-gray-600">
        <div class="w-6 h-6 flex items-center justify-center">
          <i class="ri-close-line"></i>
        </div>
      </button>
    </div>
    <div class="p-6">
      <!-- Camera Preview -->
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

      <!-- Captured Image Preview -->
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

      <!-- Camera Action Buttons -->
      <div class="flex justify-center space-x-4">
        <button id="update_captureBtn" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-600 whitespace-nowrap">
          <div class="w-4 h-4 flex items-center justify-center mr-2 inline-block">
            <i class="ri-camera-line"></i>
          </div>
          Capture
        </button>
        <button id="update_confirmCaptureBtn" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 whitespace-nowrap hidden">
          <div class="w-4 h-4 flex items-center justify-center mr-2 inline-block">
            <i class="ri-check-line"></i>
          </div>
          Confirm
        </button>
      </div>
    </div>
  </div>
</div>
