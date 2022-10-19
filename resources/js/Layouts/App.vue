<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" app>
            <v-list-item style="background-color: #1f1d55">
                <v-list-item-content>
                    <v-list-item-title class="text-h6">
                        <Link href="/dashboard">
                            <v-img
                                class="mx-auto"
                                src="/images/logo.svg"
                                width="150"
                            />
                        </Link>
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list dense nav rounded>
                <v-list-item-group color="primary">
                    <v-list-group
                        :value="false"
                        prepend-icon="mdi-file-document"
                    >
                        <template v-slot:activator>
                            <v-list-item-title>Services</v-list-item-title>
                        </template>

                        <v-list-item
                            v-for="(service, i) in servicesMenu"
                            :key="i"
                            link
                            :class="{
                                'v-list-item v-item--active v-list-item--active v-list-item--link theme--light v-item--active':
                                    $page.url === service.url,
                            }"
                        >
                            <Link :href="service.url">
                                <v-list-item-title
                                    v-text="service.title"
                                ></v-list-item-title>
                            </Link>
                        </v-list-item>
                    </v-list-group>
                </v-list-item-group>
            </v-list>

            <!-- <div v-if="admin && adminMenu">
                <v-divider></v-divider>

                <v-list dense nav rounded>
                    <v-list-item-group color="primary">
                        <v-list-group
                            :value="false"
                            prepend-icon="mdi-file-document"
                        >
                            <template v-slot:activator>
                                <v-list-item-title>Admin</v-list-item-title>
                            </template>

                            <v-list-item
                                v-for="(service, i) in adminMenu"
                                :key="i"
                                link
                                :class="{
                                    'v-list-item v-item--active v-list-item--active v-list-item--link theme--light v-item--active':
                                        $page.url === service.url,
                                }"
                            >
                                <Link :href="service.url">
                                    <v-list-item-title
                                        v-text="service.title"
                                    ></v-list-item-title>
                                </Link>
                            </v-list-item>
                        </v-list-group>
                    </v-list-item-group>
                </v-list>
            </div> -->
        </v-navigation-drawer>

        <v-app-bar app>
            <v-app-bar-nav-icon @click="drawer = !drawer"> </v-app-bar-nav-icon>

            <v-toolbar-title>Multi Level Marketing</v-toolbar-title>

            <v-spacer></v-spacer>

            <v-menu open-on-hover offset-y :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn color="primary" v-bind="attrs" v-on="on" rounded>
                        <v-icon>mdi-account</v-icon>
                    </v-btn>
                </template>

                <v-card>
                    <v-list>
                        <v-list-item>
                            <v-list-item-avatar>
                                <v-icon>mdi-account</v-icon>
                            </v-list-item-avatar>

                            <v-list-item-content>
                                <v-list-item-title>{{
                                    email
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>

                        <v-btn color="primary" text @click="signOut" rounded>
                            Sign Out
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-menu>
        </v-app-bar>

        <v-main>
            <slot />
        </v-main>
    </v-app>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue";

export default {
    components: {
        Link,
    },

    computed: {
        admin() {
            return this.$page.props.admin;
        },
        email() {
            return this.$page.props.email;
        },
    },

    data() {
        return {
            drawer: null,
            servicesMenu: [
                {
                    url: '/member/register',
                    title: 'Register Member',
                },
                {
                    url: '/member/move',
                    title: 'Move Member',
                },
                {
                    url: '/member/bonus',
                    title: 'Member Bonus',
                },
                {
                    url: '/member/tree',
                    title: 'Member Tree',
                }
            ],
        };
    },

    methods: {
        signOut() {
            this.$inertia.get("/signout");
        },
    },
};
</script>