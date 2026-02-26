<header class="bg-gradient-to-r from-orange-600 via-orange-300 to-white
               border-b border-gray-200
               px-4 sm:px-6 lg:px-8
               py-3 sm:py-4
               shadow-md sticky top-0 z-50">
  <div class="flex items-center justify-between gap-4">

    <!-- Logo and Title -->
    <div class="flex items-center gap-3 sm:gap-6">
      <div class="flex-shrink-0">
        <img src="{{asset('logo.png')}}" alt="Logo" class="w-10 h-10 sm:w-14 sm:h-14 lg:w-16 lg:h-16 object-contain rounded-lg shadow-md">
      </div>
      <div>
        <h1 class="text-lg sm:text-2xl lg:text-3xl font-semibold text-gray-900 leading-tight">Supplier Evaluation Management</h1>
        <div class="hidden sm:block text-xs sm:text-sm text-white mt-1">Manage and evaluate suppliers with ease</div>
      </div>
    </div>

    <!-- Centered Links Section -->
    <div class="hidden md:flex justify-center gap-8 lg:gap-12 flex-grow">
    @if(auth()->user()->role !== 'end_user')
        <a
           class="text-gray-900 hover:text-blue-600 text-base font-medium flex items-center space-x-2 transition-all duration-300"
           onclick="openModal()" style="cursor: pointer;">
           <span>User Management</span>
        </a>
    @endif
      <a href="/evaluation" class="text-gray-900 hover:text-blue-600 text-base font-medium flex items-center space-x-2 transition-all duration-300">
        <span>Data Analytics</span>
      </a>
    </div>

    <!-- User Info and Notifications -->
    <div class="flex items-center gap-3 sm:gap-6">
        <!-- Mobile Menu Button -->
        <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
          <i class="ri-menu-line text-2xl text-gray-800"></i>
        </button>
      <!-- Notification -->
<!-- Notification Dropdown -->
<div class="relative">
  <button id="notification-btn" class="relative w-9 h-9 sm:w-12 sm:h-12 flex items-center justify-center rounded-full hover:bg-gray-100 transition focus:outline-none">
    <i class="ri-notification-line text-gray-600 text-2xl"></i>
    <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border border-white"></span>
  </button>

  <!-- Dropdown -->
  <div id="dropdown-menu" class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg border border-gray-200 hidden z-50">
    <div class="max-h-64 overflow-y-auto">
      <ul>
        <li class="px-4 py-3 hover:bg-gray-100 cursor-pointer">
          <p class="text-sm font-medium text-gray-800">New comment on your post</p>
          <p class="text-xs text-gray-500">5 minutes ago</p>
        </li>
        <li class="px-4 py-3 hover:bg-gray-100 cursor-pointer">
          <p class="text-sm font-medium text-gray-800">Your order has been shipped</p>
          <p class="text-xs text-gray-500">30 minutes ago</p>
        </li>
        <li class="px-4 py-3 hover:bg-gray-100 cursor-pointer">
          <p class="text-sm font-medium text-gray-800">New follower: John Doe</p>
          <p class="text-xs text-gray-500">1 hour ago</p>
        </li>
        <li class="px-4 py-3 hover:bg-gray-100 cursor-pointer">
          <p class="text-sm font-medium text-gray-800">Reminder: Meeting at 3 PM</p>
          <p class="text-xs text-gray-500">2 hours ago</p>
        </li>
        <li class="px-4 py-3 hover:bg-gray-100 cursor-pointer">
          <p class="text-sm font-medium text-gray-800">Update available for your app</p>
          <p class="text-xs text-gray-500">3 hours ago</p>
        </li>
      </ul>
    </div>

    <!-- See All Notifications -->
    <div class="border-t border-gray-200">
      <a href="#" class="block text-center text-sm text-blue-600 hover:bg-gray-100 py-2 rounded-b-lg">
        See all notifications
      </a>
    </div>
  </div>
</div>

<!-- JavaScript -->
<script>
  const btn = document.getElementById('notification-btn');
  const menu = document.getElementById('dropdown-menu');

  btn.addEventListener('click', (e) => {
    e.stopPropagation(); // Prevent click from bubbling to document
    menu.classList.toggle('hidden');
  });

  // Close dropdown if clicked outside
  document.addEventListener('click', (e) => {
    if (!menu.contains(e.target) && !btn.contains(e.target)) {
      menu.classList.add('hidden');
    }
  });
</script>

      <!-- User Info -->
    <!-- User Info Dropdown -->
    <div class="relative inline-block text-left user-menu">
      <button class="flex items-center space-x-3 focus:outline-none user-toggle">
        <img src="https://readdy.ai/api/search-image?query=professional%20business%20person%20headshot%20portrait%20with%20clean%20background%20corporate%20style&width=40&height=40&seq=user-avatar&orientation=squarish"
             alt="User"
             class="w-9 h-9 sm:w-12 sm:h-12 rounded-full object-cover border-2 border-gray-300 shadow-lg">
        <div class="text-sm text-left">
          <div class="font-medium text-gray-900">{{ auth()->user()->name }}</div>
          <div class="text-gray-500">
            @auth
                @switch(auth()->user()->role)
                    @case('administrator')
                        Administrator
                        @break

                    @case('end_user')
                        End User
                        @break

                    @default
                        {{ auth()->user()->role }}
                @endswitch
            @endauth
        </div>
        </div>
        <!-- Dropdown arrow -->
        <svg class="w-4 h-4 ml-2 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
      </button>

      <!-- Dropdown menu -->
      <div class="user-dropdown hidden absolute right-0 mt-2
            w-48 sm:w-40
            bg-white border border-gray-200
            rounded-lg shadow-lg z-50">
        <a href="/profile"
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
           Profile
        </a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
                  class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            Logout
          </button>
        </form>
      </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const toggles = document.querySelectorAll(".user-toggle");

    toggles.forEach(toggle => {
        toggle.addEventListener("click", function (e) {
            e.stopPropagation(); // 🔥 Prevent immediate close

            const wrapper = this.closest(".user-menu");
            const dropdown = wrapper.querySelector(".user-dropdown");

            // Close other dropdowns
            document.querySelectorAll(".user-dropdown").forEach(menu => {
                if (menu !== dropdown) {
                    menu.classList.add("hidden");
                }
            });

            dropdown.classList.toggle("hidden");
        });
    });

    // Close when clicking outside
    document.addEventListener("click", function (e) {
        document.querySelectorAll(".user-dropdown").forEach(menu => {
            if (!menu.closest(".user-menu").contains(e.target)) {
                menu.classList.add("hidden");
            }
        });
    });

});
</script>
     </div>
   </div>
   <!-- Mobile Menu -->
<div id="mobileMenu"
     class="hidden md:hidden flex flex-col gap-4 px-4 pb-4 bg-white shadow-md">

    @if(auth()->user()->role !== 'end_user')
    <a onclick="openModal()"
       class="text-gray-800 hover:text-blue-600 text-base font-medium">
       User Management
    </a>
    @endif

    <a href="/evaluation"
       class="text-gray-800 hover:text-blue-600 text-base font-medium">
       Data Analytics
    </a>

</div>
<script>
document.getElementById("mobileMenuBtn").addEventListener("click", function() {
    const menu = document.getElementById("mobileMenu");
    menu.classList.toggle("hidden");
});
</script>
</header>
