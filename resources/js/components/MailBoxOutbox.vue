<template>
    <v-layout align-center justify-center>
        <v-flex xs12 md10>
            <v-card class="py-3">
                <v-toolbar flat color="white">
                    <v-toolbar-title class="grey--text">送信メール一覧</v-toolbar-title>
                    <v-divider
                            class="ml-4"
                            inset
                            vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-text-field
                            v-model="search"
                            append-icon="search"
                            label="検索"
                            single-line
                            hide-details
                    ></v-text-field>
                </v-toolbar>


            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "MailBoxOutbox",
        data: () => ({
            search: '',


        }),

    }
</script>

<style scoped>

</style>