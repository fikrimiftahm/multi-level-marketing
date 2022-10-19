<template>
  <app-layout>
    <v-container class="my-5">
      <v-row
        :align="'center'"
        :justify="'center'"
        :style="{ display: alertType ? '' : 'none' }"
      >
        <v-col md="4">
          <v-alert :type="alertType" :value="alert" transition="fade-transition">
            {{ alertMessage }}
          </v-alert>
        </v-col>
      </v-row>

      {{ level1 }}
      {{ level2 }}
      
    </v-container>
  </app-layout>
</template>

<script>
import AppLayout from "../../Layouts/App.vue";
import { Link } from "@inertiajs/inertia-vue";
import { required, email } from "vee-validate/dist/rules";
import {
  extend,
  ValidationObserver,
  ValidationProvider,
  setInteractionMode,
} from "vee-validate";

setInteractionMode("eager");

extend("required", {
  ...required,
  message: "{_field_} can not be empty",
});

extend("email", {
  ...email,
  message: "Email must be valid",
});

export default {
  components: {
    AppLayout,
    Link,
    ValidationProvider,
    ValidationObserver,
  },

  props: {
    level1: Array,
    level2: Array,
  },

  data() {
    return {
      name: "",
      loading: false,
      alert: false,
      alertType: "success",
      alertMessage: "",
      result: false,
    };
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
  },
};
</script>