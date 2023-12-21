<template>
    <v-container v-if="token">
        <LoadingDialog v-model="loading"/>

        <v-col v-if="!loading && isUserOwner" md="8" offset-md="2" lg="6" offset-lg="3" xl="2" offset-xl="5" class="mb-5 mt-5">
            <div class="text-center ">
                <v-btn color="blue-lighten-1" class="text-capitalize" @click.stop.prevent="createDish()" size="large" :active="false">Create new dish</v-btn>
            </div>
        </v-col>

        <v-row v-if="!loading && !empty" class="mt-8">
            <v-col class="pt-2 pb-2" v-for="dish in dishes" cols="12" :key="dish.id">
                <v-card variant="flat" color="blue-lighten-1">
                    <v-card-title class="text-lighten-5">
                        <div style="text-align: center; font-size: 40px;">
                            {{ dish.name }}
                        </div>
                        <v-row>
                            <v-col md="3">
                                <div class="column-content">
                                    <img :src="dish.picture" alt="Image" style="max-width: 260px; max-height: 200px" />
                                </div>
                            </v-col>
                            <v-col md="8">
                                <div style="display: flex; align-items: center; height: 200px;">
                                    <div style="margin-left: 10px;">
                                        {{ dish.description }}
                                    </div>
                                </div>
                            </v-col>
                            <v-col md="1" class="text-right">
                                <div style="display: flex; align-items: center; height: 200px;">
                                    <v-btn v-if="isUserOwner || claims.role === 'Admin'" color="blue-lighten-5" class="mr-2" :size="x-small" @click.stop.prevent="updateDish(dish.id)">
                                        <v-icon icon="mdi-note-edit" color="blue-lighten-1" :size="small"></v-icon>
                                    </v-btn>
                                    <v-btn v-if="isUserOwner || claims.role === 'Admin'" color="blue-lighten-5" :size="x-small" @click.stop.prevent="deleteDish(dish.id)">
                                        <v-icon icon="mdi-delete" color="blue-lighten-1" :size="small"></v-icon>
                                    </v-btn>
                                </div>
                            </v-col>
                        </v-row>
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>

        <v-col v-if="empty" md="8" offset-md="2" lg="6" offset-lg="3" xl="4" offset-xl="4" style="text-align: center;">
                <v-card elevation="16" min-width="200px" color="blue-lighten-1" style="display: flex; align-items: center;">
                    <v-card-title>
                        <div class="text-h3 text-center" >Found no dishes for this menu!</div>
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
                <p>Are you sure you want to delete this dish?</p>
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
                <span class="headline">Create Dish</span>
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="createModalSubmit">
                    <v-text-field v-model="data.name" class="my-2" type="text" label="Name" color="blue-lighten-5" variant="outlined" :error-messages="errors.name"></v-text-field>
                    <v-textarea v-model="data.description" class="my-2" type="text" label="Description" color="blue-lighten-1" variant="outlined"  :error-messages="errors.description"></v-textarea>
                    <v-text-field v-model="data.picture" class="my-2" type="text" label="Link" color="blue-lighten-5" variant="outlined" :error-messages="errors.picture"></v-text-field>
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
                <span class="headline">Update Dish</span>
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="updateModalSubmit">
                    <v-text-field v-model="data.name" class="my-2" type="text" label="Name" color="blue-lighten-5" variant="outlined" :error-messages="errors.name"></v-text-field>
                    <v-textarea v-model="data.description" class="my-2" type="text" label="Description" color="blue-lighten-1" variant="outlined"  :error-messages="errors.description"></v-textarea>
                    <v-text-field v-model="data.picture" class="my-2" type="text" label="Link" color="blue-lighten-5" variant="outlined" :error-messages="errors.picture"></v-text-field>
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
import { computed, nextTick, reactive, ref, onMounted } from 'vue'
import axios from 'axios'
import LoadingDialog from '../components/LoadingDialog.vue'
import CloseButton from '../components/CloseButton.vue'
import { jwtDecode } from 'jwt-decode'
import { POSITION, useToast } from 'vue-toastification'
import router from '@/router'
export default {
    props: ['rid', 'mid', 'token'],
    components: {
        LoadingDialog
    },
    setup(props, context){

        const data = reactive({
                name: '',
                description: '',
                picture: ''
        })

        const toast = useToast()
        const dishes = ref([])
        const loading = ref(true)
        const empty = ref(false)
        const deleteModal = ref(false)
        const createModal = ref(false)
        const updateModal = ref(false)
        const submitting = ref(false)
        const errors = ref({})
        const isUserOwner = ref(false)
        var toDelete


        const claims = computed(() => {
            if(props.token)
                return jwtDecode(props.token)
        })

        const isOwner = () => {
            axios.get(`${process.env.VUE_APP_API}/restaurant/${props.rid}`)
                .then((res) => {
                    if(res.data.owner == claims.value.sub)
                    {
                        isUserOwner.value = true;
                    }
                    else
                    {
                        isUserOwner.value = false;
                    }
                }).catch((err) => {
                    if(err.response?.status === 404)
                    {
                        toast.error('Restaurant not found', {
                            position: POSITION.TOP_CENTER,
                            timeout: 3000,
                            closeButton: CloseButton
                        })
                    }
                    if(!err.response) {
                        toast.error('Server is currently offline', {
                            position: POSITION.TOP_CENTER,
                            timeout: 3000,
                            closeButton: CloseButton
                        })
                    }
                    else console.log(err)

                    isUserOwner.value = false;
                })
        }

        const fetchDishes = () => {
            axios.get(`${process.env.VUE_APP_API}/restaurant/${props.rid}/menu/${props.mid}/dish`)
                .then((res) => {
                    console.log("Got it")
                    console.log(res.data)
                    dishes.value = res.data
                    loading.value = false

                    if(dishes.value.length > 0)
                        empty.value = false
                    else
                        empty.value = true

                }).catch((err) => {
                    if(err.response?.status === 401){
                        axios.post(`${process.env.VUE_APP_API}/refresh`)
                            .then((res) => {
                                const accessToken = res.data.data.token
                                context.emit('refresh', accessToken)
                                fetchDishes()
                            }).catch((err) => {

                            })
                    }
                    else if(err.response?.status === 404)
                    {
                        empty.value = true
                        loading.value = false
                    }
                    console.log("Response")
                    console.log(err.response)
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
                data.restaurant = props.rid
                axios.post(`${process.env.VUE_APP_API}/restaurant/${props.rid}/menu/${props.mid}/dish`, data)
                    .then(() => {
                        errors.value = {}
                        submitting.value = false
                        createModal.value = false
                        toast.info('Successfully created a new menu', {
                            position: POSITION.TOP_CENTER,
                            timeout: 3000,
                            closeButton: CloseButton
                        })
                        fetchDishes()
                    }).catch((err) => {
                        if(err.response?.status !== 201)
                        {
                            submitting.value = false
                            errors.value = err.response.data.data
                        }
                        else console.log(err)

                        console.log(err.response?.status)
                    })
            fetchDishes()
        }

        const deleteDish = (index) => {
            deleteModal.value = true;
            toDelete = index
        }

        const deleteModalClose = () => {
            deleteModal.value = false;
        }

        const deleteModalSubmit = () => {
                axios.delete(`${process.env.VUE_APP_API}/restaurant/${props.rid}/menu/${props.mid}/dish/${toDelete}`)
                    .then(() => {
                        toast.info('Successfully deleted the dish', {
                            position: POSITION.TOP_CENTER,
                            timeout: 3000,
                            closeButton: CloseButton
                        })
                        fetchDishes()
                    }).catch((err) => {
                        if(err.response?.status === 404) {
                            toast.error('Dish not found', {
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
            fetchDishes()
        }

        const createDish = () => {
            createModal.value = true;
            data.name = ''
            data.description = ''
            data.picture = ''
        }

        const createModalClose = () => {
            createModal.value = false;
        }

        const updateDish = (index) => {
            updateModal.value = true;
            toDelete = index
            var res = dishes.value.find(dish => dish.id === index)
            data.name = res.name
            data.description = res.description
            data.picture = res.picture
        }

        const updateModalClose = () => {
            updateModal.value = false;
        }

        const updateModalSubmit = () => {
            axios.put(`${process.env.VUE_APP_API}/restaurant/${props.rid}/menu/${props.mid}/dish/${toDelete}`, data)
                    .then(() => {
                        toast.info('Successfully updated the dish', {
                            position: POSITION.TOP_CENTER,
                            timeout: 3000,
                            closeButton: CloseButton
                        })
                        updateModal.value = false;
                        submitting.value = false
                        fetchDishes()
                    }).catch((err) => {
                        if(err.response?.status === 404) {
                            toast.error('Dish not found', {
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

            fetchDishes()
        }

        onMounted(() => {
            isOwner()
            fetchDishes() // Initial fetch of restaurants
        })

        return { dishes, loading, empty, claims, data, errors, isUserOwner, deleteDish, deleteModalClose, deleteModalSubmit, createDish, createModalClose, createModalSubmit, updateDish, updateModalClose, updateModalSubmit, deleteModal, createModal, updateModal }
    }
}
</script>

<style>

</style>