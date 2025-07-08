<?php
$currentUrl = \Request::segment(2);
?>

@extends('admin::layouts.master')

@section('navigation')
    <div class="fixed z-30 w-full bg-white border-b-2 shadow-md">
        <div class="px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button class="p-2 text-gray-600 rounded cursor-pointer lg:hidden" onclick="toogleNav()">
                        <svg xmlns="https://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <a href="#" class="flex items-center text-xl font-bold">
                        <img src="{{ url('build/images/logo.png') }}" alt="logo" class="h-8">
                    </a>

                </div>
                <div class="flex items-center">
                    <div class="relative inline-block text-left mr-4">
                        <button onclick="document.getElementById('langMenu').classList.toggle('hidden')" class="inline-flex items-center px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                            {{ strtoupper(app()->getLocale()) }}
                            <svg class="w-4 h-4 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06-.02L10 10.586l3.71-3.396a.75.75 0 111.02 1.1l-4.25 3.894a.75.75 0 01-1.02 0l-4.25-3.894a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div id="langMenu" class="absolute z-50 hidden mt-2 bg-white border rounded shadow w-24">
                            @foreach (['en' => 'English', 'hu' => 'Magyar', 'sr' => 'Srpski'] as $code => $language)
                                @if ($code !== app()->getLocale())
                                    <a href="{{ route('admin.setLocale', $code) }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100">
                                        {{ $language }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="relative inline-block ">
                        <!-- Logout button -->
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button class="relative flex items-center p-2">
                                <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                    <path fill="none" d="M0 0h24v24H0z" />
                                    <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2a9.985 9.985 0 0 1 8 4h-2.71a8 8 0 1 0 .001 12h2.71A9.985 9.985 0 0 1 12 22zm7-6v-3h-8v-2h8V8l5 4-5 4z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pt-12 lg:flex">
        <div class="flex flex-col w-full px-4 py-8 overflow-y-auto border-b lg:border-r border-r-slate-300 lg:min-h-[100vh] lg:h-100 lg:w-64">
            <div class="hidden lg:flex flex-col justify-between mt-6" id="mobile-menu">
                <aside>
                    <ul>
                        <li>
                            <a class="flex items-center px-4 py-2 text-gray-600 rounded-lg {{ $currentUrl == 'dashboard' ? 'bg-white shadow-md' : 'bg-gray-100' }}" href="{{ route('admin.dashboard') }}">
                                <i class="ri-home-4-line text-2xl"></i>
                                <span class="mx-4 font-medium">Irányítópult</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center px-4 py-2 mt-5 text-gray-600 rounded-lg {{ $currentUrl == 'user' ? 'bg-white shadow-md' : 'bg-gray-100' }}" href="{{ route('admin.user.index') }}">
                                <i class="ri-team-line text-2xl"></i>
                                <span class="mx-4 font-medium">Felhasználók</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center px-4 py-2 mt-5 text-gray-600 rounded-lg {{ $currentUrl == 'category' ? 'bg-white shadow-md' : 'bg-gray-100' }}" href="{{ route('admin.category.index') }}">
                                <i class="ri-list-check-2 text-2xl"></i>
                                <span class="mx-4 font-medium">Kategóriák</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center px-4 py-2 mt-5 text-gray-600 rounded-lg {{ $currentUrl == 'album' ? 'bg-white shadow-md' : 'bg-gray-100' }}" href="{{ route('admin.album.index') }}">
                                <i class="ri-album-line text-2xl"></i>
                                <span class="mx-4 font-medium">Albumok</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center px-4 py-2 mt-5 text-gray-600 rounded-lg {{ $currentUrl == 'email' ? 'bg-white shadow-md' : 'bg-gray-100' }}" href="{{ route('admin.email.index') }}">
                                <i class="ri-mail-line text-2xl"></i>
                                <span class="mx-4 font-medium">Emailek</span>
                            </a>
                        </li>
                    </ul>
                </aside>
            </div>
        </div>
        <div class="w-full h-full p-4 sm:p-12 overflow-y-auto">
            @include('admin::layouts.flash-message')

            @yield('content')
        </div>
    </div>
@endsection