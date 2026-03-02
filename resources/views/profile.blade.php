@extends('layouts.app')

@section('title', __('Mi perfil'))

@section('content')
    <div class="grid gap-5 lg:gap-7.5 max-w-5xl mx-auto">
        <div class="kt-card">
            <div class="kt-card-content">
                <div class="max-w-3xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>
        </div>

        <div class="kt-card">
            <div class="kt-card-content">
                <div class="max-w-3xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>
        </div>

        <div class="kt-card">
            <div class="kt-card-content">
                <div class="max-w-3xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
@endsection
