<template>

</template>

<script setup>
const addDoctor = async () => {
  const newDoctor = { name: 'New Doctor', specialization: 'New Specialization' };
  try {
    const response = await fetch('/api/doctors', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(newDoctor),
    });
    if (!response.ok) {
      console.error('Error adding doctor:', response.status, response.statusText);
      const errorText = await response.text();
      console.error('Error response text:', errorText);
      return;
    }
    const data = await response.json();
    doctors.value.push(data);
  } catch (error) {
    console.error('Error adding doctor:', error);
  }
};
</script>

<style scoped>

</style>