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
    <button @click="toggleAddPatientForm" class="mb-4 ml-4 p-2 bg-green-500 text-white rounded hover:bg-green-600">
      Add Patient
    </button>

    <div v-if="isAddingPatient" class="mb-6">
      <h4 class="font-semibold mb-2">Add New Patient</h4>
      <form @submit.prevent="submitPatientForm" enctype="multipart/form-data">
        <div class="mb-4">
          <label class="block text-gray-700">Name</label>
          <input v-model="newPatient.name" type="text" class="p-2 border rounded w-full" required />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700">Date of Birth</label>
          <input v-model="newPatient.date_of_birth" type="number" class="p-2 border rounded w-full" required />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700">Gender</label>
          <input v-model="newPatient.gender" type="text" class="p-2 border rounded w-full" required />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700">Address</label>
          <input v-model="newPatient.address" type="text" class="p-2 border rounded w-full" required />
        </div>
        <div class="flex space-x-4">
          <button type="submit" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
          <button type="button" @click="cancelAddPatient" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            Cancel
          </button>
        </div>
      </form>
    </div>


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
              <input v-model="patient.date_of_birth" class="p-2 border rounded" />
            </div>
            <div v-else>
              {{ patient.date_of_birth }}
            </div>
          </td>
          <td class="py-2">
            <div v-if="editingPatientId === patient.id">
              <input v-model="patient.gender" class="p-2 border rounded" />
            </div>
            <div v-else>
              {{ patient.gender }}
            </div>
          </td>
          <td class="py-2 address-cell">
            <div v-if="editingPatientId === patient.id">
              <input v-model="patient.address" class="p-2 border rounded" />
            </div>
            <div v-else>
              {{ patient.address }}
            </div>
          </td>
          <td class="py-2 actions-cell">
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
        @click="getPatients(currentPage - 1)"
        :disabled="currentPage === 1"
        class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
      >
        Previous
      </button>
      <span class="mx-4">{{ currentPage }} of {{ totalPages }}</span>
      <button
        @click="getPatients(currentPage + 1)"
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

    const patients = ref([]);
    const currentPage = ref(1);
    const totalPages = ref(1);
    const searchQuery = ref('');
    const editingPatientId = ref(null);
    const isAddingPatient = ref(false);

    const newPatient = ref({
      name: '',
      date_of_birth: '',
      gender: '',
      address: '',
    });

    const getPatients = async (page = 1) => {
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
        currentPage.value = data.current_page;
        totalPages.value = data.last_page;
        console.log('Invoices array:', patients.value); // Log the Invoices array
      } catch (error) {
        console.error('Error fetching patients:', error);
      }
    };

    const toggleAddPatientForm = () => {
      isAddingPatient.value = !isAddingPatient.value;
    };

    const submitPatientForm = async () => {
      const formData = new FormData();
      formData.append('name', newPatient.value.name);
      formData.append('date_of_birth', newPatient.value.date_of_birth);
      formData.append('gender', newPatient.value.gender);
      formData.append('address', newPatient.value.address);

      try {
        const response = await fetch ('/api/patients', {
          method: 'POST',
          body: formData,
        });

        if (!response.ok) {
          console.error('Error adding patient:', response.status, response.statusText);
          const errorText = await response.text();
          console.error('Error response text:', errorText);
          return;
        }

        const data = await response.json();
        patients.value.push(data);
        resetForm();
      } catch (error) {
        console.error('Error adding patient:', error);
      }
    }

    const resetForm = () => {
      newPatient.value.name = '';
      newPatient.value.date_of_birth = '';
      newPatient.value.gender = '';
      newPatient.value.address = '';
      isAddingPatient.value = false;
    };

    const cancelAddPatient = () => {
      resetForm();
    };

    const editPatient = (id) => {
      editingPatientId.value = id;
    };

    const savePatient = async (patient) => {
      try {
        console.log('Saving patient:', patient);

        const formData = new FormData();
        formData.append('name', patient.name);
        formData.append('date_of_birth', patient.date_of_birth);
        formData.append('gender', patient.gender);
        formData.append('address', patient.address);

        const response = await fetch(`/api/patients/${patient.id}`, {
          method: 'PUT',
          body: formData,
        });

        if (!response.ok) {
          console.error('Error saving patient:', response.status, response.statusText);
          const errorText = await response.text();
          console.error('Error response text:', errorText);
          return;
        }

        const data = await response.json();
        console.log('API Response:', data);

        const index = patients.value.findIndex(p => p.id === patient.id);
        if (index !== -1) {
          patients.value[index] = data;
        } else {
          patients.value.push(data);
        }

        editingPatientId.value = null;

        await getPatients(currentPage.value);
      } catch (error) {
        console.error('Error saving patient:', error);
      }
    };

    const cancelEdit = () => {
      editingPatientId.value = null;
      getPatients(currentPage.value);
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
      getPatients(currentPage.value);
    };

    const filteredPatients = computed(() => {
      return patients.value.filter(patient =>
        patient.name.toLowerCase().includes(searchQuery.value.toLowerCase())
      );
    });

    onMounted(() => getPatients(currentPage.value));
</script>

<style scoped>
.address-cell {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.actions-cell {
  width: 150px;
  text-align: left;
}
</style>