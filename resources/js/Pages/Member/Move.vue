<template>
  <app-layout>
    <v-container class="my-5">
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
                <v-select
                  v-model="name"
                  :error-messages="errors"
                  :items="map"
                  :item-text="'member_name'"
                  :item-value="'member_name'"
                  label="Member"
                  required
                  clearable
                  filled
                  rounded
                  v-on:change="changeLeader"
                ></v-select>
              </validation-provider>

              <validation-provider
                v-slot="{ errors }"
                name="Parent"
                rules="required"
              >
                <v-select
                  v-model="parent"
                  :error-messages="errors"
                  :items="parentList"
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
                Move Member
              </v-btn>
            </form>
          </validation-observer>
        </v-col>
      </v-row>
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
    type: String,
    message: String,
    members: Array,
    map: Array,
  },

  data() {
    return {
      name: "",
      parent: 0,
      parentList: [],
      loading: false,
      alert: false,
      alertType: "success",
      alertMessage: "",
    };
  },

  mounted() {
    this.initialize();
  },

  methods: {
    initialize() {
      this.parentList = this.members;
    },

    changeLeader() {
      this.parentList = this.members;
      const existingMember = this.map.filter((val) => {
        return val.leader_name == this.name;
      });
      
      let existingMemberList = [];
      existingMember.forEach((element) => {
        existingMemberList.push(element.member_name);
      });

      this.parentList = this.parentList.filter((el, index) => {
        return el.name != this.name && !existingMemberList.includes(el.name);
      });

      for (let i = 1; i <= this.map.length; i++) {
        if (this.map[i].member_name == this.name) {
          this.parent = this.map[i].leader_id;

          break;
        } else {
          this.parent = 0;
        }
      }
    },

    async submit() {
      const valid = await this.$refs.observer.validate();

      if (valid) {
        this.$inertia.post(
          "/member/move/submit",
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