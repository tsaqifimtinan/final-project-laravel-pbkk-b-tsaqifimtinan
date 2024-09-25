@extends('layout.template')

@section('sidebar')
    <x-menu-tree title="Hospital" icon="fas fa-tachometer-alt" :active="request()->is('pertemuan2/*')">
        <x-menu-item title="Appointments" icon="fas fa-calendar" href="{{ route('appointments') }}" :active="request()->is('appointments')"></x-menu-item>
        <x-menu-item title="Patients" icon="fas fa-user" href="{{ route('patients') }}" :active="request()->is('patients')"></x-menu-item>
        <x-menu-item title="Invoices" icon="fas fa-file-invoice" href="{{ route('invoices') }}" :active="request()->is('invoices')"></x-menu-item>
    </x-menu-tree>
@endsection

@section('content')
    @yield('table-content')
@endsection
