<template>
  <div>
    <h3 class="text-lg font-semibold mb-4">Manage Doctors</h3>
    <input
      v-model="searchQuery"
      @input="searchDoctors"
      type="text"
      placeholder="Search doctors..."
      class="mb-4 p-2 border rounded"
    />
    <button @click="toggleAddDoctorForm" class="mb-4 ml-4 p-2 bg-green-500 text-white rounded hover:bg-green-600">
      Add Doctor
    </button>

    <!-- Form for adding new doctor -->
    <div v-if="isAddingDoctor" class="mb-6">
      <h4 class="font-semibold mb-2">Add New Doctor</h4>
      <form @submit.prevent="submitDoctorForm" enctype="multipart/form-data">
        <div class="mb-4">
          <label class="block text-gray-700">Name</label>
          <input v-model="newDoctor.name" type="text" class="p-2 border rounded w-full" required />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700">Specialization</label>
          <input v-model="newDoctor.specialization" type="text" class="p-2 border rounded w-full" required />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700">Photo</label>
          <input @change="handleFileUpload" type="file" accept="image/*" class="p-2 border rounded w-full" required />
        </div>
        <div class="flex space-x-4">
          <button type="submit" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
          <button type="button" @click="cancelAddDoctor" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            Cancel
          </button>
        </div>
      </form>
    </div>
    <table class="min-w-full bg-white">
      <thead>
        <tr>
          <th class="py-2 text-left">Photo</th> <!-- New header for the photo -->
          <th class="py-2 text-left">Name</th>
          <th class="py-2 text-left">Specialization</th>
          <th class="py-2 text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="doctor in filteredDoctors" :key="doctor.id">
          <td class="py-2">
            <img 
              :src="doctor.photo" 
              alt="Doctor's Photo" 
              class="h-12 w-12 object-cover rounded-full" 
            />
          </td>
          <td class="py-2">
            <div v-if="editingDoctorId === doctor.id">
              <input v-model="doctor.name" class="p-2 border rounded" />
            </div>
            <div v-else>
              {{ doctor.name }}
            </div>
          </td>
          <td class="py-2">
            <div v-if="editingDoctorId === doctor.id">
              <input v-model="doctor.specialization" class="p-2 border rounded" />
            </div>
            <div v-else>
              {{ doctor.specialization }}
            </div>
          </td>
          <td class="py-2">
            <div v-if="editingDoctorId === doctor.id">
              <button @click="saveDoctor(doctor)" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Save
              </button>
              <button @click="cancelEdit" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600 ml-2">
                Cancel
              </button>
            </div>
            <div v-else>
              <button @click="editDoctor(doctor.id)" class="p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                Edit
              </button>
              <button @click="deleteDoctor(doctor.id)" class="p-2 bg-red-500 text-white rounded hover:bg-red-600 ml-2">
                Delete
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="mt-4">
      <button
        @click="fetchDoctors(currentPage - 1)"
        :disabled="currentPage === 1"
        class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
      >
        Previous
      </button>
      <span class="mx-2">Page {{ currentPage }} of {{ totalPages }}</span>
      <button
        @click="fetchDoctors(currentPage + 1)"
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

const doctors = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const searchQuery = ref('');
const editingDoctorId = ref(null);
const isAddingDoctor = ref(false);

// New doctor form data
const newDoctor = ref({
  name: '',
  specialization: '',
  photo: null,
});

const fetchDoctors = async (page = 1) => {
  try {
    const response = await fetch(`/api/doctors?page=${page}`);
    if (!response.ok) {
      console.error('Error fetching doctors:', response.status, response.statusText);
      const errorText = await response.text();
      console.error('Error response text:', errorText);
      return;
    }
    const data = await response.json();
    console.log('API response:', data); // Log the API response
    doctors.value = data.data; // Use data.data if paginated
    currentPage.value = data.current_page;
    totalPages.value = data.last_page;
    console.log('Doctors array:', doctors.value); // Log the doctors array
  } catch (error) {
    console.error('Error fetching doctors:', error);
  }
};

const toggleAddDoctorForm = () => {
  isAddingDoctor.value = !isAddingDoctor.value;
};

// Handle file upload
const handleFileUpload = (event) => {
  const file = event.target.files[0];
  newDoctor.value.photo = file; // Store the uploaded file
};

// Submit the form to add a new doctor
const submitDoctorForm = async () => {
  const formData = new FormData();
  formData.append('name', newDoctor.value.name);
  formData.append('specialization', newDoctor.value.specialization);
  formData.append('photo', newDoctor.value.photo); // Add the file

  try {
    const response = await fetch('/api/doctors', {
      method: 'POST',
      body: formData, // Send formData
    });

    if (!response.ok) {
      throw new Error('Error adding doctor');
    }

    const data = await response.json();
    doctors.value.push(data); // Add the new doctor to the list
    resetForm();
  } catch (error) {
    console.error('Error adding doctor:', error);
  }
};

// Reset the form after submission or cancel
const resetForm = () => {
  newDoctor.value.name = '';
  newDoctor.value.specialization = '';
  newDoctor.value.photo = null;
  isAddingDoctor.value = false;
};

// Cancel adding a doctor
const cancelAddDoctor = () => {
  resetForm();
};

const editDoctor = (id) => {
  editingDoctorId.value = id;
};

const saveDoctor = async (doctor) => {
  try {
    console.log('Saving doctor:', doctor); // Log the doctor being saved
    const response = await fetch(`/api/doctors/${doctor.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name: doctor.name,
        specialization: doctor.specialization
      }),
    });
    if (!response.ok) {
      console.error('Error saving doctor:', response.status, response.statusText);
      const errorText = await response.text();
      console.error('Error response text:', errorText);
      return;
    }
    const data = await response.json();
    console.log('Saved doctor response:', data); // Log the response from the server
    const index = doctors.value.findIndex(d => d.id === doctor.id);
    doctors.value[index] = data;
    editingDoctorId.value = null;
  } catch (error) {
    console.error('Error saving doctor:', error);
  }
};

const cancelEdit = () => {
  editingDoctorId.value = null;
  fetchDoctors(currentPage.value); // Re-fetch doctors to reset any unsaved changes
};

const deleteDoctor = async (id) => {
  try {
    await fetch(`/api/doctors/${id}`, {
      method: 'DELETE',
    });
    if (!response.ok) {
      console.error('Error deleting patient:', response.status, response.statusText);
      const errorText = await response.text();
      console.error('Error response text:', errorText);
      return;
    }
    doctors.value = doctors.value.filter(doctor => doctor.id !== id);
  } catch (error) {
    console.error('Error deleting doctor:', error);
  }
};

const searchDoctors = () => {
  fetchDoctors(currentPage.value);
};

const filteredDoctors = computed(() => {
  return doctors.value.filter(doctor =>
    doctor.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

onMounted(() => fetchDoctors(currentPage.value));
</script>

<style scoped>
/* Add any custom styles if needed */
</style>