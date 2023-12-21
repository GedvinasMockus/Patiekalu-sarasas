<template>
    <v-card elevation="16" min-width="200px" color="blue-lighten-1">
        <v-card-title>
            <div class="text-h3 text-center">Login</div>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-form @submit.prevent="submit">
                <v-text-field v-model="data.email" type="email" class="my-2" label="email" :error-messages="errors.email"></v-text-field>
                <v-text-field v-model="data.password" type="password" class="mb-2" label="password" :error-messages="errors.password"></v-text-field>
                <v-btn type="submit" block class="text-capitalize" color="blue-lighten-5" variant="elevated" :loading="submitting" :disabled="submitting">Login</v-btn>
            </v-form>
        </v-card-text>
    </v-card>
</template>

<script>
import { reactive, ref } from 'vue'
import axios from 'axios'
export default {
    setup(props, context){
        const data = reactive({
            email: '',
            password: ''
        })
        const submitting = ref(false)
        const errors = ref({})
        const submit = () => {
            submitting.value = true
            axios.post(`${process.env.VUE_APP_API}/login`, data)
                .then((res) => {
                    errors.value = {}
                    console.log(res.data)
                    const accessToken = res.data.data.token
                    context.emit('login', accessToken)
                }).catch((err) => {
                    data.password = ''
                    errors.value = ''
                    submitting.value = false
                    console.log(err.response.data)
                    if(err.response?.status === 422)
                        inputErrors.value = err.response.data.data
                    else if(err.response?.status === 401)
                        context.emit('error', err.response.data.message)
                    else if(!err.response)
                        context.emit('error', 'Server is currently offline')
                    else console.log(err)
                })
        }
        return {data, submitting, errors, submit}
    }
}
</script>

<style>

</style>