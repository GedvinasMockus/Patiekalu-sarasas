<template>
    <v-container v-if="token" align-start>
        <LoadingDialog v-model="loading"/>

        <v-row v-if="!loading" class="mt-5">
            <v-col v-if="!loading" class="pt-5 pb-5" v-for="user in users" lg="3" md="6" cols="12" :key="user.id">
                <v-card variant="flat" color="blue-lighten-1">
                    <v-card-title class="text-lighten-5">
                        <v-row>
                            <v-col cols="9">
                                <v-icon icon="mdi-account-outline"></v-icon>
                                {{ user.name }}
                            </v-col>
                            <v-col cols="3" class="text-right">
                                <v-btn color="blue-lighten-5" class="mr-2" :size="x-small" @click.stop.prevent="updateUser(user.id)">
                                    <v-icon icon="mdi-note-edit" color="blue-lighten-1" :size="small"></v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>

        <v-row v-if="empty" md="8" offset-md="2" lg="6" offset-lg="3" xl="4" offset-xl="4">
                <v-card elevation="16" min-width="200px" color="blue-lighten-1">
                    <v-card-title>
                        <div class="text-h3 text-center">Found no users!</div>
                    </v-card-title>
                    <v-divider></v-divider>
                </v-card>
        </v-row>

    </v-container>

    <v-dialog v-model="updateModal" max-width="500px">
        <v-card variant="flat" color="blue-lighten-1">
            <v-card-title>
                <span class="headline">Update User</span>
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="updateModalSubmit">
                    <v-text-field v-model="data.name" class="my-2" type="text" label="Name" color="blue-lighten-5" variant="outlined" :disabled="true"></v-text-field>
                    <v-text-field v-model="data.surname" class="my-2" type="text" label="Name" color="blue-lighten-5" variant="outlined" :disabled="true"></v-text-field>
                    <v-text-field v-model="data.email" class="my-2" type="text" label="Name" color="blue-lighten-5" variant="outlined" :disabled="true"></v-text-field>
                    <v-select v-model="data.role" class="my-2" :items="['Regular', 'Owner', 'Admin']" type="text" label="Status" color="blue-lighten-1" variant="outlined"></v-select>

                    <v-btn type="submit" block class="text-capitalize" color="blue-lighten-5" variant="elevated" :loading="submitting" :disabled="submitting">Update</v-btn>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn variant="elevated" color="blue-lighten-5" @click="updateModalClose">
                    Close
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { computed, reactive, ref, onMounted } from 'vue'
import axios from 'axios'
import LoadingDialog from '../components/LoadingDialog.vue'
import CloseButton from '../components/CloseButton.vue'
import { jwtDecode } from 'jwt-decode'
import { POSITION, useToast } from 'vue-toastification'
import router from '@/router'
export default {
    props: ['token'],
    components: {
        LoadingDialog
    },
    setup(props, context){

        const data = reactive({
                name: '',
                surname: '',
                email: '',
                role: ''
        })

        const toast = useToast()
        const users = ref([])
        const loading = ref(true)
        const empty = ref(false)
        const deleteModal = ref(false)
        const createModal = ref(false)
        const updateModal = ref(false)
        const submitting = ref(false)
        const errors = ref({})
        var toDelete

        const claims = computed(() => {
            if(props.token)
                return jwtDecode(props.token)
        })

        const fetchUsers = () => {
            axios.get(`${process.env.VUE_APP_API}/users`)
                .then((res) => {
                    users.value = res.data
                    loading.value = false
                }).catch((err) => {
                    if(err.response?.status === 401){
                        axios.post(`${process.env.VUE_APP_API}/refresh`)
                            .then((res) => {
                                const accessToken = res.data.data.token
                                context.emit('refresh', accessToken)
                                fetchUsers()
                            }).catch((err) => {
                                console.log(err)
                            })
                    }
                    else if(err.response?.status === 404)
                    {
                        empty.value = true
                        loading.value = false
                    }
                    if(!err.response) {
                        toast.error('Server is currently offline', {
                            position: POSITION.TOP_CENTER,
                            timeout: 3000,
                            closeButton: CloseButton
                        })
                    }
                    else console.log(err)
                })
        }

        const updateUser = (index) => {
            updateModal.value = true;
            toDelete = index
            var res = users.value.find(user => user.id === index)
            data.name = res.name
            data.surname = res.surname
            data.email = res.email
            data.role = res.role
        }

        const updateModalClose = () => {
            updateModal.value = false;
        }

        const updateModalSubmit = () => {
            console.log(data)
            axios.put(`${process.env.VUE_APP_API}/users/${toDelete}`, data)
                    .then(() => {
                        toast.info('Successfully updated the user', {
                            position: POSITION.TOP_CENTER,
                            timeout: 3000,
                            closeButton: CloseButton
                        })
                        updateModal.value = false;
                        submitting.value = false
                    }).catch((err) => {
                        if(err.response?.status === 404) {
                            toast.error('User not found', {
                                position: POSITION.TOP_CENTER,
                                timeout: 3000,
                                closeButton: CloseButton
                            })
                        }
                        else if(err.response?.status === 400)
                        {
                            submitting.value = false
                            errors.value = err.response.data.data
                        }
                        else if(!err.response) {
                            toast.error('Server is currently offline', {
                                position: POSITION.TOP_CENTER,
                                timeout: 3000,
                                closeButton: CloseButton
                            })
                        }
                        else console.log(err)
                    })

            fetchUsers()
        }

        
        onMounted(() => {
            fetchUsers()
        })

        return { users, loading, empty, claims, errors, submitting, data, updateUser, updateModalClose, updateModalSubmit, createModal, updateModal }
    }
}
</script>

<style>
.blue-lighten-5--text {
  background-color: var(--v-blue-lighten-5);
}
</style>