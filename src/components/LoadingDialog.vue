<template>
  <v-dialog v-model="show" min-width="150px" width="200px" persistent :scrim="false">
    <v-card class="text-center " color="blue-lighten-1">
        <v-card-text>
            <div class="text-h5">Loading...</div>
            <!-- <v-progress-linear indeterminate color="blue-gry-darken-3"></v-progress-linear> -->
            <v-progress-circular
                indeterminate
                color="blue-lighten-5"
                ></v-progress-circular>
        </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import { computed, ref, watch } from 'vue'
export default {
    props: ['modelValue'],
    setup(props, context){
        const show = computed(() => {
            return props.modelValue
        }, (value) => {
            context.emit('input', value)
        })
        return { show }
    }
}
</script>

<style>

</style>