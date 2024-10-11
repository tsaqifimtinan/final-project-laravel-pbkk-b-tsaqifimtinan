<template>
    <div>
        <h3 class="text-lg font-semibold mb-4">Manage treatments</h3>
        <input
        v-model="searchQuery"
        @input="searchTreatments"
        type="text"
        placeholder="Search treatments..."
        class="mb-4 p-2 border rounded"
        />
        <button @click="toggleAddTreatmentsForm" class="mb-4 ml-4 p-2 bg-green-500 text-white rounded hover:bg-green-600">
        Add Treatment
        </button>

        <!-- Form for adding new treatment -->
        <div v-if="isAddingTreatment" class="mb-6">
        <h4 class="font-semibold mb-2">Add New treatment</h4>
        <form @submit.prevent="submitTreatmentForm">
            <div class="mb-4">
            <label class="block text-gray-700">Patient ID</label>
            <input v-model="newTreatment.patient_id" type="number" class="p-2 border rounded w-full" required />
            </div>
            <div class="mb-4">
            <label class="block text-gray-700">treatment Name</label>
            <input v-model="newTreatment.treatment_name" type="text" class="p-2 border rounded w-full" required />
            </div>
            <div class="mb-4">
            <label class="block text-gray-700">Description</label>
            <input v-model="newTreatment.description" type="text" class="p-2 border rounded w-full" required />
            </div>
            <div class="mb-4">
            <label class="block text-gray-700">Precription Date</label>
            <input v-model="newTreatment.treatment_date" type="date" class="p-2 border rounded w-full" required />
            </div>
            <div class="flex space-x-4">
            <button type="submit" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
            <button type="button" @click="cancelAddTreatments" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Cancel
            </button>
            </div>
        </form>
        </div>
        <table class="min-w-full bg-white">
        <thead>
            <tr>
            <th class="py-2 text-left">Patient ID</th>
            <th class="py-2 text-left">Treatment Name</th>
            <th class="py-2 text-left">Description</th>
            <th class="py-2 text-left">Treatment Date</th>
            <th class="py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="treatment in filteredTreatments" :key="treatment.id">
            <td class="py-2">
                <div v-if="editingtreatmentID === treatment.id">
                <input v-model="treatment.patient_id" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ treatment.patient_id }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingtreatmentID === treatment.id">
                <input v-model="treatment.treatment_name" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ treatment.treatment_name }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingtreatmentID === treatment.id">
                <input v-model="treatment.description" :class="{'desc-overflow': isDescriptionOverflow(treatment.description)}" class="p-2 border rounded" />
                </div>
                <div v-else :class="{'desc-overflow': isDescriptionOverflow(treatment.description)}">
                {{ treatment.description }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingtreatmentID === treatment.id">
                <input v-model="treatment.treatment_date" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ treatment.treatment_date }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingtreatmentID === treatment.id">
                <button @click="saveTreatment(treatment)" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Save
                </button>
                <button @click="cancelEdit" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600 ml-2">
                    Cancel
                </button>
                </div>
                <div v-else>
                <button @click="editTreatments(treatment.id)" class="p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                    Edit
                </button>
                <button @click="deleteTreatments(treatment.id)" class="p-2 bg-red-500 text-white rounded hover:bg-red-600 ml-2">
                    Delete
                </button>
                </div>
            </td>
            </tr>
        </tbody>
        </table>
        <div class="mt-4">
        <button
            @click="fetchTreatments(currentPage - 1)"
            :disabled="currentPage === 1"
            class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
        >
            Previous
        </button>
        <span class="mx-2">Page {{ currentPage }} of {{ totalPages }}</span>
        <button
            @click="fetchTreatments(currentPage + 1)"
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

const treatments = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const searchQuery = ref('');
const editingtreatmentID = ref(null);
const isAddingTreatment = ref(false);

// New treatment form data
const newTreatment = ref({
    patient_id: '',
    treatment_name: '',
    description: '',
    treatment_date: '',
});

const fetchTreatments = async (page = 1) => {
    try {
        const response = await fetch (`/api/treatments?page=${page}`);
        if (!response.ok) {
            console.error('Error fetching treatments:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        console.log('API Response:', data);
        treatments.value = data.data;
        currentPage.value = data.current_page;
        totalPages.value = data.last_page;
    } catch (error) {
        console.error('Error fetching treatments:', error);
    }
};

const toggleAddTreatmentsForm = () => {
    isAddingTreatment.value = !isAddingTreatment.value;
};

const submitTreatmentForm = async () => {
    const treatmentData = {
        patient_id: newTreatment.value.patient_id,
        treatment_name: newTreatment.value.treatment_name,
        description: newTreatment.value.description,
        treatment_date: newTreatment.value.treatment_date,
    };

    try {
        const response = await fetch('/api/treatments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(treatmentData),
        });

        if (!response.ok) {
            console.error('Error adding treatment:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        treatments.value.push(data);
        console.log('API Response:', data);

        await fetchTreatments(currentPage.value);
        resetForm();
        isAddingTreatment.value = false;
    } catch (error) {
        console.error('Error adding treatment:', error);
    }
};

const resetForm = () => {
    newTreatment.value = {
        patient_id: '',
        treatment_name: '',
        description: '',
        treatment_date: '',
    };
    isAddingTreatment.value = false;
};

const cancelAddTreatments = () => {
    resetForm();
};

const editTreatments = (id) => {
    editingtreatmentID.value = id;
};

const saveTreatment = async (treatment) => {
    try {
        const response = await fetch(`/api/treatments/${treatment.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                patient_id: treatment.patient_id,
                treatment_name: treatment.treatment_name,
                description: treatment.description,
                treatment_date: treatment.treatment_date,
            }),
        });

        if (!response.ok) {
            throw new Error('An error occurred while saving the treatment');
        }

        const data = await response.json();
        console.log('Updated treatment:', data);

        const index = treatments.value.findIndex(t => t.id === treatment.id);
        if (index !== -1) {
            treatments.value[index] = data;
            console.log('Updated treatments array:', treatments.value);
        } else {
            console.error('Treatment not found in the array');
        }

        editingtreatmentID.value = null;
        await fetchTreatments(currentPage.value);
        console.log('Treatments array after saving:', treatments.value);
    } catch (error) {
        console.error(error);
    }
};

const cancelEdit = () => {
    editingtreatmentID.value = null;
    isAddingTreatment.value = false;
};

const deleteTreatments = async (id) => {
    try {
        const response = await fetch(`/api/treatments/${id}`, {
            method: 'DELETE',
        });

        if (!response.ok) {
            console.error('Error deleting treatment:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        console.log('treatment deleted:', id);
        treatments.value = treatments.value.filter(a => a.id !== id);
    } catch (error) {
        console.error('Error deleting treatment:', error);
    }
}

const searchTreatments = () => {
    fetchTreatments(currentPage.value);
}

const filteredTreatments = computed(() => {
    return treatments.value.filter(treatment =>
        treatment.patient_id?.toString().includes(searchQuery.value) ||
        treatment.treatment_name?.toString().includes(searchQuery.value) ||
        treatment.description?.includes(searchQuery.value) ||
        treatment.treatment_date?.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const isDescriptionOverflow = (description) => {
    return description.length > 50;
};

onMounted(() => fetchTreatments(currentPage.value));
</script>

<style scoped>
.desc-overflow {
    font-size: 0.8rem;
}

</style>