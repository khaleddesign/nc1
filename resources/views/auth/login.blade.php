@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                {{ __('Connexion à votre compte') }}
            </h2>
        </div>
        
        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="rounded-md shadow-sm -space-y-px">
                <!-- Email Address -->
                <div>
                    <label for="email" class="sr-only">{{ __('Email Address') }}</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        autofocus
                        value="{{ old('email') }}"
                        class="relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('email') border-red-300 @enderror" 
                        placeholder="{{ __('Adresse email') }}"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div>
                    <label for="password" class="sr-only">{{ __('Password') }}</label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        autocomplete="current-password" 
                        required
                        class="relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('password') border-red-300 @enderror" 
                        placeholder="{{ __('Mot de passe') }}"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input 
                        id="remember" 
                        name="remember" 
                        type="checkbox" 
                        {{ old('remember') ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        {{ __('Se souvenir de moi') }}
                    </label>
                </div>

                <!-- Forgot Password Link - Version sécurisée sans Route -->
                @php
                    $hasPasswordRequest = false;
                    try {
                        $hasPasswordRequest = \Illuminate\Support\Facades\Route::has('password.request');
                    } catch (\Exception $e) {
                        $hasPasswordRequest = false;
                    }
                @endphp
                
                @if($hasPasswordRequest)
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            {{ __('Mot de passe oublié ?') }}
                        </a>
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div>
                <button 
                    type="submit" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                >
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <!-- Lock Icon -->
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    {{ __('Se connecter') }}
                </button>
            </div>

            <!-- Registration Link -->
            @php
                $hasRegister = false;
                try {
                    $hasRegister = \Illuminate\Support\Facades\Route::has('register');
                } catch (\Exception $e) {
                    $hasRegister = false;
                }
            @endphp
            
            @if($hasRegister)
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        {{ __("Pas encore de compte ?") }}
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            {{ __('Créer un compte') }}
                        </a>
                    </p>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection