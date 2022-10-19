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

      <v-row class="pa-5 h-100" :align="'center'" :justify="'center'">
        <v-col md="4" :align="'center'" :justify="'center'">
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
                  :items="members"
                  :item-text="'name'"
                  :item-value="'name'"
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
                Calculate Bonus
              </v-btn>
            </form>
          </validation-observer>
        </v-col>
      </v-row>

      <v-row v-if="result">
        <v-col :align="'center'" :justify="'center'">
          <table>
            <tr>
              <th>
                <td>
                  Parent :
                </td>
                <td>
                  {{ level1[0].leader_name }}
                </td>
              </th>
            </tr>

            <hr>

            <tr>
              <th>
                <td>
                  Level 1 :
                </td>
                <template v-for="(item, index) in level1">
                  <td :key="item.member_name">
                    {{ item.member_name }}{{ (index + 1) == level1.length ? '' : ',' }}
                  </td>
                </template>
              </th>
            </tr>

            <tr>
              <th>
                <td>
                  Bonus Level 1 :
                </td>
                <td>
                  $ {{ bonus1 }}
                </td>
              </th>
            </tr>

            <tr>
              <th>
                <td>
                  Level 2 :
                </td>
                <template v-for="(item, index) in level2">
                  <template v-for="(item2, index2) in item">
                    <td :key="item2.member_name">
                      {{ item2.member_name }}{{ (index + 1) == level2.length ? '' : ',' }}{{ ((index2 + 1) == item.length ? '' : ',') }}
                    </td>
                  </template>
                </template>
              </th>
            </tr>

            <tr>
              <th>
                <td>
                  Bonus Level 2 :
                </td>
                <td>
                  $ {{ bonus2 }}
                </td>
              </th>
            </tr>

            <hr>

            <tr>
              <th>
                <td>
                  Total Bonus :
                </td>
                <td>
                  $ {{ bonus }}
                </td>
              </th>
            </tr>
          </table>
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
    members: Array,
  },

  data() {
    return {
      name: "",
      loading: false,
      level1: [],
      level2: [],
      level1Total: 0,
      level2Total: 0,
      level1Bonus: 1.0,
      level2Bonus: 0.5,
      bonus1: 0.0,
      bonus2: 0.0,
      bonus: 0.0,
      alert: false,
      alertType: "success",
      alertMessage: "",
      result: false,
    };
  },

  methods: {
    async submit() {
      this.result = false;
      const valid = await this.$refs.observer.validate();

      if (valid) {
        axios
          .post(this.$page.url + "/submit", {
            name: this.name,
          })
          .then((res) => {
            this.loading = false;

            if (res.data.type) {
              this.showAlert(res.data.type, res.data.message);

              return;
            }

            this.level1 = res.data.level1;
            this.level2 = res.data.level2;

            // console.log(this.level1);
            // console.log(this.level2);

            let count = 0;
            res.data.level2.forEach(element => {
              element.forEach(el => {
                count++;
              });
            });

            this.level1Total = res.data.level1.length;
            this.level2Total = count;

            this.bonus1 = this.level1Total * this.level1Bonus;
            this.bonus2 = this.level2Total * this.level2Bonus;
            this.bonus = this.bonus1 + this.bonus2;

            this.result = true;
          })
          .catch((err) => {
            this.loading = false;

            this.showAlert("error", `${err.response.status} : ${err.message}`);
          });
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

<style scoped>
  table {
    width: 50%;  
    border: 1px solid;
  }

  tr, th, td {
    text-align: center;
    padding: 5px;
  }
</style>