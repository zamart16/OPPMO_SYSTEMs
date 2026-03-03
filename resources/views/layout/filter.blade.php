            <div class="flex flex-col lg:flex-row
            lg:items-end
            gap-4 mb-6">

              <!-- Search -->
              <div class="relative w-full lg:w-auto">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <div class="w-4 h-4 flex items-center justify-center">
                    <i class="ri-search-line text-gray-400"></i>
                  </div>
                </div>
                <input
                    id="searchInput"
                    type="text"
                    placeholder="Search evaluations..."
                     class="w-full lg:w-[400px]
                    pl-10 pr-4 py-2
                    border border-gray-300 rounded-lg
                    focus:ring-2 focus:ring-primary focus:border-transparent
                    text-sm">
              </div>

              <!-- status Filter -->
              <select id="departmentFilter" class="w-full sm:w-auto
               border border-gray-300
               rounded-lg px-3 py-2 text-sm pr-8">
                <option value="">All Status</option>
                <option value="For Head Review">For Head Review</option>
                <option value="PENDING">PENDING</option>
              </select>

              <!-- Start & End Date Container -->
              <div class="w-full lg:w-auto
            border border-gray-300
            rounded-lg p-4
            bg-white shadow-sm">
                <!-- Dates Container -->
                <div class="flex flex-col sm:flex-row sm:space-x-4 gap-4">
                  <!-- Start Date -->
                  <div class="flex flex-col text-sm text-gray-700 flex-1">
                    <label for="startDateFilter" class="mb-1 font-medium">Start Date</label>
                    <input id="startDateFilter" type="date" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition" aria-label="Start date for evaluation filter">
                  </div>

                  <!-- End Date -->
                  <div class="flex flex-col text-sm text-gray-700 flex-1">
                    <label for="endDateFilter" class="mb-1 font-medium">End Date</label>
                    <input id="endDateFilter" type="date" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition" aria-label="End date for evaluation filter">
                  </div>
                </div>
              </div>




              <!-- Calculation -->
              <div class="hidden flex items-center justify-between text-sm text-gray-600 mb-4">
                <div>
                  Show
                  <select id="entriesPerPage" class="border border-gray-300 rounded px-2 py-1 mx-1 pr-6">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                  </select>
                  entries
                </div>
              </div>
            </div>
