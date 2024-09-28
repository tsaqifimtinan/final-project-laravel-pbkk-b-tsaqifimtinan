<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue'; // Your existing component
import { Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';

// User role state
const userRole = ref('');
const isLoading = ref(true);
const errorMessage = ref('');

// Example links array
const links = [
  { href: '/doctors', text: 'Doctors' },
  { href: '/patients', text: 'Patients' },
  { href: '/appointments', text: 'Appointments' },
  { href: '/invoices', text: 'Invoices' },
  { href: '/medications', text: 'Medications' },
  { href: '/payments', text: 'Payments' },
  { href: '/prescriptions', text: 'Prescriptions' },
  { href: '/rooms', text: 'Rooms' },
  { href: '/treatments', text: 'Treatments' },
];

// Computed property to filter links based on user role
const filteredLinks = computed(() => {
  if (userRole.value === 'admin') {
    return links;
  } else if (userRole.value === 'doctor') {
    return links.filter(link => ['Doctors', 'Patients'].includes(link.text));
  } else if (userRole.value === 'patient') {
    return links.filter(link => link.text === 'Doctors');
  }
  return [];
});

// Fetch user role from API or global state
const fetchUserRole = async () => {
  try {
    console.log('Fetching user role...');
    const response = await fetch('/api/user-role');
    if (!response.ok) {
      throw new Error('Failed to fetch user role');
    }
    const data = await response.json();
    console.log('User role data:', data);
    userRole.value = data.role; // Assuming the API returns { role: 'admin' | 'doctor' | 'patient' }
    isLoading.value = false;
  } catch (error) {
    console.error('Error fetching user role:', error);
    errorMessage.value = 'Failed to load user role. Please try again later.';
    isLoading.value = false;
  }
};

// Fetch user role on component mount
onMounted(fetchUserRole);
</script>

<template>
    <AppLayout title="Dashboard">
        <!-- Customize the header slot if needed -->
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
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
                                <Link href="/doctors" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
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
                            <!-- Add more sidebar links as needed -->
                        </ul>
                    </aside>

                    <!-- Main Content Section -->
                    <div class="col-span-3 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <!-- Add custom content for the dashboard -->
                        <Welcome />
                        
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Add any custom styles if needed */
</style>