<template>
    <div>
      <h1>Homeowner Import</h1>
  
      <!-- File input field -->
      <input type="file" ref="fileInput" @change="handleFileChange" />
      
      <!-- Display results -->
      <div v-if="responseData">
        <h2>CSV Data</h2>
        <pre>{{ responseData }}</pre>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const fileInput = ref(null);
  const responseData = ref(null);
  
  const handleFileChange = async (event) => {
    const file = event.target.files[0];
    console.log(file);
  
    if (file) {
      const formData = new FormData();
      formData.append('file', file);
  
      try {
        const response = await fetch('http://localhost:8000/api/csv/upload', {
          method: 'POST',
          body: formData,
        });
        if (!response.ok) {
          throw new Error('File upload failed');
        }
  


        const data = await response.json();
        
        // Handle the response (e.g., display the CSV data)
        responseData.value = data;
        console.log(data);
      } catch (error) {
        console.error('Error uploading the file:', error);
      }
    }
  };
  </script>
  