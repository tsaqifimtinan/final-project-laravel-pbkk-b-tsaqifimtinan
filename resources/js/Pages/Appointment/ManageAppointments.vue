<template>
    <div>
        <h3 class="text-lg font-semibold mb-4">Manage Appointments</h3>
        <input
        v-model="searchQuery"
        @input="searchAppointments"
        type="text"
        placeholder="Search appointments..."
        class="mb-4 p-2 border rounded"
        />
        <button @click="toggleAddAppointmentForm" class="mb-4 ml-4 p-2 bg-green-500 text-white rounded hover:bg-green-600">
        Add Appointment
        </button>

        <!-- Form for adding new Appointment -->
        <div v-if="isAddingAppointment" class="mb-6">
        <h4 class="font-semibold mb-2">Add New Appointment</h4>
        <form @submit.prevent="submitAppointmentForm" enctype="multipart/form-data">
            <div class="mb-4">
            <label class="block text-gray-700">Patient ID</label>
            <input v-model="newAppointment.patient_id" type="number" class="p-2 border rounded w-full" required />
            </div>
            <div class="mb-4">
            <label class="block text-gray-700">Doctor ID</label>
            <input v-model="newAppointment.doctor_id" type="number" class="p-2 border rounded w-full" required />
            </div>
            <div class="mb-4">
            <label class="block text-gray-700">Appointment Date</label>
            <input v-model="newAppointment.appointment_date" type="date" class="p-2 border rounded w-full" required />
            </div>
            <div class="mb-4">
            <label class="block text-gray-700">Notes</label>
            <input v-model="newAppointment.notes" type="text" class="p-2 border rounded w-full" required />
            </div>
            <div class="flex space-x-4">
            <button type="submit" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
            <button type="button" @click="cancelAddAppointment" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Cancel
            </button>
            </div>
        </form>
        </div>
        <table class="min-w-full bg-white">
        <thead>
            <tr>
            <th class="py-2 text-left">Patient ID</th>
            <th class="py-2 text-left">Doctor ID</th>
            <th class="py-2 text-left">Appointment Date</th>
            <th class="py-2 text-left">Notes</th>
            <th class="py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="appointment in filteredAppointments" :key="appointment.id">
            <td class="py-2">
                <div v-if="editingAppointmentID === appointment.id">
                <input v-model="appointment.patient_id" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ appointment.patient_id }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingAppointmentID === appointment.id">
                <input v-model="appointment.doctor_id" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ appointment.doctor_id }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingAppointmentID === appointment.id">
                <input v-model="appointment.appointment_date" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ appointment.appointment_date }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingAppointmentID === appointment.id">
                <input v-model="appointment.notes" :class="{'notes-overflow': isNotesOverflow(appointment.notes)}" class="p-2 border rounded" />
                </div>
                <div v-else :class="{'notes-overflow': isNotesOverflow(appointment.notes)}">
                {{ appointment.notes }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingAppointmentID === appointment.id">
                <button @click="saveAppointment(appointment)" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Save
                </button>
                </div>
                <div v-else>
                <button @click="editAppointment(appointment.id)" class="p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                    Edit
                </button>
                <button @click="deleteAppointment(appointment.id)" class="p-2 bg-red-500 text-white rounded hover:bg-red-600 ml-2">
                    Delete
                </button>
                </div>
            </td>
            </tr>
        </tbody>
        </table>
        <div class="mt-4">
        <button
            @click="fetchAppointments(currentPage - 1)"
            :disabled="currentPage === 1"
            class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
        >
            Previous
        </button>
        <span class="mx-2">Page {{ currentPage }} of {{ totalPages }}</span>
        <button
            @click="fetchAppointments(currentPage + 1)"
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

const appointments = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const searchQuery = ref('');
const editingAppointmentID = ref(null);
const isAddingAppointment = ref(false);

// New Appointment form data
const newAppointment = ref({
    patient_id: '',
    doctor_id: '',
    appointment_date: '',
    notes: '',
});

const fetchAppointments = async (page = 1) => {
    try {
        const response = await fetch(`/api/appointments?page=${page}`);
        if (!response.ok) {
            console.error('Error fetching appointments:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        console.log('API Response:', data);
        appointments.value = data.data;
        currentPage.value = data.current_page;
        totalPages.value = data.last_page;
    } catch (error) {
        console.error('Error fetching appointments:', error);
    }
};

const toggleAddAppointmentForm = () => {
    isAddingAppointment.value = !isAddingAppointment.value;
};

const submitAppointmentForm = async () => {
    const payload = {
        patient_id: newAppointment.value.patient_id,
        doctor_id: newAppointment.value.doctor_id,
        appointment_date: newAppointment.value.appointment_date,
        notes: newAppointment.value.notes
    };

    try {
        const response = await fetch('/api/appointments', {
            method: 'POST',
            body: JSON.stringify(payload),
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            console.error('Error adding appointment:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        appointments.value.push(data); // Assuming `appointments` is the correct variable
        console.log('API Response:', data);
        resetForm();
        isAddingAppointment.value = false;
    } catch (error) {
        console.error('Error adding appointment:', error);
    }
}

const resetForm = () => {
    newAppointment.value = {
        patient_id: '',
        doctor_id: '',
        appointment_date: '',
        notes: '',
    };
    isAddingAppointment.value = false;
};

const cancelAddAppointment = () => {
    resetForm();
};

const editAppointment = (id) => {
    editingAppointmentID.value = id;
};

const saveAppointment = async (appointment) => {
    try {
        console.log('Saving appointment:', appointment);

        const payload = {
            patient_id: appointment.patient_id,
            doctor_id: appointment.doctor_id,
            appointment_date: appointment.appointment_date,
            notes: appointment.notes
        };

        const response = await fetch(`/api/appointments/${appointment.id}`, {
            method: 'PUT',
            body: JSON.stringify(payload),
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            console.error('Error saving appointment:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        console.log('API Response:', data);

        const index = appointments.value.findIndex(a => a.id === appointment.id);
        if (index !== -1) {
            appointments.value[index] = data;
        } else {
            appointments.value.push(data);
        }

        editingAppointmentID.value = null;
        await fetchAppointments(currentPage.value);
    } catch (error) {
        console.error('Error saving appointment:', error);
    }
}

const cancelEdit = () => {
    editingAppointmentID.value = null;
    fetchAppointments(currentPage.value);
};

const deleteAppointment = async (id) => {
    try {
        const response = await fetch(`/api/appointments/${id}`, {
            method: 'DELETE',
        });

        if (!response.ok) {
            console.error('Error deleting appointment:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        console.log('Appointment deleted:', id);
        appointments.value = appointments.value.filter(a => a.id !== id);
    } catch (error) {
        console.error('Error deleting appointment:', error);
    }
}

const searchAppointments = () => {
    fetchAppointments(currentPage.value);
}

const filteredAppointments = computed(() => {
    return appointments.value.filter(appointment =>
        (appointment.patient_id && appointment.patient_id.toString().includes(searchQuery.value)) ||
        (appointment.doctor_id && appointment.doctor_id.toString().includes(searchQuery.value)) ||
        (appointment.appointment_date && appointment.appointment_date.includes(searchQuery.value)) ||
        (appointment.notes && appointment.notes.toLowerCase().includes(searchQuery.value.toLowerCase()))
    );
});

const isNotesOverflow = (notes) => {
    return notes.length > 50; // Adjust the length as needed
};

onMounted(() => fetchAppointments(currentPage.value));
</script>

<style scoped>
.notes-overflow {
    font-size: 0.8rem; /* Adjust the font size as needed */
}
</style>