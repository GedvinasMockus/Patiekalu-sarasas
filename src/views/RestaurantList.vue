<template>
    <v-container v-if="token" align-start>
        <LoadingDialog v-model="loading"/>

        <v-col v-if="!loading && claims.role === 'Owner'" md="8" offset-md="2" lg="6" offset-lg="3" xl="2" offset-xl="5" class="mb-5 mt-5">
            <div class="text-center ">
                <v-btn color="blue-lighten-1" class="text-capitalize" @click.stop.prevent="createRestaurant()" size="large" :active="false">Create new restaurant</v-btn>
            </div>
        </v-col>

        <v-row v-if="!loading && !empty" class="mt-5">
            <v-col v-if="!loading" class="pt-5 pb-5" v-for="restaurant in restaurants" lg="3" md="6" cols="12" :key="restaurant.id">
                <v-card v-if="restaurant.status == 'Approved' || restaurant.owner == claims.sub || claims.role == 'Admin'" variant="flat" color="blue-lighten-1" :to="{name: 'menus', 'params': { rid: restaurant.id }}" hover>
                    <v-card-title class="text-lighten-5">
                        <v-row>
                            <v-col cols="9">
                                <v-icon icon="mdi-silverware"></v-icon>
                                {{ restaurant.name }}
                            </v-col>
                            <v-col cols="3" class="text-right">
                                <v-btn v-if="restaurant.owner == claims.sub || claims.role === 'Admin'" color="blue-lighten-5" class="mr-2" :size="x-small" @click.stop.prevent="updateRestaurant(restaurant.id)">
                                    <v-icon icon="mdi-note-edit" color="blue-lighten-1" :size="small"></v-icon>
                                </v-btn>
                                <v-btn v-if="restaurant.owner == claims.sub || claims.role === 'Admin'" color="blue-lighten-5" :size="x-small" @click.stop.prevent="deleteRestaurant(restaurant.id)">
                                    <v-icon icon="mdi-delete" color="blue-lighten-1" :size="small"></v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>

        <v-col v-if="empty" md="8" offset-md="2" lg="6" offset-lg="3" xl="4" offset-xl="4">
                <v-card elevation="16" min-width="200px" color="blue-lighten-1">
                    <v-card-title>
                        <div class="text-h3 text-center">Found no restaurants!</div>
                    </v-card-title>
                    <v-divider></v-divider>
                </v-card>
        </v-col>

    </v-container>

    <v-dialog v-model="deleteModal" max-width="500px">
        <v-card variant="flat" color="blue-lighten-1">
            <v-card-title>
                <span class="headline">Confirm Deletion</span>
            </v-card-title>
            <v-card-text>
                <p>Are you sure you want to delete this restaurant?</p>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn variant="elevated" color="blue-lighten-5" @click="deleteModalSubmit">
                    Yes
                </v-btn>
                <v-btn variant="elevated" color="blue-lighten-5" @click="deleteModalClose">
                    Close
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog v-model="createModal" max-width="500px">
        <v-card variant="flat" color="blue-lighten-1">
            <v-card-title>
                <span class="headline">Create Restaurant</span>
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="createModalSubmit">
                    <v-text-field v-model="data.name" class="my-2" type="text" label="Name" color="blue-lighten-5" variant="outlined" :error-messages="errors.name"></v-text-field>
                    <v-btn type="submit" block class="text-capitalize" color="blue-lighten-5" variant="elevated" :loading="submitting" :disabled="submitting">Create</v-btn>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn variant="elevated" color="blue-lighten-5" @click="createModalClose">
                    Close
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog v-model="updateModal" max-width="500px">
        <v-card variant="flat" color="blue-lighten-1">
            <v-card-title>
                <span class="headline">Update Restaurant</span>
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="updateModalSubmit">
                    <v-text-field v-model="data.name" class="my-2" type="text" label="Name" color="blue-lighten-5" variant="outlined" :error-messages="errors.name"></v-text-field>
                    <v-select v-if="claims.role === 'Admin'" v-model="data.status" class="my-2" :items="['Submitted', 'Approved', 'Denied']" type="text" label="Status" color="blue-lighten-1" variant="outlined"></v-select>
                    <v-select v-else v-model="data.status" :items="['Submitted', 'Approved', 'Denied']" class="my-2" type="text" label="Status" color="blue-lighten-1" variant="outlined" :disabled="true"></v-select>
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
                status: '',
                owner: ''
        })

        const toast = useToast()
        const restaurants = ref([])
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

        const fetchRestaurants = () => {
            axios.get(`${process.env.VUE_APP_API}/restaurant`)
                .then((res) => {
                    restaurants.value = res.data
                    loading.value = false

                    if(restaurants.value.length > 0)
                        empty.value = false
                    else
                        empty.value = true

                    if(claims.value.role == 'Regular')
                    {
                        if(!restaurants.value.some(restaurant => restaurant.status === 'Approved'))
                        {
                            empty.value = true
                        }
                    }
                }).catch((err) => {
                    if(err.response?.status === 401){
                        axios.post(`${process.env.VUE_APP_API}/refresh`)
                            .then((res) => {
                                const accessToken = res.data.data.token
                                context.emit('refresh', accessToken)
                                fetchRestaurants()
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

        const createModalSubmit = () => {
                submitting.value = true
                data.owner = claims.value.sub
                data.status = "Submitted"
                axios.post(`${process.env.VUE_APP_API}/restaurant`, data)
                    .then(() => {
                        errors.value = {}
                        submitting.value = false
                        createModal.value = false
                        toast.info('Successfully created a new restaurant', {
                            position: POSITION.TOP_CENTER,
                            timeout: 3000,
                            closeButton: CloseButton
                        })
                        fetchRestaurants()
                    }).catch((err) => {
                        if(err.response?.status !== 201)
                        {
                            submitting.value = false
                            errors.value = err.response.data.data
                        }
                        else console.log(err)

                        console.log(err.response?.status)
                    })
        }

        const deleteRestaurant = (index) => {
            deleteModal.value = true;
            toDelete = index
        }

        const deleteModalClose = () => {
            deleteModal.value = false;
        }

        const deleteModalSubmit = () => {
                axios.delete(`${process.env.VUE_APP_API}/restaurant/${toDelete}`)
                    .then(() => {
                        toast.info('Successfully deleted the restaurant', {
                            position: POSITION.TOP_CENTER,
                            timeout: 3000,
                            closeButton: CloseButton
                        })
                    }).catch((err) => {
                        if(err.response?.status === 404) {
                            toast.error('Restaurant not found', {
                                position: POSITION.TOP_CENTER,
                                timeout: 3000,
                                closeButton: CloseButton
                            })
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

            submitting.value = false
            deleteModal.value = false;
            fetchRestaurants()
        }

        const createRestaurant = () => {
            createModal.value = true;
            data.name = ''
            data.owner = ''
            data.status = ''
        }

        const createModalClose = () => {
            createModal.value = false;
        }

        const updateRestaurant = (index) => {
            updateModal.value = true;
            toDelete = index
            console.log("Restaurants")
            console.log(restaurants.value)
            var res = restaurants.value.find(restaurant => restaurant.id === index)
            data.name = res.name
            data.status = res.status
            data.owner = res.owner
        }

        const updateModalClose = () => {
            updateModal.value = false;
        }

        const updateModalSubmit = () => {
            console.log(data)
            axios.put(`${process.env.VUE_APP_API}/restaurant/${toDelete}`, data)
                    .then(() => {
                        toast.info('Successfully updated the restaurant', {
                            position: POSITION.TOP_CENTER,
                            timeout: 3000,
                            closeButton: CloseButton
                        })
                        updateModal.value = false;
                        submitting.value = false
                        fetchRestaurants()
                    }).catch((err) => {
                        if(err.response?.status === 404) {
                            toast.error('Restaurant not found', {
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

            fetchRestaurants()
        }

        
        onMounted(() => {
            fetchRestaurants() // Initial fetch of restaurants
        })

        return { restaurants, loading, empty, claims, errors, submitting, data, deleteRestaurant, deleteModalClose, deleteModalSubmit, createRestaurant, createModalClose, createModalSubmit, updateRestaurant, updateModalClose, updateModalSubmit, deleteModal, createModal, updateModal }
    }
}
</script>

<style>
.blue-lighten-5--text {
  background-color: var(--v-blue-lighten-5);
}
</style>