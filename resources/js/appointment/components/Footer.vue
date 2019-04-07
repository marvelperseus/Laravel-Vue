<template>
    <v-footer
            dark
            height="auto"
    >
        <v-card
                flat
                tile
                width="100%"
                class="primary white--text"
        >
            <v-layout row justify-center align-center wrap>
                <v-flex xs12 md6>
                    <v-card-title class="pt-4 justify-center title">{{ clinic.name }}</v-card-title>
                    <v-card-text
                            class="pt-0 text-xs-center body-1"
                    >
                        <p>
                            〒{{ clinic.zip }}<br>
                            {{ clinic.address }}
                        </p>
                        <p>
                            <v-icon small>phone</v-icon> {{ clinic.tel }}<br>
                            <v-icon small>web</v-icon> {{ clinic.url }}<br>
                        </p>
                    </v-card-text>
                </v-flex>
                <v-flex xs12 md6>
                    <v-card-actions class="justify-center">
                        <v-btn flat :href="clinic.top_page_url">TOP</v-btn>
                        <v-btn flat :href="clinic.term_page_url">利用規約</v-btn>
                        <v-btn flat :href="clinic.privacy_policy_page_url">プライバシーポリシー</v-btn>
                    </v-card-actions>
                </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-card-text class="text-xs-center">
                &copy;2019 Ortho Manager
            </v-card-text>
        </v-card>
    </v-footer>
</template>

<script>
    export default {
        name: "Footer",
        props: ['clinic']
    }
</script>

<style scoped>

</style>