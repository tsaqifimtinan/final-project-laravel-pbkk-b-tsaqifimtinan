<template>
  <div>
    <h3 class="text-lg font-semibold mb-4">Manage Patients</h3>
    <input
      v-model="searchQuery"
      @input="searchPatients"
      type="text"
      placeholder="Search patients..."
      class="mb-4 p-2 border rounded"
    />
    <button @click="addPatients" class="mb-4 ml-4 p-2 bg-green-500 text-white rounded hover:bg-green-600">
      Add Patient
    </button>
    <table class="min-w-full bg-white">
      <thead>
        <tr>
          <th class="py-2 text-left">Name</th>
          <th class="py-2 text-left">Date of Birth</th>
          <th class="py-2 text-left">Gender</th>
          <th class="py-2 text-left">Address</th>
          <th class="py-2 text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="patient in filteredPatients" :key="patient.id">
          <td class="py-2">
            <div v-if="editingPatientId === patient.id">
              <input v-model="patient.name" class="p-2 border rounded" />
            </div>
            <div v-else>
              {{ patient.name }}
            </div>
          </td>
          <td class="py-2">
            <div v-if="editingPatientId === patient.id">
              <input v-model="patient.age" class="p-2 border rounded" />
            </div>
            <div v-else>
              {{ patient.age }}
            </div>
          </td>
          <td class="py-2">
            <div v-if="editingPatientId === patient.id">
              <button @click="savePatient(patient)" class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                Save
              </button>
              <button @click="cancelEdit" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600 ml-2">
                Cancel
              </button>
            </div>
            <div v-else>
              <button @click="editPatient(patient.id)" class="p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                Edit
              </button>
              <button @click="deletePatient(patient.id)" class="p-2 bg-red-500 text-white rounded hover:bg-red-600 ml-2">
                Delete
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="mt-4">
      <button
        @click="fetchPatients(currentPage - 1)"
        :disabled="currentPage === 1"
        class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
      >
        Previous
      </button>
      <span class="mx-4">{{ currentPage }} of {{ totalPages }}</span>
      <button
        @click="fetchPatients(currentPage + 1)"
        :disabled="currentPage === totalPages"
        class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
      >
        Next
      </button>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';

const patients = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const searchQuery = ref('');
const editingPatientId = ref(null);

const fetchPatients = async (page = 1) => {
  try {
    const response = await fetch(`/api/patients?page=${page}`);
    if (!response.ok) {
      console.error('Error fetching patients:', response.status, response.statusText);
      const errorText = await response.text();
      console.error('Error response text:', errorText);
      return;
    } 
    const data = await response.json();
    console.log('API Response:', data);
    patients.value = data.data;
    currentPage.value = data.meta.current_page;
    totalPages.value = data.meta.last_page;
  } catch (error) {
    console.error('Error in fetchPatients:', error);
  }
};

const addPatients = async () => {
  const newPatient = { name: 'New Patient', specialization: 'New Specialization' };
  try {
    const response = await fetch('/api/patients', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(newPatient),
    });
    console.log('API request sent to /api/patients with POST method');
    if (!response.ok) {
      console.error('Error adding patient:', response.status, response.statusText);
      const errorText = await response.text();
      console.error('Error response text:', errorText);
      return;
    }
    const data = await response.json();
    console.log('Patient added:', data);
    patients.value.push(data);
  } catch (error) {
    console.error('Error adding patient:', error);
  }
};

const editPatient = (id) => {
  editingPatientId.value = id;
};

const savePatient = async (patient) => {
  try {
    console.log('Saving patient:', patient);
    const response = await fetch(`/api/patients/${patient.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name: patient.name,
        date_of_birth: patient.date_of_birth,
        gender: patient.gender,
        address: patient.address,
        phone: patient.phone,
        email: patient.email,
      }),
    });
    console.log('API request sent to /api/patients/' + patient.id + ' with PUT method');
    if (!response.ok) {
      console.error('Error saving patient:', response.status, response.statusText);
      const errorText = await response.text();
      console.error('Error response text:', errorText);
      return;
    }
    const data = await response.json();
    console.log('Saved patient:', data);
    const index = patients.value.findIndex(p => p.id === patient.id);
    patients.value[index] = data;
    editingPatientId.value = null;
  } catch (error) {
    console.error('Error saving patient:', error);
  }
};

const cancelEdit = () => {
  editingPatientId.value = null;
  fetchPatients(currentPage.value);
};

const deletePatient = async (id) => {
  try {
    const response = await fetch(`/api/patients/${id}`, {
      method: 'DELETE',
    });
    console.log('API request sent to /api/patients/' + id + ' with DELETE method');
    if (!response.ok) {
      console.error('Error deleting patient:', response.status, response.statusText);
      const errorText = await response.text();
      console.error('Error response text:', errorText);
      return;
    }
    patients.value = patients.value.filter(p => p.id !== id);
  } catch (error) {
    console.error('Error deleting patient:', error);
  }
};

const searchPatients = () => {
  fetchPatients(currentPage.value);
};

const filteredPatients = computed(() => {
  return patients.value.filter(patient =>
    patient.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

onMounted(() => {
  console.log('Component mounted, fetching patients...');
  fetchPatients(currentPage.value);
});
</script>

<style scoped>
/* Add your styles here */
</style>