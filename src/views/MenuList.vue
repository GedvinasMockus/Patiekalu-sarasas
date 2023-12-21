<template>
    <v-container v-if="token">
        <LoadingDialog v-model="loading"/>

        <v-col v-if="!loading && isUserOwner" md="8" offset-md="2" lg="6" offset-lg="3" xl="2" offset-xl="5" class="mb-5 mt-5">
            <div class="text-center ">
                <v-btn color="blue-lighten-1" class="text-capitalize" @click.stop.prevent="createMenu()" size="large" :active="false">Create new menu</v-btn>
            </div>
        </v-col>

        <v-row v-if="!loading && !empty" class="mt-8">
            <v-col class="pt-2 pb-2" v-for="menu in menus" lg="4" md="6" cols="12" :key="menu.id">
                <v-card variant="flat" color="blue-lighten-1" :to="{name: 'dishes', 'params': { rid: rid, mid: menu.id }}" hover>
                    <v-card-title class="text-lighten-5">
                        <v-row>
                            <v-col cols="9">
                                <v-icon icon="mdi-silverware"></v-icon>
                                {{ menu.name }}
                            </v-col>
                            <v-col cols="3" class="text-right">
                                <v-btn v-if="isUserOwner || claims.role === 'Admin'" color="blue-lighten-5" class="mr-2" :size="x-small" @click.stop.prevent="updateMenu(menu.id)">
                                    <v-icon icon="mdi-note-edit" color="blue-lighten-1" :size="small"></v-icon>
                                </v-btn>
                                <v-btn v-if="isUserOwner || claims.role === 'Admin'" color="blue-lighten-5" :size="x-small" @click.stop.prevent="deleteMenu(menu.id)">
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
                        <div class="text-h3 text-center">Found no dishes for this menu!</div>
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
                <p>Are you sure you want to delete this menu?</p>
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
                <span class="headline">Create Menu</span>
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="createModalSubmit">
                    <v-text-field v-model="data.name" class="my-2" type="text" label="Name" color="blue-lighten-5" variant="outlined" :error-messages="errors.name"></v-text-field>
                    <v-textarea v-model="data.description" class="my-2" type="text" label="Description" color="blue-lighten-1" variant="outlined"  :error-messages="errors.description"></v-textarea>
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
                <span class="headline">Update Menu</span>
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="updateModalSubmit">
                    <v-text-field v-model="data.name" class="my-2" type="text" label="Name" color="blue-lighten-5" variant="outlined" :error-messages="errors.name"></v-text-field>
                    <v-textarea v-model="data.description" class="my-2" type="text" label="Description" color="blue-lighten-1" variant="outlined"></v-textarea>
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
    props: ['rid', 'token'],
    components: {
        LoadingDialog
    },
    setup(props, context){

        const data = reactive({
                name: '',
                description: '',
                restaurant: ''
        })

        const toast = useToast()
        const menus = ref({})
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

        const fetchMenus = () => {
            axios.get(`${process.env.VUE_APP_API}/restaurant/${props.rid}/menu`)
                .then((res) => {
                    menus.value = res.data
                    loading.value = false

                    if(menus.value.length > 0)
                        empty.value = false
                    else
                        empty.value = true
                }).catch((err) => {
                    if(err.response?.status === 401){
                        axios.post(`${process.env.VUE_APP_API}/refresh`)
                            .then((res) => {
                                const accessToken = res.data.data.token
                                context.emit('refresh', accessToken)
                                fetchMenus()
                            }).catch((err) => {

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
                data.restaurant = props.rid
                axios.post(`${process.env.VUE_APP_API}/restaurant/${props.rid}/menu`, data)
                    .then(() => {
                        errors.value = {}
                        submitting.value = false
                        createModal.value = false
                        toast.info('Successfully created a new menu', {
                            position: POSITION.TOP_CENTER,
                            timeout: 3000,
                            closeButton: CloseButton
                        })
                        fetchMenus()
                    }).catch((err) => {
                        if(err.response?.status !== 201)
                        {
                            submitting.value = false
                            errors.value = err.response.data.data
                        }
                        else console.log(err)

                        console.log(err.response?.status)
                    })

            fetchMenus()
        }

        const deleteMenu = (index) => {
            deleteModal.value = true;
            toDelete = index
        }

        const deleteModalClose = () => {
            deleteModal.value = false;
        }

        const deleteModalSubmit = () => {
                axios.delete(`${process.env.VUE_APP_API}/restaurant/${props.rid}/menu/${toDelete}`)
                    .then(() => {
                        toast.info('Successfully deleted the menu', {
                            position: POSITION.TOP_CENTER,
                            timeout: 3000,
                            closeButton: CloseButton
                        })
                        fetchMenus()
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
            fetchMenus()
        }

        const createMenu = () => {
            createModal.value = true;
            data.name = ''
            data.owner = ''
            data.status = ''
        }

        const createModalClose = () => {
            createModal.value = false;
        }

        const updateMenu = (index) => {
            updateModal.value = true;
            toDelete = index
            var res = menus.value.find(menu => menu.id === index)
            data.name = res.name
            data.description = res.description
            data.restaurant = res.restaurant
        }

        const updateModalClose = () => {
            updateModal.value = false;
        }

        const updateModalSubmit = () => {
            axios.put(`${process.env.VUE_APP_API}/restaurant/${props.rid}/menu/${toDelete}`, data)
                    .then(() => {
                        toast.info('Successfully updated the restaurant', {
                            position: POSITION.TOP_CENTER,
                            timeout: 3000,
                            closeButton: CloseButton
                        })
                        updateModal.value = false;
                        submitting.value = false
                        fetchMenus()
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

            fetchMenus()
        }

        onMounted(() => {
            isOwner()
            fetchMenus() // Initial fetch of restaurants
        })

        return { menus, loading, empty, claims, data, errors, isUserOwner, deleteMenu, deleteModalClose, deleteModalSubmit, createMenu, createModalClose, createModalSubmit, updateMenu, updateModalClose, updateModalSubmit, deleteModal, createModal, updateModal }
    }
}
</script>

<style>

</style>