<template>
  <app-layout>
    <v-row
      :align="'center'"
      :justify="'center'"
      :style="{ display: alert ? '' : 'none' }"
    >
      <v-col md="4">
        <v-alert :type="type" :value="alert" transition="fade-transition">
          {{ message }}
        </v-alert>
      </v-col>
    </v-row>
  </app-layout>
</template>

<script>
import AppLayout from "../../Layouts/App.vue";
import { Link } from "@inertiajs/inertia-vue";

export default {
    components: {
        AppLayout,
        Link,
    },

    props: {
        type: String,
        message: String,
    },

    methods: {
        showAlert(type, msg) {
            this.alertType = type;
            this.alertMessage = msg;
            this.alert = true;

            setTimeout(() => {
                this.alert = false;
                this.alertType = "success";
                this.alertMessage = "";
            }, 10000);
        },
    }
}
</script>