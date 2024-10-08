<template>
    <div>
        <h3 class="text-lg font-semibold mb-4">Manage prescriptions</h3>
        <input
        v-model="searchQuery"
        @input="searchprescriptions"
        type="text"
        placeholder="Search prescriptions..."
        class="mb-4 p-2 border rounded"
        />
        <button @click="toggleAddPrescriptionForm" class="mb-4 ml-4 p-2 bg-green-500 text-white rounded hover:bg-green-600">
        Add Prescription
        </button>

        <!-- Form for adding new prescription -->
        <div v-if="isAddingPrescription" class="mb-6">
        <h4 class="font-semibold mb-2">Add New Prescription</h4>
        <form @submit.prevent="submitprescriptionForm" enctype="multipart/form-data">
            <div class="mb-4">
            <label class="block text-gray-700">Patient ID</label>
            <input v-model="newPrescription.patient_id" type="number" class="p-2 border rounded w-full" required />
            </div>
            <div class="mb-4">
            <label class="block text-gray-700">Prescription Name</label>
            <input v-model="newPrescription.prescription_name" type="number" class="p-2 border rounded w-full" required />
            </div>
            <div class="mb-4">
            <label class="block text-gray-700">Description</label>
            <input v-model="newPrescription.description" type="date" class="p-2 border rounded w-full" required />
            </div>
            <div class="mb-4">
            <label class="block text-gray-700">Precription Date</label>
            <input v-model="newPrescription.prescription_date" type="text" class="p-2 border rounded w-full" required />
            </div>
            <div class="flex space-x-4">
            <button type="submit" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
            <button type="button" @click="cancelAddPrecription" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Cancel
            </button>
            </div>
        </form>
        </div>
        <table class="min-w-full bg-white">
        <thead>
            <tr>
            <th class="py-2 text-left">Patient ID</th>
            <th class="py-2 text-left">Prescription Name</th>
            <th class="py-2 text-left">Description</th>
            <th class="py-2 text-left">Prescription Date</th>
            <th class="py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="prescription in filteredPrescriptions" :key="prescription.id">
            <td class="py-2">
                <div v-if="editingPrescriptionID === prescription.id">
                <input v-model="prescription.patient_id" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ prescription.patient_id }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingPrescriptionID === prescription.id">
                <input v-model="prescription.prescription_name" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ prescription.prescription_name }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingPrescriptionID === prescription.id">
                <input v-model="prescription.description" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ prescription.description }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingPrescriptionID === prescription.id">
                <input v-model="prescription.prescription_date" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ prescription.prescription_date }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingPrescriptionID === prescription.id">
                <button @click="savePrescription(prescription)" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Save
                </button>
                <button @click="cancelEdit" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600 ml-2">
                    Cancel
                </button>
                </div>
                <div v-else>
                <button @click="editPrescription(prescription.id)" class="p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                    Edit
                </button>
                <button @click="deletePrescription(prescription.id)" class="p-2 bg-red-500 text-white rounded hover:bg-red-600 ml-2">
                    Delete
                </button>
                </div>
            </td>
            </tr>
        </tbody>
        </table>
        <div class="mt-4">
        <button
            @click="fetchPrescriptions(currentPage - 1)"
            :disabled="currentPage === 1"
            class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
        >
            Previous
        </button>
        <span class="mx-2">Page {{ currentPage }} of {{ totalPages }}</span>
        <button
            @click="fetchPrescriptions(currentPage + 1)"
            :disabled="currentPage === totalPages"
            class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
        >
            Next
        </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const prescriptions = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const searchQuery = ref('');
const editingPrescriptionID = ref(null);
const isAddingPrescription = ref(false);

// New prescription form data
const newPrescription = ref({
    patient_id: '',
    prescription_name: '',
    description: '',
    prescription_date: '',
});

const fetchPrescriptions = async (page = 1) => {
    try {
        const response = await fetch (`/api/prescriptions?page=${page}`);
        if (!response.ok) {
            console.error('Error fetching prescriptions:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        console.log('API Response:', data);
        prescriptions.value = data.data;
        currentPage.value = data.current_page;
        totalPages.value = data.last_page;
    } catch (error) {
        console.error('Error fetching prescriptions:', error);
    }
};

const toggleAddPrescriptionForm = () => {
    isAddingPrescription.value = !isAddingPrescription.value;
};

const submitprescriptionForm = async () => {
    const prescriptionData = {
        patient_id: newPrescription.value.patient_id,
        prescription_name: newPrescription.value.prescription_name,
        description: newPrescription.value.description,
        prescription_date: newPrescription.value.prescription_date,
    };

    try {
        const response = await fetch('/api/prescriptions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(prescriptionData),
        });

        if (!response.ok) {
            console.error('Error adding prescription:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        prescription.value.push(data);
        console.log('API Response:', data);
        resetForm();
        isAddingPrescription.value = false;
    } catch (error) {
        console.error('Error adding prescription:', error);
    }
};

const resetForm = () => {
    newPrescription.value = {
        patient_id: '',
        prescription_name: '',
        description: '',
        prescription_date: '',
    };
    isAddingPrescription.value = false;
};

const cancelAddPrecription = () => {
    resetForm();
};

const editPrescription = (id) => {
    editingPrescriptionID.value = id;
};

const savePrescription = async (prescription) => {
    try {
        console.log('Saving prescription:', prescription);

        const prescriptionData = {
            patient_id: prescription.patient_id,
            prescription_name: prescription.prescription_name,
            description: prescription.description,
            prescription_date: prescription.prescription_date,
        };

        const response = await fetch(`/api/prescriptions/${prescription.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(prescriptionData),
        });

        if (!response.ok) {
            console.error('Error saving prescription:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        console.log('API Response:', data);

        const index = prescriptions.value.findIndex(a => a.id === prescription.id);
        if (index !== -1) {
            prescriptions.value[index] = data;
        } else {
            prescriptions.value.push(data);
        }

        editingPrescriptionID.value = null;
        await fetchPrescriptions(currentPage.value);
    } catch (error) {
        console.error('Error saving prescription:', error);
    }
};

const deletePrescription = async (id) => {
    try {
        const response = await fetch(`/api/prescriptions/${id}`, {
            method: 'DELETE',
        });

        if (!response.ok) {
            console.error('Error deleting prescription:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        console.log('prescription deleted:', id);
        prescriptions.value = prescriptions.value.filter(a => a.id !== id);
    } catch (error) {
        console.error('Error deleting prescription:', error);
    }
}

const searchprescriptions = () => {
    fetchPrescriptions(currentPage.value);
}

const filteredPrescriptions = computed(() => {
    return prescriptions.value.filter(prescription =>
        prescription.patient_id.toString().includes(searchQuery.value) ||
        prescription.prescription_name.toString().includes(searchQuery.value) ||
        prescription.description.includes(searchQuery.value) ||
        prescription.prescription_date.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

onMounted(() => fetchPrescriptions(currentPage.value));
</script>

<style scoped>

</style>