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

    <v-row class="pa-5 h-100" :align="'center'" :justify="'center'">
      <v-col md="4">
        <validation-observer ref="observer" v-slot="{ invalid }">
          <form @submit.prevent="submit" class="pa-5">
            <validation-provider
              v-slot="{ errors }"
              name="Name"
              rules="required"
            >
              <v-text-field
                v-model="name"
                :error-messages="errors"
                label="Name"
                required
                clearable
                filled
                rounded
              ></v-text-field>
            </validation-provider>

            <validation-provider
              v-slot="{ errors }"
              name="Parent"
              rules="required"
            >
              <v-select
                v-model="parent"
                :error-messages="errors"
                :items="members"
                :item-text="'name'"
                :item-value="'id'"
                label="Parent"
                required
                clearable
                filled
                rounded
              ></v-select>
            </validation-provider>

            <v-btn
              type="submit"
              color="primary"
              :loading="loading"
              @click="submit"
              rounded
            >
              Register Member
            </v-btn>
          </form>
        </validation-observer>
      </v-col>
    </v-row>
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
    type: String,
    message: String,
    members: Array,
  },

  data: () => ({
    autofilled: false,
    name: "",
    parent: "",
    loading: false,
    alert: false,
    alertType: "success",
    alertMessage: "",
  }),

  methods: {
    async submit() {
      const valid = await this.$refs.observer.validate();

      if (valid) {
        this.$inertia.post(
          "/member/register/submit",
          {
            name: this.name,
            parent: this.parent,
          },
          {
            replace: true,
            onBefore: (visit) => {
              this.loading = !this.loading;
            },
            onStart: (visit) => {},
            onProgress: (progress) => {},
            onSuccess: (page) => {},
            onError: (errors) => {},
            onCancel: () => {},
            onFinish: (visit) => {
              this.loading = !this.loading;

              if (this.type) {
                this.showAlert(this.type, this.message);
              }
            },
          }
        );
      }
    },

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