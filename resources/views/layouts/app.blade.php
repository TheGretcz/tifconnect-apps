<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TIF Connect' }} - Telkom ISP Monitoring</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #sidebar {
            transition: transform 0.3s ease-in-out;
        }
        #main-content {
            transition: margin-left 0.3s ease-in-out;
        }
        @media (min-width: 640px) {
            .sidebar-hidden #sidebar {
                transform: translateX(-100%) !important;
            }
            .sidebar-hidden #main-content {
                margin-left: 0 !important;
            }
            .sidebar-hidden #header-toggle {
                margin-left: 0 !important;
            }
        }
        @media (max-width: 639px) {
            .sidebar-open #sidebar {
                transform: translateX(0) !important;
            }
            .sidebar-backdrop {
                position: fixed;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
                display: none;
            }
            .sidebar-open .sidebar-backdrop {
                display: block;
            }
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 font-sans">
    @include('components.chat-widget')


    {{-- Removed old fixed toggle --}}

    <aside id="sidebar"
        class="fixed top-0 left-0 z-50 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-gray-900 relative">
            {{-- Mobile Close Button --}}
            <button type="button" onclick="document.body.classList.remove('sidebar-open')"
                class="sm:hidden absolute top-4 right-4 text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="flex items-center ps-2.5 mb-6">
                <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L2 7l8 5 8-5-8-5zM2 13l8 5 8-5M2 10l8 5 8-5" />
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-bold text-white">TIF Connect</span>
                    <p class="text-xs text-gray-400">ISP Monitoring</p>
                </div>
            </div>

            {{-- User Info --}}
            <div class="p-3 mb-4 rounded-lg bg-gray-800 border border-gray-700">
                <div class="flex items-center">
                    <div
                        class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->username }}</p>
                        @if(auth()->user()->isAdmin())
                            <p class="text-xs text-gray-400">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ auth()->user()->isSuperAdmin() ? 'bg-purple-900 text-purple-300' : 'bg-red-900 text-red-300' }}">
                                    {{ auth()->user()->role }}
                                </span>
                            </p>
                        @else
                            <p class="text-xs text-gray-300 truncate max-w-[120px]">{{ auth()->user()->isp_name }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <ul class="space-y-1 font-medium">
                @if(auth()->user()->isAdmin())
                    {{-- Admin Menu --}}
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            <span class="ms-3">Dashboard</span>
                        </a>
                    </li>
                    </li>
                    <li>
                        <a href="{{ route('admin.coverage.index') }}"
                            class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.coverage.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ms-3">Check Coverage</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.area.index') }}"
                            class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.area.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ms-3">Data Area</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.purchase-orders.index') }}"
                            class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.purchase-orders.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ms-3">Purchase Order</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders.index') }}"
                            class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd"
                                    d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ms-3">Data Order</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reporting.index') }}"
                            class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reporting.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                            </svg>
                            <span class="ms-3">Reporting</span>
                        </a>
                    </li>
                @else
                    {{-- ISP Menu --}}
                    <li>
                        <a href="{{ route('isp.dashboard') }}"
                            class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('isp.dashboard') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            <span class="ms-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('isp.coverage.create') }}"
                            class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('isp.coverage.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ms-3">Add Request</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('isp.purchase-orders.index') }}"
                            class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('isp.purchase-orders.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ms-3">Purchase Order</span>
                        </a>
                    </li>
                @endif
            </ul>

            {{-- Profile Settings --}}
            <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-700">
                @if(auth()->user()->isAdmin())
                    <li>
                        <a href="{{ route('admin.isp.index') }}"
                            class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.isp.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z" />
                            </svg>
                            <span class="ms-3">Manage ISP</span>
                        </a>
                    </li>
                    @if(auth()->user()->isSuperAdmin())
                        <li>
                            <a href="{{ route('admin.users.index') }}"
                                class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                </svg>
                                <span class="ms-3">Manage User</span>
                            </a>
                        </li>
                    @endif
                @endif
                <li>
                    <a href="{{ route('profile.password') }}"
                        class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('profile.password') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ms-3">Change Password</span>
                    </a>
                </li>
            </ul>

            {{-- Logout --}}
            <div class="absolute bottom-4 left-3 right-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center p-3 rounded-lg text-gray-300 hover:bg-red-600/20 hover:text-red-400 transition-all duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ms-3">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div id="main-content" class="min-h-screen transition-all duration-300 sm:ml-64">
        {{-- Sticky Header --}}
        <nav class="sticky top-0 z-30 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 px-4 py-2.5">
            <div class="flex items-center">
                <button id="sidebar-toggle" type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Toggle sidebar</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <div class="ml-4 flex items-center lg:ml-6">
                    <span class="text-sm font-semibold text-gray-700 dark:text-white lg:text-base">{{ $title ?? 'TIF Connect' }}</span>
                </div>
            </div>
        </nav>
        <div class="p-4">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div id="alert-success"
                    class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 border border-green-200"
                    role="alert">
                    <svg class="shrink-0 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="ms-3 text-sm font-medium">{{ session('success') }}</span>
                    <button type="button"
                        class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8"
                        data-dismiss-target="#alert-success" aria-label="Close">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div id="alert-error"
                    class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
                    <svg class="shrink-0 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="ms-3 text-sm font-medium">{{ session('error') }}</span>
                    <button type="button"
                        class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8"
                        data-dismiss-target="#alert-error" aria-label="Close">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
            @endif
            @if($errors->any())
                <div class="flex items-start p-4 mb-4 text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
                    <svg class="shrink-0 w-4 h-4 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="ms-3 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>

        {{-- Static Footer --}}
        <footer class="mt-12 border-t border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 py-6">
                {{-- Social Media Icons --}}
                <div class="flex justify-center items-center space-x-5 mb-4">
                    {{-- Facebook --}}
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer"
                        class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500 hover:bg-blue-600 hover:text-white transition-all duration-300 hover:shadow-lg hover:shadow-blue-600/30 hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    {{-- Instagram --}}
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                        class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500 hover:bg-gradient-to-br hover:from-purple-600 hover:to-pink-500 hover:text-white transition-all duration-300 hover:shadow-lg hover:shadow-pink-500/30 hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                        </svg>
                    </a>
                    {{-- Telegram --}}
                    <a href="https://t.me" target="_blank" rel="noopener noreferrer"
                        class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500 hover:bg-sky-500 hover:text-white transition-all duration-300 hover:shadow-lg hover:shadow-sky-500/30 hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.479.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                        </svg>
                    </a>
                    {{-- WhatsApp --}}
                    <a href="https://wa.me" target="_blank" rel="noopener noreferrer"
                        class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500 hover:bg-green-500 hover:text-white transition-all duration-300 hover:shadow-lg hover:shadow-green-500/30 hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                    </a>
                </div>

                {{-- Copyright Text --}}
                <div class="text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Developed by <span class="font-semibold text-gray-700 dark:text-gray-300">Ahmed
                            Septiyanto</span>
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        &copy; 2026 All Rights Reserved
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <!-- CHATBOT SECTION END -->

    @stack('scripts')
    <div id="sidebar-overlay" class="sidebar-backdrop" onclick="document.body.classList.remove('sidebar-open')"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const body = document.body;
            
            // Check for saved state (Desktop only)
            if (window.innerWidth >= 640 && localStorage.getItem('sidebar-collapsed') === 'true') {
                body.classList.add('sidebar-hidden');
            }

            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth < 640) {
                    body.classList.toggle('sidebar-open');
                } else {
                    body.classList.toggle('sidebar-hidden');
                    const isCollapsed = body.classList.contains('sidebar-hidden');
                    localStorage.setItem('sidebar-collapsed', isCollapsed);
                }
            });

            // Close sidebar on mobile when clicking a link
            const sidebarLinks = document.querySelectorAll('#sidebar a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 640) {
                        body.classList.remove('sidebar-open');
                    }
                });
            });
        });
    </script>
</body>

</html>