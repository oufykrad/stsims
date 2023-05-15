<template>
    <Head title="Settings" />
    <PageHeader :title="title" :items="items" />
</template>
<script>
import _ from 'lodash';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components: { PageHeader },
    data() {
        return {
            currentUrl: window.location.origin,
            title: "Settings",
            items: [{text: "Settings",href: "/",}, {text: "Configuration",active: true, }, ],
        };
    }
}
</script>
<style>
    .file-manager-sidebar {
        min-width: 450px;
        max-width: 450px;
        height: calc(100vh - 180px);
    }
    .dropdown-menu-lg {
        width: 89%;
    }
</style>
