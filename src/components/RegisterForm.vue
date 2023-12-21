<template>
<v-card elevation="16" min-width="200px" color="blue-lighten-1" >
    <v-card-title class="text-h3 text-center">Register</v-card-title>
    <v-divider></v-divider>
    <v-card-text>
        <v-form @submit.prevent="submit">
            <v-text-field v-model="data.name" class="my-2" type="text" label="Name" color="blue-lighten-5" variant="outlined" :error-messages="errors.name"></v-text-field>
            <v-text-field v-model="data.surname" class="mb-2" type="text" label="Surname" color="blue-lighten-5" variant="outlined" :error-messages="errors.surname"></v-text-field>
            <v-text-field v-model="data.email" class="mb-2" type="email" label="Email" color="blue-lighten-5" variant="outlined" :error-messages="errors.email"></v-text-field>
            <v-text-field v-model="data.password" class="mb-2" type="password" label="Password" color="blue-lighten-5" variant="outlined" :error-messages="errors.password"></v-text-field>
            <v-text-field v-model="data.c_password" type="password" class="mt-2" label="Confirm password" color="blue-lighten-5" variant="outlined" :error-messages="errors.c_password" ></v-text-field>
            <v-btn type="submit" block class="text-capitalize" color="blue-lighten-5" variant="elevated" :loading="submitting" :disabled="submitting">Register</v-btn>
        </v-form>
    </v-card-text>
</v-card>
</template>
<script>
import { reactive, ref } from 'vue'
import axios from "axios"
import router from '@/router'
export default { 
    props: ['statuses'],
    setup(props, context){
        const data = reactive({
            name: '',
            surname: '',
            email: '',
            password: '',
            c_password: ''
        })
        const submitting = ref(false)
        const errors = ref({})
        const submit = () => {
            submitting.value = true
            axios.post(`${process.env.VUE_APP_API}/register`, data)
                .then(() => {
                    errors.value = {}
                    submitting.value = false
                    context.emit('register')
                }).catch((err) => {
                    data.password = ''
                    data.c_password = ''
                    if(err.response?.status !== 201)
                    {
                        submitting.value = false
                        errors.value = err.response.data.data
                    }
                    else console.log(err)
                })
        }
        return { data, submitting, errors, submit }
    }
}
</script>

<style>

</style>