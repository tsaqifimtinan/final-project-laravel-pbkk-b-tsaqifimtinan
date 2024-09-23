@extends('layout.template')

@section('sidebar')
    @guest
    <x-menu-tree title="Authentication" icon="fas fa-tachometer-alt" :active="request()->is('auth/*')">
        <x-menu-item title="Login" icon="fas fa-user" href="login" :active="request()->is('auth/login')"></x-menu-item>
        <x-menu-item title="Register" icon="fas fa-user" href="register" :active="request()->is('auth/login')"></x-menu-item>
    </x-menu-tree>
    @endguest

    <x-menu-tree title="Final Project ETS" icon="fas fa-tachometer-alt" :active="request()->is('pertemuan2/*')">
        <x-menu-item title="Appointments" icon="fas fa-calendar" href="{{ route('appointments') }}" :active="request()->is('appointments')"></x-menu-item>
        <x-menu-item title="Patients" icon="fas fa-user" href="{{ route('patients') }}" :active="request()->is('patients')"></x-menu-item>
        <x-menu-item title="Invoices" icon="fas fa-file-invoice" href="{{ route('invoices') }}" :active="request()->is('invoices')"></x-menu-item>
    </x-menu-tree>
@endsection

@section('content')
    @yield('table-content')
@endsection
