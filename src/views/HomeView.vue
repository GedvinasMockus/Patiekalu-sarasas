
<template>
    <v-container class="fill-height">
        <LoadingDialog v-model="loading"/>

        <v-row>
            <v-col v-if="token" md="8" offset-md="2" lg="6" offset-lg="3" xl="4" offset-xl="4">
                <v-card elevation="16" min-width="200px" color="blue-lighten-1">
                    <v-card-title>
                        <div class="text-h3 text-center">Welcome</div>
                    </v-card-title>
                    <v-divider></v-divider>
                    <div class="text-center ">
                        <v-btn color="blue-lighten-5" class="text-capitalize" :to="{name: 'restaurants'}" size="large" :active="false">See Restaurant List</v-btn>
                    </div>
                </v-card>
            </v-col>
            <v-col v-else md="8" offset-md="2" lg="6" offset-lg="3" xl="4" offset-xl="4">
                <v-card elevation="16" min-width="200px" color="blue-lighten-1">
                    <v-card-title>
                        <div class="text-h3 text-center">Please Login to use the website.</div>
                    </v-card-title>
                    <v-divider></v-divider>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { ref } from 'vue'
import axios from 'axios'
import LoadingDialog from '../components/LoadingDialog.vue'
import CloseButton from '../components/CloseButton.vue'
import { POSITION, useToast } from 'vue-toastification'
export default {
    props: ['token'],
    components: {
        LoadingDialog
    },
    setup() {
        const toast = useToast()

        const cities = ref([])
        const loading = ref(false)
        return { cities, loading }
    }
}
</script>

<style>

</style>