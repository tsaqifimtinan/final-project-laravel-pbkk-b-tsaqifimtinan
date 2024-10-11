<template>
    <div>
        <h3 class="text-lg font-semibold mb-4">Manage medications</h3>
        <input
            v-model="searchQuery"
            @input="searchMedications"
            type="text"
            placeholder="Search medications..."
            class="mb-4 p-2 border rounded"
        />
        <button @click="toggleAddMedicationForm" class="mb-4 ml-4 p-2 bg-green-500 text-white rounded hover:bg-green-600">
            Add Medication
        </button>

        <!-- Form for adding new medication -->
        <div v-if="isAddingMedication" class="mb-6">
            <h4 class="font-semibold mb-2">Add New Medication</h4>
            <form @submit.prevent="submitMedicationForm">
                <div class="mb-4">
                    <label class="block text-gray-700">Patient ID</label>
                    <input v-model="newMedication.patient_id" type="text" class="p-2 border rounded w-full" required />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Medication Name</label>
                    <input v-model="newMedication.medication_name" type="text" class="p-2 border rounded w-full" required />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Description</label>
                    <input v-model="newMedication.description" type="text" class="p-2 border rounded w-full" required />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Medication Date</label>
                    <input v-model="newMedication.medication_date" type="text" class="p-2 border rounded w-full" required />
                </div>
                <div class="flex space-x-4">
                    <button type="submit" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                    <button type="button" @click="cancelAddMedication" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 text-left">Patient ID</th>
                    <th class="py-2 text-left">Medication Name</th>
                    <th class="py-2 text-left">Description</th>
                    <th class="py-2 text-left">Medication Date</th>
                    <th class="py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="medication in filteredMedications" :key="medication.id">
                    <td class="py-2">
                        <div v-if="editingMedicationId === medication.id">
                            <input v-model="medication.patient_id" class="p-2 border rounded" />
                        </div>
                        <div v-else>
                            {{ medication.patient_id }}
                        </div>
                    </td>
                    <td class="py-2">
                        <div v-if="editingMedicationId === medication.id">
                            <input v-model="medication.medication_name" class="p-2 border rounded" />
                        </div>
                        <div v-else>
                            {{ medication.medication_name }}
                        </div>
                    </td>
                    <td class="py-2">
                        <div v-if="editingMedicationId === medication.id">
                            <input v-model="medication.description" :class="{'desc-overflow': isDescriptionOverflow(medication.description)}" class="p-2 border rounded" />
                        </div>
                        <div v-else :class="{'desc-overflow': isDescriptionOverflow(medication.description)}">
                            {{ medication.description }}
                        </div>
                    </td>
                    <td class="py-2">
                        <div v-if="editingMedicationId === medication.id">
                            <input v-model="medication.medication_date" class="p-2 border rounded" />
                        </div>
                        <div v-else>
                            {{ medication.medication_date }}
                        </div>
                    </td>
                    <td class="py-2">
                        <div v-if="editingMedicationId === medication.id">
                            <button @click="saveMedication(medication)" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Save
                            </button>
                        </div>
                        <div v-else>
                            <button @click="editMedication(medication.id)" class="p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                Edit
                            </button>
                            <button @click="deleteMedication(medication.id)" class="p-2 bg-red-500 text-white rounded hover:bg-red-600 ml-2">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="mt-4">
            <button
                @click="fetchMedications(currentPage - 1)"
                :disabled="currentPage === 1"
                class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
            >
                Previous
            </button>
            <span class="mx-2">Page {{ currentPage }} of {{ totalPages }}</span>
            <button
                @click="fetchMedications(currentPage + 1)"
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

const medications = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const searchQuery = ref('');
const editingMedicationId = ref(null);
const isAddingMedication = ref(false);

const newMedication = ref({
    patient_id: '',
    medication_name: '',
    description: '',
    medication_date: '',
});

const fetchMedications = async (page = 1) => {
    try {
        const response = await fetch(`/api/medications?page=${page}`);
        if (!response.ok) {
            throw new Error('An error occurred while fetching the data');
        }

        const data = await response.json();
        console.log('API response:', data); // Log API response
        medications.value = data.data;
        currentPage.value = data.current_page;
        totalPages.value = data.last_page;
        console.log('Medications array:', medications.value); // Log medications array
    } catch (error) {
        console.error(error);
    }
};

const toggleAddMedicationForm = () => {
    isAddingMedication.value = !isAddingMedication.value;
};

const submitMedicationForm = async () => {
    const payload = {
        patient_id: newMedication.value.patient_id,
        medication_name: newMedication.value.medication_name,
        description: newMedication.value.description,
        medication_date: newMedication.value.medication_date,
    };

    try {
        const response = await fetch('/api/medications', {
            method: 'POST',
            body: JSON.stringify(payload), // Send JSON payload
            headers: {
                'Content-Type': 'application/json', // Set Content-Type header to application/json
            },
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Server error:', errorData);
            throw new Error('Error adding medication');
        }

        const data = await response.json();
        medications.value.push(data); // Add the new medication to the list
        resetForm(); // Clear the form after successful submission
    } catch (error) {
        console.error('Error adding medication:', error);
    }
};

const resetForm = () => {
    newMedication.value.patient_id = '';
    newMedication.value.medication_name = '';
    newMedication.value.description = '';
    newMedication.value.medication_date = '';
    isAddingMedication.value = false;
};

const cancelAddMedication = () => {
    resetForm();
};

const editMedication = (id) => {
    editingMedicationId.value = id;
};

const saveMedication = async (medication) => {
    try {
        const response = await fetch(`/api/medications/${medication.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                patient_id: medication.patient_id,
                medication_name: medication.medication_name,
                description: medication.description,
                medication_date: medication.medication_date,
            }),
        });

        if (!response.ok) {
            throw new Error('An error occurred while saving the medication');
        }

        const data = await response.json();
        console.log('Updated medication:', data);

        const index = medications.value.findIndex(m => m.id === medication.id);
        if (index !== -1) {
            medications.value[index] = data;
            console.log('Updated medications array:', medications.value);
        } else {
            console.error('Medication not found in the array');
        }

        editingMedicationId.value = null;
        await fetchMedications(currentPage.value);
        console.log('Medications array after saving:', medications.value);
    } catch (error) {
        console.error(error);
    }
};

const cancelEdit = () => {
    editingMedicationId.value = null;
    fetchMedications(currentPage.value);
};

const deleteMedication = async (id) => {
    try {
        const response = await fetch(`/api/medications/${id}`, {
            method: 'DELETE',
        });

        if (!response.ok) {
            throw new Error('An error occurred while deleting the medication');
        }

        medications.value = medications.value.filter(m => m.id !== id);
        console.log('Medications array after deleting:', medications.value);
    } catch (error) {
        console.error(error);
    }
};

const searchMedications = () => {
    fetchMedications();
};

const filteredMedications = computed(() => {
    return medications.value.filter(medication => {
        return String(medication.patient_id).toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            String(medication.medication_name).toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            String(medication.description).toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            String(medication.medication_date).toLowerCase().includes(searchQuery.value.toLowerCase());
    });
});

const isDescriptionOverflow = (description) => {
    return description && typeof description === 'string' && description.length > 40;
};

onMounted(() => {
    fetchMedications(currentPage.value);
});
</script>

<style scoped>
.desc-overflow {
    font-size: 0.8rem; /* Adjust the font size as needed */
}
</style>