<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ManageDoctors from '@/Pages/Doctor/ManageDoctors.vue'; // Import the new component
</script>

<template>
    <AppLayout title="Dashboard">
        <!-- Customize the header slot if needed -->
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Doctor
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
                            <router-link to="/doctors" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Doctor
                            </router-link>
                            </li>
                            <li>
                            <router-link to="/profile" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Profile
                            </router-link>
                            </li>
                            <li>
                            <router-link to="/settings" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Settings
                            </router-link>
                            </li>
                            <!-- Add more sidebar links as needed -->
                        </ul>
                    </aside>

                    <!-- Main Content Section -->
                    <div class="col-span-3 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <!-- Replace Welcome component with ManageDoctors component -->
                        <ManageDoctors />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>