<template>
    <v-app>
        <v-main>
            <v-container fill-height fluid>
                <v-row
                    :align="'center'"
                    :justify="'center'"
                    :style="{ display: alert ? '' : 'none' }"
                >
                    <v-col md="4">
                        <v-alert
                            :type="alertType"
                            :value="alert"
                            transition="fade-transition"
                        >
                            {{ alertMessage }}
                        </v-alert>
                    </v-col>
                </v-row>

                <v-row class="h-100" :align="'center'" :justify="'center'">
                    <v-col md="4">
                        <v-card elevation="2" class="mx-auto">
                            <v-card-title style="background-color: #1f1d55">
                                <v-img
                                    class="mx-auto"
                                    src="/images/logo.svg"
                                    width="100"
                                />
                            </v-card-title>

                            <v-divider class="mx-4"></v-divider>

                            <v-card-text>
                                <validation-observer
                                    ref="observer"
                                    v-slot="{ invalid }"
                                >
                                    <form @submit.prevent="submit" class="pa-5">
                                        <validation-provider
                                            v-slot="{ errors }"
                                            name="E-mail"
                                            rules="required|email"
                                        >
                                            <v-text-field
                                                v-model="email"
                                                :error-messages="errors"
                                                label="E-mail"
                                                required
                                                clearable
                                                filled
                                                rounded
                                            ></v-text-field>
                                        </validation-provider>

                                        <validation-provider
                                            v-slot="{ errors }"
                                            name="Password"
                                            rules="required"
                                        >
                                            <v-text-field
                                                v-model="password"
                                                :error-messages="errors"
                                                :append-icon="
                                                    show
                                                        ? 'mdi-eye'
                                                        : 'mdi-eye-off'
                                                "
                                                :type="
                                                    show ? 'text' : 'password'
                                                "
                                                @click:append="show = !show"
                                                label="Password"
                                                required
                                                clearable
                                                filled
                                                rounded
                                                :placeholder="
                                                    !autofilled ? ' ' : ''
                                                "
                                            ></v-text-field>
                                        </validation-provider>

                                        <v-btn
                                            type="submit"
                                            color="primary"
                                            :loading="loading"
                                            @click="submit"
                                            rounded
                                        >
                                            Login
                                        </v-btn>
                                    </form>
                                </validation-observer>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
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
        ValidationProvider,
        ValidationObserver,
    },

    props: {
        type: String,
        message: String,
    },

    data: () => ({
        autofilled: false,
        email: "",
        password: "",
        show: false,
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
                    "/signin",
                    {
                        email: this.email,
                        password: this.password,
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
            }, 5000);
        },
    },

    watch: {
        email() {
            this.autofilled = true;
        },
    },
};
</script>