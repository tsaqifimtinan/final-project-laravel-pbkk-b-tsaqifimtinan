<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ManageMedication from '@/Pages/Medication/ManageMedication.vue'; // Import the new component
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <AppLayout title="Dashboard">
        <!-- Customize the header slot if needed -->
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Medication
            </h2>
        </template>

        <!-- Add a sidebar and main content section -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Grid layout: Sidebar on the left, main content on the right -->
                <div class="grid grid-cols-4 gap-4">
                    <!-- Sidebar Section -->
                    <aside class="col-span-1 bg-gray-100 p-4 rounded-lg shadow">
                        <h3 class="font-semibold text-lg mb-4">Menu</h3>
                        <ul class="space-y-2">
                            <li>
                                <Link href="/Medications" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Doctors
                                </Link>
                            </li>
                            <li>
                                <Link href="/patients" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Patients
                                </Link>
                            </li>
                            <li>
                                <Link href="/appointments" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Appointments
                                </Link>
                            </li>
                            <li>
                                <Link href="/invoices" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Invoices
                                </Link>
                            </li>
                            <li>
                                <Link href="/medications" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Medications
                                </Link>
                            </li>
                            <li>
                                <Link href="/payments" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Payments
                                </Link>
                            </li>
                            <li>
                                <Link href="/prescriptions" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Prescriptions
                                </Link>
                            </li>
                            <li>
                                <Link href="/rooms" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Rooms
                                </Link>
                            </li>
                            <li>
                                <Link href="/treatments" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Treatments
                                </Link>
                            </li>
                            <li>
                                <Link href="/restfulapi" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    API
                                </Link>
                            </li>
                            <!-- Add more sidebar links as needed -->
                        </ul>
                    </aside>    

                    <!-- Main Content Section -->
                    <div class="col-span-3 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <!-- Replace Welcome component with ManageMedication component -->
                        <ManageMedication />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>