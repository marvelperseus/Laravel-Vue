<template>
    <v-toolbar
            color="primary"
            dark
            app
            fixed
    >
        <v-toolbar-title>
            <v-btn flat :href="clinic.top_page_url" class="title">
                {{ clinic.name }}
            </v-btn>
        </v-toolbar-title>

        <template v-if="$vuetify.breakpoint.smAndUp">
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn flat class="title">
                    <v-icon>phone</v-icon>
                    {{ clinic.tel }}
                </v-btn>
            </v-toolbar-items>
        </template>

    </v-toolbar>
</template>

<script>
    export default {
        name: "Header",
        props: ['clinic']
    }
</script>

<style scoped>

</style>